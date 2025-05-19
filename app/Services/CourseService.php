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
}
