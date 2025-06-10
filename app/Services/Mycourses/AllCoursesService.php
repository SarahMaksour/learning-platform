<?php

use App\Models\Course;
use App\Models\Enrolment;
use Illuminate\Http\Request;
use App\Services\CourseService;

class AllCoursesService{
 public function getallmycourses(Request $request){
    $studentId=auth()->id();
    if(!$studentId)
    {
        return response()->json(['error'=> 'Unauthorized'],401);
    }
    $enrollments=Enrolment::with(
        [
         'course.instructor ' ,
         'course.quiz'
        ] 
        )
        ->where('user_id',$studentId)
        ->get();
    }

}