<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    protected HomeService $homeService;
    public function __construct(HomeService $homeService){
        $this->homeService=$homeService;

    }
    public function homePage(){
        $featuredPopular=$this->homeService->getPopularCourses();
        $featuredTopRated=$this->homeService->getTopRatedCourses();

        return response()->json([

            'popular_courses' => CourseResource::collection($featuredPopular),
            'top_rated_courses' => CourseResource::collection($featuredTopRated),
        ]);
    }

}
