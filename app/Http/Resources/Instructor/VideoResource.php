<?php

namespace App\Http\Resources\Instructor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video_url' => $this->video_path ? asset('storage/' . $this->video_path) : null,
            'duration' => $this->duration,
            'quiz' => $this->quiz ? new QuizResource($this->quiz) : null,
        ];
    }
}
