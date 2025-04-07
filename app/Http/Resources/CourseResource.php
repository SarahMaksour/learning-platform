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
            'image_url'        => $this->image ? asset('storage/' . $this->image) : null,
            'average_rating'   => round($this->reviews_avg_rating ?? 0, 1),
            'ratings_count'    => $this->reviews_count ?? 0,
            'students_count'   => $this->enrollments_count ?? 0,
            'user'       => [
                'id'   => $this->user->id ?? null,
                'name' => $this->user->name ?? 'غير معروف',
            ],


];
    }
}
