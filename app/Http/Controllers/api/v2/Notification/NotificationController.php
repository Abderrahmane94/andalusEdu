<?php

namespace App\Http\Controllers\api\v2\Notification;

use App\Http\Controllers\Controller;
use App\Http\Resources\SmNotificationResource;
use App\SmNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function allNotificationList()
    {
        $notifications = SmNotification::orderBy('id', 'DESC')->with('user')->where('user_id',auth()->user()->id)->where('is_read',0)->get();
        $data['unread_notifications'] = SmNotificationResource::collection(@$notifications);
        $data['unread_notifications_count'] = @$notifications->count();
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);           
    }

    public function allNotificationMarkRead(Request $request)
    {
        $user = Auth()->user();
        if(Auth()->user()->role_id != 1){
            if($user->role_id == 2){
                SmNotification::where('user_id', Auth::user()->id)->where('role_id', 2)->update(['is_read' => 1]);
            }elseif($user->role_id == 3){
                SmNotification::where('user_id', Auth::user()->id)->where('role_id', '!=', 2)->update(['is_read' => 1]);
            }else{
                SmNotification::where('user_id', $user->id)->where('role_id', '!=', 2)->where('role_id', '!=', 3)->update(['is_read' => 1]);
            }
        }else{
            SmNotification::where('user_id', $user->id)->where('role_id', 1)->update(['is_read' => 1]);
        }
        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Operation successful',
        ];

        return response()->json($response, 200);
    }
}
