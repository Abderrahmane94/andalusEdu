<?php

namespace App\Http\Controllers\api\v2\Timeline;

use App\Http\Controllers\Controller;
use App\SmStudentTimeline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimelineController extends Controller
{
    public function stdTimeline($id)
    {
        $data['timelines'] = SmStudentTimeline::select('id', 'date', 'title', 'description', 'file', 'created_at')->where('staff_student_id', $id)
            ->where('type', 'stu')->where('academic_id', getAcademicId())
            ->where('school_id', Auth::user()->school_id)
            ->get();            
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }
}
