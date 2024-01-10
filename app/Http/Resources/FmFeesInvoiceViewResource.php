<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceViewResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'record_id' => $this->recordDetail->id,
            'student_id' => $this->studentInfo->id,
            'full_name' => $this->studentInfo->full_name,
            'admission_no' => $this->studentInfo->admission_no,
            'roll_no' => $this->studentInfo->roll_no,
            'class' => $this->recordDetail->class->class_name,
            'section' => $this->recordDetail->section->section_name,
            'create_date' => $this->create_date,
            'due_date' => $this->due_date,       
            'paid_amount' => $this->paid_amount,
              
        ];

    }
}
