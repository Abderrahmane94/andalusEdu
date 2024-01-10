<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FmFeesInvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $balance = $this->Tamount + $this->Tfine - ($this->Tpaidamount + $this->Tweaver);
        if ($balance == 0) {
            $status = 'Paid';
        } elseif($balance>0) {
            $status = 'Partial';        
        } else{
            $status = 'Unpaid';        
        }        
        return [
            'id' => $this->id,
            'full_name' => $this->studentInfo->full_name,
            'class' => $this->recordDetail->class->class_name,
            'section' => $this->recordDetail->section->section_name,
            'amount' => currency_format($this->Tamount),
            'waiver' => currency_format($this->Tweaver),
            'fine' => currency_format($this->Tfine),
            'paid_amount' => currency_format($this->Tpaidamount),
            'balance' => currency_format($balance),
            'status' => $status,                       
            'create_date' => $this->create_date,                       
        ];

        
    }
}
