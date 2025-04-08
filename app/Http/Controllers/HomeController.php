<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Services\HomeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected HomeService $homeService;

    public function __construct(HomeService $homeService) {
        $this->homeService = $homeService;
    }

    public function homePage(Request $request) {
        $type = $request->query('type'); 

        if ($type === 'popular') {
            $courses = $this->homeService->getPopularCourses();
            return response()->json([
                'popular_courses' => CourseResource::collection($courses)
            ], 200);
        } elseif ($type === 'top') {
            $courses = $this->homeService->getTopRatedCourses();
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
