<?php

namespace App\Http\Resources;

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
        'id'               => $this->id,
        'title'            => $this->title,
        'price'            => $this->price,
        'image_url'        => $this->image ? asset($this->image) : null,
        'average_rating'   => (string) round($this->reviews_avg_rating ?? 0, 2),
        'ratings_count'    => $this->reviews_count ?? 0,
        'students_count'   => $this->enrollments_count ?? 0,
        'instructor'       => [
            'id'   => $this->instructor->id ?? null,
            'name' => $this->instructor->name ?? 'غير معروف',
            'total_video_duration_seconds' => $this->total_video_duration ?? 0,
        ],
    ];   
}
}