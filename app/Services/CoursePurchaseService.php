<?php
namespace App\Services;

use App\Models\Course;
use App\Models\PlacementAttempt;
use App\Models\Quiz;
use Illuminate\Validation\Rules\Exists;

class CoursePurchaseService{
    public function CanUserBuy($user,$course){

    if($course->parent_course_id){
        $passedQuiz=PlacementAttempt::where([
            'user_id'=>$user->id,
            'course_id'=>$course->id,
            'status'=>'completed',
        ])->exists();
    }

    if(!$passedQuiz){

        $quiz=Quiz::where('course_id',$course->parent_course_id)->first();

        $newAttempt=PlacementAttempt::create([
            'user_id'=>$user->id,
            'course_id'=>$course->id,
            'quiz_id'=>$quiz->id,
            'status'=>'in-progress',
             'score' => 0,
        ]);
    }
    }
}
