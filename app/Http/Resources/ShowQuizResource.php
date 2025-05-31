<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowQuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
'id'=>$this->id,
'title'=>$this->title,
'type'=>$this->type,
'total_point'=>$this->total_point,
'question'=>QuestionResource::collection($this->whenLoaded('questions'))
        ];
    }
}
