<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FilterService;
use App\Http\Resources\CourseResource;

class FilterController extends Controller
{
    protected $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

   public function filter(Request $request)
    {
        // استدعاء الفلترة من الـ Service
        $courses = $this->filterService->filterCourses($request);

        // تحويل النتائج لريسورس ثم إرجاعها
        return CourseResource::collection($courses);
    }
}