<?php

namespace App\Http\Controllers\api\v2\NoticeBoard;

use App\Http\Controllers\Controller;
use App\SmNoticeBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeBoardController extends Controller
{
    public function studentNoticeboard(Request $request)
    {
        $data = [];
        $data['allNotices'] = SmNoticeBoard::select('id', 'notice_title', 'notice_message', 'publish_on')->where('active_status', 1)->where('inform_to', 'LIKE', '%2%')
            ->orderBy('id', 'DESC')
            ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->get();
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }

    public function studentSingleNoticeboard($id)
    {
        $data = [];
        $data = SmNoticeBoard::select('id', 'notice_title', 'notice_message', 'publish_on')->where('active_status', 1)->where('inform_to', 'LIKE', '%2%')
            ->where('academic_id', getAcademicId())->where('school_id', Auth::user()->school_id)->findOrFail($id);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }
}
