<?php

namespace App\Http\Controllers\api\v2\Dormitory;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\StudentDormitoryResource;
use App\SmDormitoryList;
use App\SmRoomList;
use App\SmRoomType;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DormitoryController extends Controller
{
    public function studentDormitory()
    {
        $user = Auth::user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        $room_lists = SmRoomList::with('dormitory')->where('active_status', 1)->where('id', $student_detail->room_id)->where('school_id', Auth::user()->school_id)->groupBy('dormitory_id')->get();
        $data = StudentDormitoryResource::collection($room_lists);
        
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);
    }
}
