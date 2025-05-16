<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HomeService;
use App\Services\ViewAllService;
use App\Http\Resources\CourseResource;

class ViewAllController extends Controller
{
    //
    protected  $viewAllService;
    public function __construct(ViewAllService $viewAllService){
        $this->viewAllService=$viewAllService;

    }
    public function homePage(){
        $featuredPopular=$this->viewAllService->getPopularCourses();
        $featuredTopRated=$this->viewAllService->getTopRatedCourses();

        return response()->json([

            'popular_courses' => CourseResource::collection($featuredPopular),
            'top_rated_courses' => CourseResource::collection($featuredTopRated),
        ]);
    }

}
