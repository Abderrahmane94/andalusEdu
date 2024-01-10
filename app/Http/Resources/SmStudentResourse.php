<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmStudentResourse extends JsonResource
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
            'student_photo' => $this->student_photo,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'admission_no' => $this->admission_no,
            'date_of_birth' => $this->date_of_birth,
            'age' => $this->age,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'current_address' => $this->current_address,
            'permanent_address' => $this->permanent_address,
            'blood_group' => @$this->bloodGroup->base_setup_name,
            'religion' => @$this->religion->base_setup_name,
        ];
    }
}
