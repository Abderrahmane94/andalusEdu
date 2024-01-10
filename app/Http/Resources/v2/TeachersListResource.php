<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeachersListResource extends JsonResource
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
            'staff_photo' => $this->teacher->staff_photo,
            'full_name' => $this->teacher->full_name,
            'subject_name' => $this->subject->subject_name,
            'email' => $this->teacher->email,
            'mobile' => $this->teacher->mobile
        ];
    }
}
