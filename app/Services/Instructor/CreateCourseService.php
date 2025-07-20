<?php

namespace App\Services\Instructor;

use App\Models\Quiz;
use App\Models\Video;
use App\Models\Course;
use App\Models\Question;
use App\Models\CourseContent;
use Illuminate\Support\Facades\DB;

class CreateCourseService
{
 public function getCourseWithLesson(array $data){

return DB::transaction(function() use ($data){
 $imagePath = $data['image']->store('/images', 'public');
 $course=Course::create(
    [
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
             'image' => $imagePath
    ]);
      foreach ($data['videos'] ?? [] as $videoData) {
            $videoPath = $videoData['video']->store('/videos', 'public');
            $video = Video::create([
                'course_id' => $course->id,
                'title' => $videoData['title'],
                'video_path' => $videoPath,
                'duration' => $videoData['duration'] ?? null,
            ]);
                   $content=CourseContent::create([
                'course_id' => $course->id,
                'contentable_id' => $video->id,
                'contentable_type' => Video::class,
            ]);
        

         if (isset($videoData['quiz'])) {
          $quizData = $videoData['quiz'];
            $quiz = Quiz::create([
                'course_id' => $course->id,
                'content_id' => $content->id,
                'title' => $quizData['title'],
                'type' => 'lesson',
            ]);

            foreach ($quizData['questions'] ?? [] as $q) {
                Question::create([
                    'quiz_id' => $quiz->id,
                    'text' => $q['text'],
                    'option' => json_encode($q['options']),
                    'correct_answer' => $q['correct_answer'],
                ]);
            }
        }
    }
        return $course;
});

 }
}