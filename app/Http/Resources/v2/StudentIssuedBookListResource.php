<?php

namespace App\Http\Resources\v2;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentIssuedBookListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $now=new DateTime($this->given_date);
        $end=new DateTime($this->due_date);
        if($this->issue_status == 'I'){
            if($end<$now){        
                $status = __('library.expired');
            }else{
                $status = __('library.issued');
            }
        }   
        return [
            'id' => $this->id,
            'book_title' => $this->book_title,
            'book_number' => $this->book_number,
            'isbn_no' => $this->isbn_no,
            'author_name' => $this->author_name,
            'subject' => $this->subject_name,
            'issue_date' => $this->given_date,
            'return_date' => $this->due_date,
            'status' => $status,
        ];

    }
}
