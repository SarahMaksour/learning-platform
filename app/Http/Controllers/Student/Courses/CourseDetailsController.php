<?php

namespace App\Http\Controllers\Student\Courses;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\CourseService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Services\LessonCourseService;
use App\Services\CoursePurchaseService;
use App\Http\Resources\CourseDetailResource;
use App\Http\Resources\LessonStatusResource;

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

    $user = auth()->user(); // ممكن يكون null
    $course = Course::findOrFail($id);

    $is_paid = $user ? $this->courseService->isUserPaid($user, $course) : false;
    $courseDetail->is_paid = $is_paid;

    return response()->json([
        'courseDetail' => new CourseDetailResource($courseDetail)
    ], 201);
}

public function getCourseLesson($course_id)
{
    // نجيب الـ user من Sanctum إذا مسجل دخول
    $user = auth('sanctum')->user();

   // إذا ما فيه user، نعمل guest object
    if (!$user) {
        $user = (object)[
            'id' => 0,
            'name' => 'Guest',
            'email' => 'guest@example.com'
        ];
    }

    $lessons = $this->courseService->getCourseLessonsWithStatus($course_id, $user);
    return LessonStatusResource::collection($lessons);
}


    public function PayTheCourse( $id){
//$course_id=$course->id;
        $message=$this->courseService->enrollUserInCourseWithPayment($id);
        return $message;
    }
}
