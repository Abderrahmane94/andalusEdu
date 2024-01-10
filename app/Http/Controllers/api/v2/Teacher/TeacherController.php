<?php

namespace App\Http\Controllers\api\v2\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\TeachersListResource;
use App\Models\StudentRecord;
use App\Models\TeacherEvaluationSetting;
use App\SmAssignSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function studentTeacher($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $result = SmAssignSubject::with('teacher', 'subject')->where('class_id', $record->class_id)
            ->where('section_id', $record->section_id)->distinct('teacher_id')->get();
        $data = TeachersListResource::collection($result);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }
}
