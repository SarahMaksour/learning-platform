<?php

namespace App\Services\Instructor;

use App\Models\Quiz;
use App\Models\Video;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Support\Arr;
use GuzzleHttp\Psr7\Request;
use App\Models\CourseContent;
use Illuminate\Support\Facades\DB;

class MyCourseService{
    public function getMyCourse(){
        $user=Auth()->user();
        $user_id=$user->id;
        
         return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('instructor:id,name')
            ->where('user_id',$user_id)
            ->orderByDesc('enrollments_count')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }

    public function addCourse(array $data){
 return DB::transaction(function () use ($data) {
            // 1. تخزين صورة الكورس
        //    $imagePath = Arr::get($data, 'image')->store('/images', 'public');

            // 2. إنشاء الكورس
            $course = Course::create([
                'user_id'    => Arr::get($data, 'user_id'),
                'title'      => Arr::get($data, 'title'),
                'description'=> Arr::get($data, 'description'),
                'price'      => Arr::get($data, 'price'),
               // 'image'      => $imagePath,
            ]);

            // 3. تكرار الفيديوهات (الدروس)
         /*   foreach (Arr::get($data, 'videos', []) as $videoData) {
                // 3.1 تخزين الفيديو
                $videoPath = Arr::get($videoData, 'video')->store('/videos', 'public');

                // 3.2 إنشاء الفيديو
                $video = Video::create([
                    'course_id'   => $course->id,
                    'title'       => Arr::get($videoData, 'title'),
                    'video_path'  => $videoPath,
                    'duration'    => Arr::get($videoData, 'duration'),
                ]);

                // 3.3 إنشاء المحتوى المرتبط بالفيديو
                $content = CourseContent::create([
                    'course_id'       => $course->id,
                    'contentable_id'  => $video->id,
                    'contentable_type'=> Video::class,
                ]);

                // 3.4 إنشاء الكويز (إن وُجد)
                if ($quizData = Arr::get($videoData, 'quiz')) {
                    $quiz = Quiz::create([
                        'course_id' => $course->id,
                        'content_id'=> $content->id,
                        'title'     => Arr::get($quizData, 'title'),
                        'type'      => 'lesson',
                    ]);

                    // 3.5 إنشاء الأسئلة
                    foreach (Arr::get($quizData, 'questions', []) as $q) {
                        Question::create([
                            'quiz_id'        => $quiz->id,
                            'text'           => Arr::get($q, 'text'),
                            'option'         => json_encode(Arr::get($q, 'options', [])),
                            'correct_answer' => Arr::get($q, 'correct_answer'),
                        ]);
                    }
                }
            }*/
      
            return ['message' => 'course add successfully'];
        });
    }
}