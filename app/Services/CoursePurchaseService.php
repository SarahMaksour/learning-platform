<?php

namespace App\Services;

use App\Models\Course;
use App\Models\PlacementAttempt;
use App\Models\Quiz;
use Illuminate\Validation\Rules\Exists;

class CoursePurchaseService
{
    public function isUserPaid($user, $course): bool
    {
        return $user->enrollments()->where('course_id', $course->id)->exists();
    }

    public function isPassedPlacement($user, $course)
    {
        //If the course is not part of a series of courses
        if (!$course->parent_course_id) {
            return true;
        }
        //Check for a completed attempt
         return PlacementAttempt::where([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'completed',
        ])->exists();

    }

    //Creates a test attempt if it is needed and has not yet been created.

     public function ensurePlacementAttemptExists($user, $course)
    {
        if (!$course->parent_course_id) 
            return;

        $alreadyPassed = $this->isPassedPlacement($user, $course);
        if ($alreadyPassed) 
            return;

        $quiz = Quiz::where('course_id', $course->parent_course_id)->first();
        if ($quiz) {
            PlacementAttempt::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'quiz_id' => $quiz->id,
            ], [
                'status' => 'in-progress',
                'score' => 0,
            ]);
        }
    }
 
}
