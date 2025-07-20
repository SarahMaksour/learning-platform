<?php

namespace App\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price,
            'image_url' => $this->image ? asset('storage/' . $this->image) : null,
            'videos' => VideoResource::collection($this->videos),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
