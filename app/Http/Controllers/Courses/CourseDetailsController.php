<?php

namespace App\Http\Controllers\Courses;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDetailResource;

class CourseDetailsController extends Controller
{
    protected $courseService;
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function getAboutCourse($id)
    {
        $courseDetail = $this->courseService->getAboutCourse($id);
        return response()->json([
            'courseDetail' => new CourseDetailResource($courseDetail)
        ], 201);
    }
}
