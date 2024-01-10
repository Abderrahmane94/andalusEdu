<?php

namespace App\Http\Controllers\api\v2\Attendance;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\SmAssignSubject;
use App\SmStudentAttendance;
use App\SmSubjectAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function stdAttendCurrMonth(Request $request)
    {
        if ($request->year || $request->month) {
            $validator = Validator::make($request->all(), [
                'month' => 'required',
                'year' => 'required',
            ]);    
            if ($validator->fails()) {
                $response = [
                    'status'  => false,
                    'data' => $validator->errors(),
                    'message' => 'Operation failed',
                ];
                return response()->json($response, 401);
            }
        }      

        $student_detail = Auth::user()->student->load('studentRecords', 'attendances');

        if ($request->year && $request->month) {
            $year = $request->year;
            $month = $request->month;
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month  = $now->month;
        }
        
        if ($month < 10) {
            $month = '0' . $month;
        }
        $current_day = date('d');

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $year);
        $previous_month = $month - 1;
        $previous_date = $year . '-' . $previous_month . '-' . $days2;

        $previousMonthDetails['date'] = $previous_date;
        $previousMonthDetails['day'] = $days2;
        $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));
        // SmSubjectAttendance
        $attendances = SmStudentAttendance::where('student_id', $student_detail->id)
            ->where('attendance_date', 'like', '%' . $year . '-' . $month . '%')
            ->where('student_record_id', $request->record_id)
            ->select('attendance_type', 'attendance_date')
            ->get();

        $data['attendances'] = $attendances;
        $data['previousMonthDetails'] = $previousMonthDetails;
        $data['days'] = $days;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['current_day'] = $current_day;
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }

    public function stdAttendSubjectWise(Request $request)
    {   
        if ($request->subject_id || $request->year || $request->month) {
            $validator = Validator::make($request->all(), [
                'subject_id' => 'required',
                'month' => 'required',
                'year' => 'required',
            ]);    
            if ($validator->fails()) {
                $response = [
                    'status'  => false,
                    'message' => $validator->errors(),
                    'message' => 'Operation failed',
                ];
                return response()->json($response, 401);
            }
        }      

        $student_detail = Auth::user()->student->load('studentRecords', 'attendances');

        if ($request->year && $request->month) {
            $year = $request->year;
            $month = $request->month;
            $subject_id = $request->subject_id;
        } else {
            $now = Carbon::now();
            $year = $now->year;
            $month  = $now->month;
        }
        
        if ($month < 10) {
            $month = '0' . $month;
        }
        $current_day = date('d');

        $days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days2 = cal_days_in_month(CAL_GREGORIAN, $month - 1, $year);
        $previous_month = $month - 1;
        $previous_date = $year . '-' . $previous_month . '-' . $days2;

        $previousMonthDetails['date'] = $previous_date;
        $previousMonthDetails['day'] = $days2;
        $previousMonthDetails['week_name'] = date('D', strtotime($previous_date));
        if ($request->subject_id) {
            $attendances = SmSubjectAttendance::where('student_id', $student_detail->id)
                ->where('attendance_date', 'like', '%' . $year . '-' . $month . '%')
                ->where('subject_id', $subject_id)
                ->where('student_record_id', $request->record_id)
                ->select('attendance_type', 'attendance_date')
                ->get();
        }else{
            $attendances = SmSubjectAttendance::where('student_id', $student_detail->id)
            ->where('attendance_date', 'like', '%' . $year . '-' . $month . '%')
            ->where('student_record_id', $request->record_id)
            ->select('attendance_type', 'attendance_date')
            ->get();
        }
        $data['attendances'] = $attendances;
        $data['previousMonthDetails'] = $previousMonthDetails;
        $data['days'] = $days;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['current_day'] = $current_day;
        $data['status'] = 'Present: P, Late: L, Absent: A, Holiday: H, Half Day: F';

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }

    public function recWiseAllSubjects($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $assignSubjects = SmAssignSubject::with(['subject' => function ($q1) {
            $q1->select('id', 'subject_name','subject_code', 'subject_type');
        }])->select('active_status', 'subject_id')->where('class_id', $record->class_id)->where('section_id', $record->section_id)->where('academic_id', $record->academic_id)->where('school_id', Auth::user()->school_id)->get();
        $response = [
            'success' => true,
            'data'    => $assignSubjects,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }
}
