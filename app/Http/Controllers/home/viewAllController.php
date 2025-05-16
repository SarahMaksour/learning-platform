<?php

namespace App\Http\Controllers\home;

use Illuminate\Http\Request;
use App\Services\HomeService;
use App\Services\ViewAllService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;

class ViewAllController extends Controller
{
    //
    protected  $viewAllService;
    public function __construct(ViewAllService $viewAllService){
        $this->viewAllService=$viewAllService;

    }
      public function homePage(Request $request) {
        $type = $request->query('type'); 

        if ($type === 'popular') {
            $courses = $this->viewAllService->getPopularCourses();
            return response()->json([
                'popular_courses' => CourseResource::collection($courses)
            ], 200);
        } elseif ($type === 'top') {
            $courses = $this->viewAllService->getTopRatedCourses();
            return response()->json([
                'top_rated_courses' => CourseResource::collection($courses)
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid type parameter. Use ?type=popular or ?type=top'
            ], 400);
        }
    }

}
