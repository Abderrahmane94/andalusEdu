<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmStudentTransportResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'route' => @$this->route->title,
            'vehicle' => @$this->vehicle->vehicle_no,
            'dormitory' => @$this->dormitory->dormitory_name,
            'driver' => @$this->vehicle->driver->full_name,
            'mobile' => @$this->vehicle->driver->mobile,
        ];
    }
}
