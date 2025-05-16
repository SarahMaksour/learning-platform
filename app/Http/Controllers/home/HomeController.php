<?php

namespace App\Http\Controllers\home;

use Illuminate\Http\Request;
use App\Services\Api\HomeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\CourseResource;

class HomeController extends Controller
{
    protected $homeService;
    public function __construct(HomeService $homeService){
        $this->homeService=$homeService;
    }
    
public function homePage(){
    $featuredPopular=$this->homeService->getPopularCourses();
    $featuredTopRated=$this->homeService->getTopRatedCourses();
   $user=auth()->user();
    return response()->json([
         'user' => new UserResource($user),
        'popular_courses' => CourseResource::collection($featuredPopular),
        'top_rated_courses' => CourseResource::collection($featuredTopRated),
        ]
    ,201);
}
}
