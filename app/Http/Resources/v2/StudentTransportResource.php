<?php

namespace App\Http\Resources\v2;

use App\SmStudent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class StudentTransportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        
        $user = Auth::user();
        $student_detail = SmStudent::where('user_id', $user->id)->first();
        if(@$student_detail->route_list_id == @$this->route->id && @$student_detail->vechile_id == @$this->vehicle->id){
            $status = 'Assigned';
        }else{
            $status = '';
        }  

        return [
            'id' => $this->id,
            'route' => @$this->route->title,
            'vehicle' => @$this->vehicle->vehicle_no,
            'status' => $status,
        ];

    }
}
