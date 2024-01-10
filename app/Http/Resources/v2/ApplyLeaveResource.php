<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplyLeaveResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($this->approve_status == 'P'){
            $status = __('common.pending');
        }elseif($this->approve_status == 'A'){
            $status = __('common.approved');
        }elseif($this->approve_status == 'C'){
            $status = __('leave.cancelled');
        }
        return [
            'id' => $this->id,
            'leave_type' => $this->leaveDefine->leaveType->type,
            'from' => dateConvert($this->leave_from),
            'to' => dateConvert($this->leave_to),
            'apply_date' => dateConvert($this->apply_date),
            'status' => $status,
        ];
    }
}
