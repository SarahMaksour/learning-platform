<?php
namespace App\Services\Api;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class HomeService
{
public function getPopularCourses(){

   return Course::withCount(['enrollments', 'reviews'])
        ->withAvg('reviews', 'rating')
        ->with('instructor:id,name')
        ->orderByDesc('enrollments_count') // عدد التسجيلات
        ->take(3)
        ->get(['id', 'title', 'price', 'user_id']);

}

public function getTopRatedCourses(){
    return Course::withAvg('reviews','rating')
    ->withCount('enrollments')
    ->with('instructor:id,name')
    ->orderByDesc('reviews_avg_rating')
    ->take(3)
        ->get(['id', 'title', 'price', 'image', 'user_id']);
       
}
}