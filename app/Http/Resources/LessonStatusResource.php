<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'lesson_id' => $this->id,
            'lesson_name' => optional($this->contentable)->title,
            'duration' => optional($this->contentable)->duration,
            'video_number' => $this->videoNum ?? null,
            'is_paid' => $this->is_paid,
            'is_previous_lesson_passed' => $this->is_previous_lesson_passed,
        ];
    }
}
