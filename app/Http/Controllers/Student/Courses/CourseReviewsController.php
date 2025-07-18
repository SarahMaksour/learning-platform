<?php

namespace App\Http\Controllers\Student\Courses;

use Illuminate\Http\Request;
use App\Services\CourseReview;
use App\Http\Controllers\Controller;

class CourseReviewsController extends Controller
{
    protected $reviewService;
    public function __construct(CourseReview $reviewService){
        $this->reviewService =$reviewService;
    }
    public function CourseReviews($course_id){
        
    $data=$this->reviewService->getCourseReview($course_id);
    return response()->json([
            'average_rating' => $data['averageRating'],
            'total_reviews' => $data['totalReviews'],
            'ratings' => $data['ratings'],
            'comments' => $data['comments'],

    ],201);
    }
}
