<?php

namespace App\Http\Controllers\api\v2\Transport;

use App\Http\Controllers\Controller;
use App\Http\Resources\v2\StudentTransportResource;
use App\SmAssignVehicle;
use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransportController extends Controller
{
    public function studentTransport()
    {

        $user = Auth::user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        $routes = SmAssignVehicle::with('route', 'vehicle')
            ->join('sm_vehicles', 'sm_assign_vehicles.vehicle_id', 'sm_vehicles.id')
            ->join('sm_students', 'sm_vehicles.id', 'sm_students.vechile_id')
            ->where('sm_assign_vehicles.active_status', 1)
            ->where('sm_students.user_id', Auth::user()->id)
            ->where('sm_assign_vehicles.school_id', Auth::user()->school_id)
            ->get();
        $data = StudentTransportResource::collection($routes);
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => 'Operation successful',
        ];
        return response()->json($response, 200);

    }
}
