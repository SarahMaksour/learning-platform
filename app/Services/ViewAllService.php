<?php
namespace App\Services;
use App\Models\Course;

class ViewAllService
{
    public function getPopularCourses()
    {
        return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('user:id,name')
            ->where('enrollments_count', '>', 1000)
            ->orderBy('enrollments_count', 'desc')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }
public function getTopRatedCourses(){
        return Course::withCount('enrollments')
        ->withAvg('reviews','rating')
            ->withCount('enrollments')
            ->with('user:id,name')
            ->orderByDesc('reviews_avg_rating')
            ->where('reviews_avg_rating')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
}
}
