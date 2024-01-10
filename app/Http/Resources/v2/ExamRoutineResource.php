<?php

namespace App\Http\Resources\v2;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamRoutineResource extends JsonResource
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
            'id' => $this->id,
            'date_and_day' => dateConvert($this->date).' '.Carbon::createFromFormat('Y-m-d', $this->date)->format('l'),
            'subject' => $this->subject->subject_name,
            'class_section' => $this->class->class_name.' ('.$this->section->section_name.') ',
            'teacher' => $this->teacher->full_name,
            'time' => date('h:i A', strtotime(@$this->start_time)).' - '.date('h:i A', strtotime(@$this->end_time)),
            'duration' => timeCalculation(strtotime($this->end_time)-strtotime($this->start_time)),
            'room' => $this->classRoom->room_no,
        ];
    }
}
