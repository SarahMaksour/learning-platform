<?php

namespace App\Services;

use App\Models\Course;

class HomeService
{
    public function getPopularCourses()
    {
        return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('user:id,name')
            ->having('enrollments_count', '>', 1000) 
            ->orderByDesc('enrollments_count')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }

    public function getTopRatedCourses()
    {
        return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('user:id,name')
            ->having('reviews_avg_rating', '>=', 4.0) 
            ->orderByDesc('reviews_avg_rating')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }
    
}
