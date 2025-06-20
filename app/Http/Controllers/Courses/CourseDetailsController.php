<?php

namespace App\Http\Controllers\Courses;

use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDetailResource;
use App\Models\Course;
use App\Services\CoursePurchaseService;

class CourseDetailsController extends Controller
{
    protected $courseService, $coursePurchaseService;
    public function __construct(CourseService $courseService, CoursePurchaseService $coursePurchaseService)
    {
        $this->courseService = $courseService;
        $this->coursePurchaseService = $coursePurchaseService;
    }
    public function getAboutCourse($id)
    {
       dd($this->courseService);
        $courseDetail = $this->courseService->getAboutCourse($id);
        return response()->json([
            'courseDetail' => new CourseDetailResource($courseDetail)
        ], 201);
    }

    public function checkCourseStatus($id)
    {
        $user = auth()->user();
        $course = Course::findOrFail($id);
        $this->coursePurchaseService->ensurePlacementAttemptExists($user, $course);
        return response()->json([
            'isPaid' => $this->coursePurchaseService->isUserPaid($user, $course),
            'isPassedPlacement' => $this->coursePurchaseService->isPassedPlacement($user, $course)
        ]);
    }
}
