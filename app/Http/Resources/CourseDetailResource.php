<?php

namespace App\Http\Resources;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
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
            'image_course' =>$this->image? asset($this->image) : null,
            'price' => $this->price,
            'isPaid'=>$this->isPaid
            'reviews_count' => $this->reviews_count,
            'reviews_avg_rating' => round($this->reviews_avg_rating, 2),
            'students_count' => $this->enrollments_count,
            'instructor' => [
                'name' => $this->instructor->name,
                'specialization' => $this->instructor->UserDetail->specialization ?? null,
                'image' => $this->instructor->UserDetail->image ?? null,
                'bio'=>$this->instructor->UserDetail->bio
            ],
           'total_video_duration' => $this->total_video_duration_formatted,

        ];
    }
}
