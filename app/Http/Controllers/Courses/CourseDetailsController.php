<?php

namespace App\Http\Controllers\Courses;

use App\Services\LessonCourseService;
use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseDetailResource;
use App\Http\Resources\LessonStatusResource;
use App\Models\Course;
use App\Services\CoursePurchaseService;

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


    public function getCourseLesson($course_id)
    {
        $user = Auth()->user();
        $course = Course::with('contents')->findOrFail($course_id);
        $isPaid = $this->courseService->isUserPaid($user, $course);
        $lessons = $course->contents->sortBy('id')->values();
        $unlock = false;
        foreach ($lessons as $index => $lesson) {
            $lesson->is_paid = $isPaid;
            $lesson->is_previous_lesson_passed = false;

            if ($isPaid) {

                if ($index === 0) {
                    $unlock = true;
                } else {
                    $previousLesson = $lessons[$index - 1];
                    $unlock = $previousLesson->isPassedByUser($user);
                }

                $lesson->is_previous_lesson_passed = $unlock;
            }
            $lesson->videoNum=$index+1;
        }
        return LessonStatusResource::collection($lessons);
    }
}
