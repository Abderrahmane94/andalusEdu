<?php

namespace App\Http\Controllers\api\v2\Assignment;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\StudentRecord;
use App\SmNotification;
use App\SmStudent;
use App\SmTeacherUploadContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function studentAssignment($record_id)
    {
        $record = StudentRecord::findOrFail($record_id);
        $assignment = SmTeacherUploadContent::with('classes', 'sections')->where('content_type', 'as')
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
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }

    public function studentAssignmentFileDownload($id)
    {
        $homeworkDetails = SmTeacherUploadContent::select('upload_file')->find($id);
        if(isset($homeworkDetails) && !empty($homeworkDetails->upload_file)){
            $file_path = $homeworkDetails->upload_file;
            return response()->download($file_path);
        }else{
            $response = [
                'success' => true,
                'data'    => null,
                'message' => 'File not available',
            ];    
            return response()->json($response, 200);
        }

    }

    public function uploadContentView(Request $request, $id)
    {
        $ContentDetails = SmTeacherUploadContent::with(['classes' => function ($q) {
            $q->select('id', 'class_name');
        }, 'sections' => function ($q) {
            $q->select('id', 'section_name');
        }])->select('id', 'upload_date', 'content_title', 'available_for_admin', 'available_for_all_classes', 'upload_file', 'class', 'section')->where('id', $id)->where('school_id', auth()->user()->school_id)->first();
        $response = [
            'success' => true,
            'data'    => $ContentDetails,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    
}
