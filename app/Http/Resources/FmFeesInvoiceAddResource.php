<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceAddResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'record_id' => $this->recordDetail->id,
            'student_id' => $this->studentInfo->id,
            'student_photo' => $this->studentInfo->student_photo,
            'full_name' => $this->studentInfo->full_name,
            'admission_no' => $this->studentInfo->admission_no,
            'roll_no' => $this->studentInfo->roll_no,
            'class' => $this->recordDetail->class->class_name,
            'section' => $this->recordDetail->section->section_name,
            'wallet_balance' => $this->studentInfo->user->wallet_balance,
        ];


    }
}
