<?php

namespace App\Http\Controllers\api\v2\Leave;

use App\Http\Controllers\Controller;
use App\Http\Resources\RemainingLeaveResource;
use App\Http\Resources\v2\ApplyLeaveResource;
use App\Models\User;
use App\Notifications\LeaveApprovedNotification;
use App\SmGeneralSettings;
use App\SmLeaveRequest;
use App\SmLeaveDefine;
use App\SmNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Traits\NotificationSend;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    use NotificationSend;

    public function remainingLeave()
    {
        $user = Auth::user();
        if ($user) {
            $my_leaves = SmLeaveDefine::where('role_id', $user->role_id)->where('user_id', $user->id)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $data = RemainingLeaveResource::collection($my_leaves);
        }
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }

    public function applyLeave()
    {
        $user = Auth::user();
        if ($user) {
            $apply_leaves = SmLeaveRequest::where('staff_id', $user->id)->where('role_id', $user->role_id)->where('active_status', 1)->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
            $data = ApplyLeaveResource::collection($apply_leaves);
        }
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }

    public function leaveStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => 'required|before_or_equal:leave_to',
            'leave_to' => "required",
            'attach_file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
        ]);
        if ($validator->fails()) {
            $response = [
                'status'  => false,
                'data' => $validator->errors(),
                'message' => 'Operation failed',
            ];
            return response()->json($response, 401);
        }

        $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
        $file = $request->file('attach_file');
        $fileSize = filesize($file);
        $fileSizeKb = ($fileSize / 1000000);
        if ($fileSizeKb >= $maxFileSize) {
            $response = [
                'status'  => false,
                'data' => null,
                'message' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
            ];
            return response()->json($response, 401);
        }
        $input = $request->all();
        $fileName = "";
        if ($request->file('attach_file') != "") {
            $file = $request->file('attach_file');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/leave_request/', $fileName);
            $fileName = 'public/uploads/leave_request/' . $fileName;
        }
        $user = auth()->user();
        if ($user) {
            $login_id = $user->id;
            $role_id = $user->role_id;
        } else {
            $login_id = $request->login_id;
            $role_id = $request->role_id;
        }
        $leaveDefine = SmLeaveDefine::with('leaveType:id')->find($request->leave_type, ['id', 'type_id']);
        $apply_leave = new SmLeaveRequest();
        $apply_leave->staff_id = $login_id;
        $apply_leave->role_id = $role_id;
        $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
        $apply_leave->leave_define_id = $request->leave_type;
        $apply_leave->type_id = $leaveDefine->leaveType->id;
        $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
        $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
        $apply_leave->approve_status = 'P';
        $apply_leave->reason = $request->reason;
        $apply_leave->file = $fileName;
        $apply_leave->academic_id = getAcademicId();
        $apply_leave->school_id = auth()->user()->school_id;
        $result = $apply_leave->save();

        $data['to_date'] = $apply_leave->leave_to;
        $data['name'] = $apply_leave->user->full_name;
        $data['from_date'] = $apply_leave->leave_from;
        $data['class_id'] = $apply_leave->student->studentRecord->class_id;
        $data['section_id'] = $apply_leave->student->studentRecord->section_id;
        $records = $this->studentRecordInfo($request->class, $request->section)->pluck('studentDetail.user_id');
        $this->sent_notifications('Leave_Apply', $records, $data, ['Student']);

        $data['name'] = $user->full_name;
        $data['email'] = $user->email;
        $data['role'] = $user->roles->name;
        $data['apply_date'] = $request->apply_date;
        $data['leave_from'] = $request->leave_from;
        $data['leave_to'] = $request->leave_to;
        $data['reason'] = $request->reason;
        send_mail($user->email, $user->full_name, "leave_applied", $data);

        $user = User::where('role_id', 1)->first();
        $notification = new SmNotification();
        $notification->user_id = $user->id;
        $notification->role_id = $user->role_id;
        $notification->date = date('Y-m-d');
        $notification->message = app('translator')->get('leave.leave_request');
        $notification->school_id = Auth::user()->school_id;
        $notification->academic_id = getAcademicId();
        $notification->save();
        Notification::send($user, new LeaveApprovedNotification($notification));
        if ($result) {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Operation successful',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Operation Failed',
            ];
            return response()->json($response, 200);
        }
    }

    public function studentLeaveEdit(Request $request, $id)
    {
        $data['apply_leave'] = SmLeaveRequest::select('id', 'apply_date', 'leave_from', 'leave_to', 'reason', 'file', 'leave_define_id')->find($id);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation Failed',
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $request->validate([
            'apply_date' => "required",
            'leave_type' => "required",
            'leave_from' => 'required|before_or_equal:leave_to',
            'leave_to' => "required",
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png",
        ]);

        $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
        $file = $request->file('attach_file');
        $fileSize = filesize($file);
        $fileSizeKb = ($fileSize / 1000000);
        if ($fileSizeKb >= $maxFileSize) {
            $response = [
                'status'  => false,
                'data' => null,
                'message' => 'Max upload file size ' . $maxFileSize . ' Mb is set in system',
            ];
            return response()->json($response, 401);
        }
        $fileName = "";
        if ($request->file('attach_file') != "") {
            $apply_leave = SmLeaveRequest::find($request->id);
            if (file_exists($apply_leave->file)) {
                unlink($apply_leave->file);
            }

            $file = $request->file('attach_file');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/leave_request/', $fileName);
            $fileName = 'public/uploads/leave_request/' . $fileName;
        }

        $user = Auth()->user();
        if ($user) {
            $login_id = $user->id;
            $role_id = $user->role_id;
        } else {
            $login_id = $request->login_id;
            $role_id = $request->role_id;
        }

        $apply_leave = SmLeaveRequest::find($request->id);
        $apply_leave->staff_id = $login_id;
        $apply_leave->role_id = $role_id;
        $apply_leave->apply_date = date('Y-m-d', strtotime($request->apply_date));
        $apply_leave->leave_define_id = $request->leave_type;
        $apply_leave->leave_from = date('Y-m-d', strtotime($request->leave_from));
        $apply_leave->leave_to = date('Y-m-d', strtotime($request->leave_to));
        $apply_leave->approve_status = 'P';
        $apply_leave->reason = $request->reason;
        if ($fileName != "") {
            $apply_leave->file = $fileName;
        }
        $result = $apply_leave->save();
        if ($result) {
            $response = [
                'success' => true,
                'data'    => $apply_leave,
                'message' => 'Operation successful',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'Operation Failed',
            ];
            return response()->json($response, 200);
        }
    }
}
