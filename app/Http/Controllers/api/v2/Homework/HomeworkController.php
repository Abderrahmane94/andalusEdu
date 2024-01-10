<?php

namespace App\Http\Controllers\api\v2\Homework;

use App\Http\Controllers\Controller;
use App\Http\Resources\SmHomeworkResource;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmHomework;
use App\SmNotification;
use App\SmStudent;
use App\SmUploadHomeworkContent;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\StudentHomeworkSubmitNotification;

class HomeworkController extends Controller
{
    public function adminTeacherhomework()
    {
        $all_homeworks = SmHomework::with(['subjects' => function($q){
            $q->select('id', 'subject_name');
        }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id');      
        $all_homeworks->where('school_id', app('school')->id)->orderby('id','DESC')->where('academic_id', getAcademicId());
        if (teacherAccess()) {
            $homeworkLists = $all_homeworks->where('created_by', auth()->user()->id)->get();
        } else {
            $homeworkLists = $all_homeworks->get();
        }     
 

        $response = [
            'success' => true,
            'data'    => $homeworkLists,
            'message' => 'Operation successful'
        ];
        return response()->json($response, 200);
    }


    public function studentHomework($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $homeworkLists = SmHomework::with('subjects', 'lmsHomeworkCompleted')->where('school_id', auth()->user()->school_id)
                            ->where(function ($que) use ($record) {
                                return $que->where('class_id', $record->class_id)
                                    ->orWhereNull('class_id');
                            })
                            ->where(function ($que) use ($record) {
                                return $que->where('section_id', $record->section_id)
                                    ->orWhereNull('section_id');
                            })
                            ->where('course_id', '=', null)
                            ->where('chapter_id', '=', null)
                            ->where('lesson_id', '=', null)
                            ->where('academic_id', getAcademicId())
                            ->where('school_id', auth()->user()->school_id)
                            ->get();
                            

                            // SmHomeworkStudent

        $data['homeworkLists'] = SmHomeworkResource::collection($homeworkLists);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful'
        ];
        return response()->json($response, 200);


    }

    public function parentHomework($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        
        $homeworkLists = SmHomework::with(['subjects' => function($q){
            $q->select('id', 'subject_name');
        }])->select('created_at', 'submission_date', 'evaluation_date', 'active_status', 'marks', 'subject_id')
        ->where('school_id', auth()->user()->school_id)
        ->where(function ($que) use ($record) {
            return $que->where('class_id', $record->class_id)
                ->orWhereNull('class_id');
        })
        ->where(function ($que) use ($record) {
            return $que->where('section_id', $record->section_id)
                ->orWhereNull('section_id');
        })
        ->where('course_id', '=', null)
        ->where('chapter_id', '=', null)
        ->where('lesson_id', '=', null)
        ->where('academic_id', getAcademicId())
        ->where('school_id', auth()->user()->school_id)
        ->get();

        $response = [
            'success' => true,
            'data'    => $homeworkLists,
            'message' => 'Operation successful'
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkView($class_id, $section_id, $homework_id)
    {
        $homeworkDetails = SmHomework::where('class_id', '=', $class_id)->where('section_id', '=', $section_id)->where('id', '=', $homework_id)->first();
        $response = [
            'success' => true,
            'data'    => $homeworkDetails,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function studentHomeworkFileDownload($id)
    {
        $homeworkDetails = SmHomework::findOrFail($id);
        $file_path = $homeworkDetails->file;

        return response()->download($file_path);
    }

    public function uploadHomeworkContent(Request $request)
    {
        if ($request->file('files') == "") {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'No file uploaded.',
            ];
            return response()->json($response, 422);
        }

        $user = Auth::user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        $data = [];
        foreach ($request->file('files') as $key => $file) {
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/homeworkcontent/', $fileName);
            $fileName = 'public/uploads/homeworkcontent/' . $fileName;
            $data[$key] = $fileName;
        }
        $all_filename = json_encode($data);
        $content = new SmUploadHomeworkContent();
        $content->file = $all_filename;
        $content->student_id = $student_detail->id;
        $content->homework_id = $request->id;
        $content->school_id = Auth::user()->school_id;
        $content->academic_id = getAcademicId();
        $content->save();

        $homework_info = SmHomeWork::find($request->id);
        $teacher_info = $teacher_info = User::find($homework_info->created_by);

        $notification = new SmNotification();
        $notification->user_id = $teacher_info->id;
        $notification->role_id = $teacher_info->role_id;
        $notification->date = date('Y-m-d');
        $notification->message = Auth::user()->student->full_name . ' ' . app('translator')->get('homework.submitted_homework');
        $notification->school_id = Auth::user()->school_id;
        $notification->academic_id = getAcademicId();
        $notification->save();

        $user = User::find($teacher_info->id);
        Notification::send($user, new StudentHomeworkSubmitNotification($notification));
        
        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation Successful',
        ];
        return response()->json($response, 422);
        
    }
}
