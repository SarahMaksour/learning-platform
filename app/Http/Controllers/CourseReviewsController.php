<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CourseReview;

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
