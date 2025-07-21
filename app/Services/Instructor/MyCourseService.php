<?php

namespace App\Services\MyCourse;

use App\Models\Course;
use GuzzleHttp\Psr7\Request;

class MyCourseService{
    public function getMyCourse(){
        $user=Auth()->user();
        $user_id=$user->id;
        
         return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('instructor:id,name')
            ->where('user_id',$user_id)
            ->orderByDesc('enrollments_count')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }
}