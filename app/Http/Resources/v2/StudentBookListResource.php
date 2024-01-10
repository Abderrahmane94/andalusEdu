<?php

namespace App\Http\Resources\v2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentBookListResource extends JsonResource
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
            'book_title' => $this->book_title,
            'book_number' => $this->book_number,
            'isbn_no' => $this->isbn_no,
            'category' => $this->bookCategory->category_name,
            'subject' => $this->bookSubject->subject_name,
            'publisher_name' => $this->publisher_name,
            'author_name' => $this->author_name,
            'quantity' => $this->quantity,
            'price' => $this->book_price,
        ];
    }
}
