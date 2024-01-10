<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SmHomeworkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        if (isset($this->lmsHomeworkCompleted->complete_status)&& ($this->lmsHomeworkCompleted->complete_status == 'C')) {
            $status = 'Completed';
        } else {
            $status = 'Incompleted';
        }       

        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'submission_date' => $this->submission_date,
            'evaluation_date' => $this->evaluation_date,
            'status' => @$status,
            'marks' => $this->marks,
            'subject' => @$this->subjects->subject_name,
        ];

    }
}
