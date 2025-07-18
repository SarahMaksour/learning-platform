<?php

namespace App\Http\Controllers\Student\home;

use Illuminate\Http\Request;
use App\Services\FilterService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\FilterCourseResource;

class FilterController extends Controller
{
 protected  $filterService;
    public function __construct(FilterService $filterService){
        $this->filterService=$filterService;
    }

    public function filterCourses(Request $request)
    {
        $courses = $this->filterService->filterCourses($request);

        return CourseResource::collection($courses);
    }
    
    
}
