<?php

namespace App\Services\Instructor;

use App\Models\Quiz;
use App\Models\Video;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
            $image = $data['image'];
            $imageName = $this->generateFileName($data['title'], $image->getClientOriginalExtension());
            $image->move(public_path('images'), $imageName);
            $imagePath = 'images/' . $imageName;
            
            $course = Course::create([
                'user_id' => Arr::get($data, 'user_id'),
                'title'      => Arr::get($data, 'title'),
                'description'=> Arr::get($data, 'description'),
                'price'      => Arr::get($data, 'price'),
                'image'      => $imagePath,
            ]);

            foreach (Arr::get($data, 'videos', []) as $videoData) {
                 $videoFile = $videoData['video'];
                $videoName = $this->generateFileName($videoData['title'], $videoFile->getClientOriginalExtension());
                $videoFile->move(public_path('videos'), $videoName);
                $videoPath = 'videos/' . $videoName;
                $video = Video::create([
                    'course_id'   => $course->id,
                    'title'       => Arr::get($videoData, 'title'),
                    'video_path'  => $videoPath,
                    'duration'    => Arr::get($videoData, 'duration'),
                ]);

                $content = CourseContent::create([
                    'course_id'       => $course->id,
                    'contentable_id'  => $video->id,
                    'contentable_type'=> Video::class,
                ]);

                if ($quizData = Arr::get($videoData, 'quiz')) {
                    $quiz = Quiz::create([
                        'course_id' => $course->id,
                        'content_id'=> $content->id,
                        'title'     => Arr::get($quizData, 'title'),
                        'type'      => 'lesson',
                    ]);

                    foreach (Arr::get($quizData, 'questions', []) as $q) {
                        Question::create([
                            'quiz_id'        => $quiz->id,
                            'text'           => Arr::get($q, 'text'),
                            'option'         => json_encode(Arr::get($q, 'options', [])),
                            'correct_answer' => Arr::get($q, 'correct_answer'),
                        ]);
                    }
                }
            }
      
            return ['message' => 'course add successfully'];
        });
    }
     private function generateFileName($title, $extension)
    {
        $slug = Str::slug($title); // laravel-basics
        $timestamp = now()->format('Ymd_His');
        return "{$slug}_{$timestamp}.{$extension}";
    }
}