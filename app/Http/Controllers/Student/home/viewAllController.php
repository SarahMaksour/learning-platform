<?php

namespace App\Http\Controllers\Student\home;

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

    } public function homePage(Request $request)
    {
        $type = $request->query('type');

        if ($type === 'popular') {
            $courses = $this->viewAllService->getPopularCourses();
        } elseif ($type === 'top') {
            $courses = $this->viewAllService->getTopRatedCourses();
        } else {
            return response()->json([
                'message' => 'Invalid type parameter. Use ?type=popular or ?type=top'
            ], 400);
        }

        return response()->json([
            'data' => CourseResource::collection($courses)
        ], 200);
    }

}
