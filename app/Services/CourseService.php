<?php
namespace App\Services;


use App\Models\Course;

class CourseService
{
    public function getAboutCourse($id){
        return Course::withCount(['enrollments','reviews'])
        ->withAvg('reviews','rating')
        ->with('instructor.UserDetail','contents.contentable')
        ->findOrFail($id);

    }
       public function isUserPaid($user, $course): bool
    {
        return $user->enrollments()->where('course_id', $course->id)->exists();
    }
}
