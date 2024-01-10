<?php

namespace App\Http\Controllers\api\v2\ClassRoutine;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\SmClassRoutineUpdate;
use App\SmStudent;
use Illuminate\Http\Request;

class ClassRoutineController extends Controller
{
    public function studentClassRoutine(Request $request, $user_id, $record_id = null)
    {
        $record = StudentRecord::select('class_id', 'section_id', 'id')->find($record_id);
        $class_id = $record->class_id;
        $section_id = $record->section_id;
        $school_id = auth()->user()->school_id;
        $data['class_routines'] = SmClassRoutineUpdate::withOutGlobalScope(StatusAcademicSchoolScope::class)->with('weekend', 'classRoom', 'subject', 'teacherDetail', 'class', 'section')->where('class_id', $class_id)->where('section_id', $section_id)
            ->where('school_id', $school_id)->get()
            ->map(function ($value) {
                return [
                    'id' => $value->id,
                    'day' => $value->weekendApi ? $value->weekendApi->name : '',
                    'room' => $value->classRoomApi ? $value->classRoomApi->room_no : '',
                    'subject' => $value->subjectApi ? $value->subjectApi->subject_name : '',
                    'teacher' => $value->teacherDetailApi ? $value->teacherDetailApi->full_name : '',
                    'class' => $value->classApi ? $value->classApi->class_name : '',
                    'section' => $value->sectionApi ? $value->sectionApi->section_name : '',
                    'start_time' => date('h:i A', strtotime($value->start_time)),
                    'end_time' => date('h:i A', strtotime($value->end_time)),
                    'break' => $value->is_break ? 'Yes' : 'No',
                ];
            });
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }
}
