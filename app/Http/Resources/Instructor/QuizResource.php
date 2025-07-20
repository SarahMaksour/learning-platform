<?php

namespace App\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
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
            'title' => $this->title,
            'total_point' => $this->total_point,
            'type' => $this->type,
            'questions' => QuestionResource::collection($this->questions),
        ];
    }
}
