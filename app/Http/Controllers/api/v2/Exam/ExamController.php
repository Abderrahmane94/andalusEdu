<?php

namespace App\Http\Controllers\api\v2\Exam;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\ExamResource;
use App\Http\Resources\v2\ExamRoutineResource;
use App\Models\StudentRecord;
use App\SmAcademicYear;
use App\SmAssignSubject;
use App\SmExam;
use App\SmExamSchedule;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function studentExam($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $exam = SmExam::with('examType', 'class', 'section')->where('class_id', $record->class_id)->where('section_id', $record->section_id)->where('school_id', Auth::user()->school_id)->where('academic_id', getAcademicId())->where('active_status', 1)->get();
        $data = ExamResource::collection($exam);
        
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function studentExamSchedule(Request $request)
    {
        $smExam = SmExam::findOrFail($request->exam_id);
        $assign_subjects = SmAssignSubject::where('class_id', $smExam->class_id)->where('section_id', $smExam->section_id)
            ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $schedule = SmExamSchedule::where('class_id', $smExam->class_id)
            ->where('section_id', $smExam->section_id)
            ->where('exam_term_id', $smExam->exam_type_id)
            ->orderBy('date', 'ASC')->get();
        $data = ExamRoutineResource::collection($schedule);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }




}
