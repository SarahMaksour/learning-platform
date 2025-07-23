<?php

namespace App\Http\Controllers\Student\Courses;

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
    $user = Auth()->user();
    $course = Course::findOrFail($id);
    $isPaid = $this->isUserPaid($user, $course);
     $courseDetail->isPaid = $isPaid;
        return response()->json([
            'courseDetail' => new CourseDetailResource($courseDetail)
        ], 201);
    
    }
public function getCourseLesson($course_id)
{
    $user = Auth()->user();
    $lessons = $this->courseService->getCourseLessonsWithStatus($course_id, $user);
    return LessonStatusResource::collection($lessons);
}


    public function PayTheCourse( $id){
//$course_id=$course->id;
        $message=$this->courseService->enrollUserInCourseWithPayment($id);
        return $message;
    }
}
