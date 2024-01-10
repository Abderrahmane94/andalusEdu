<?php

namespace App\Http\Controllers\api\v2\OthersStudyMaterial;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\StudentRecord;
use App\SmTeacherUploadContent;
use Illuminate\Http\Request;

class OthersStudyMaterialController extends Controller
{
    public function othersDownload($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $assignment = SmTeacherUploadContent::with('classes', 'sections')->where('content_type', 'ot')
            ->where(function ($que) use ($record) {
                return $que->where('class', $record->class_id)
                    ->orWhereNull('class');
            })
            ->where(function ($que) use ($record) {
                return $que->where('section', $record->section_id)
                    ->orWhereNull('section');
            })
            ->where('course_id', '=', null)
            ->where('chapter_id', '=', null)
            ->where('lesson_id', '=', null)
            ->where('academic_id', $record->academic_id)
            ->where('school_id', auth()->user()->school_id)
            ->get();
        $data = AssignmentResource::collection($assignment);

        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful'
        ];

        return response()->json($response, 200);
    }
}
