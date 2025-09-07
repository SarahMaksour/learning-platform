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
use App\Services\SupabaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MyCourseService
{
    public function getMyCourse()
    {
        $user = Auth()->user();
        $user_id = $user->id;

        return Course::withCount('enrollments')
            ->withAvg('reviews', 'rating')
            ->with('instructor:id,name')
            ->where('user_id', $user_id)
            ->orderByDesc('enrollments_count')
            ->get(['id', 'title', 'price', 'image', 'user_id']);
    }


    /*public function addCourse(array $data)
    {

        return DB::transaction(function () use ($data) {
           // ====== رفع صورة الكورس ======
           $image = $data['image'] ?? null;
        if (!$image instanceof \Illuminate\Http\UploadedFile) {
            throw new \Exception("Course image must be a valid uploaded file");
        }

        $imageName = $this->generateFileName($data['title'], $image->getClientOriginalExtension());
        Storage::disk('supabase')->put($imageName, file_get_contents($image->getPathname()));

       $imageUrl = env('SUPABASE_URL') 
            . "/storage/v1/object/public/" 
            . env('SUPABASE_BUCKET') 
            . "/" . $imageName;

                 $course = Course::create([
                'user_id' => Arr::get($data, 'user_id'),
                'title'      => Arr::get($data, 'title'),
                'description' => Arr::get($data, 'description'),
                'price'      => Arr::get($data, 'price'),
                'image'      =>  $imageUrl,
            ]);

            foreach (Arr::get($data, 'videos', []) as $videoData) {
                $videoFile = $videoData['video'] ?? null;
                if (!$videoFile instanceof \Illuminate\Http\UploadedFile) {
                throw new \Exception("Video file is required for '{$videoData['title']}'");
            }

           $videoName = $this->generateFileName($videoData['title'], $videoFile->getClientOriginalExtension());
            Storage::disk('supabase')->put($videoName, file_get_contents($videoFile->getPathname()));

            $videoUrl = env('SUPABASE_URL') 
                . "/storage/v1/object/public/" 
                . env('SUPABASE_BUCKET') 
                . "/" . $videoName;
    // مدة الفيديو
            $getID3 = new \getID3;
            $fileInfo = $getID3->analyze($videoFile->getPathname());
            $durationSeconds = isset($fileInfo['playtime_seconds']) ? (int) round($fileInfo['playtime_seconds']) : 0;
      $video = Video::create([
                    'course_id'   => $course->id,
                    'title'       => Arr::get($videoData, 'title'),
                    'description' => Arr::get($videoData, 'description'),
                    'video_path'  => $videoUrl,
                    'duration'    => $durationSeconds,
                ]);

                $content = CourseContent::create([
                    'course_id'       => $course->id,
                    'contentable_id'  => $video->id,
                    'contentable_type' => Video::class,
                ]);

                if ($quizData = Arr::get($videoData, 'quiz')) {
                    $quiz = Quiz::create([
                        'course_id' => $course->id,
                        'content_id' => $content->id,
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
    }*/

    /**
     * Update an existing course with videos and quizzes
     */
public function addCourse(array $data)
{
    $supabase = new SupabaseService();

    return DB::transaction(function () use ($data, $supabase) {

        // ====== رفع صورة الكورس ======
        $image = $data['image'] ?? null;
        if (!$image instanceof \Illuminate\Http\UploadedFile) {
            throw new \Exception("Course image must be a valid uploaded file");
        }
       // توليد اسم فريد للصورة
$imageName = $this->generateFileName($data['title'], $image->getClientOriginalExtension());
$imageUpload = $supabase->uploadImage($image, $imageName); // هنا الاسم الفريد
$imageUrl = env('SUPABASE_URL') 
            . "/storage/v1/object/public/" 
            . env('SUPABASE_BUCKET') 
            . "/" . $imageName;

        // ====== إنشاء الكورس ======
        $course = Course::create([
            'user_id' => Arr::get($data, 'user_id'),
            'title' => Arr::get($data, 'title'),
            'description' => Arr::get($data, 'description'),
            'price' => Arr::get($data, 'price'),
            'image' => $imageUrl,
        ]);

        // ====== رفع الفيديوهات ======
        foreach (Arr::get($data, 'videos', []) as $videoData) {
            $videoFile = $videoData['video'] ?? null;
            if (!$videoFile instanceof \Illuminate\Http\UploadedFile) {
                throw new \Exception("Video file is required for '{$videoData['title']}'");
            }

          $videoName = $this->generateFileName($videoData['title'], $videoFile->getClientOriginalExtension());
$videoUpload = $supabase->uploadImage($videoFile, $videoName); // الاسم الفريد
$videoUrl = env('SUPABASE_URL') 
            . "/storage/v1/object/public/" 
            . env('SUPABASE_BUCKET') 
            . "/" . $videoName;

            // ====== حساب مدة الفيديو ======
            $getID3 = new \getID3;
            $fileInfo = $getID3->analyze($videoFile->getPathname());
            $durationSeconds = isset($fileInfo['playtime_seconds']) ? (int) round($fileInfo['playtime_seconds']) : 0;

            // ====== إنشاء الفيديو ======
            $video = Video::create([
                'course_id' => $course->id,
                'title' => Arr::get($videoData, 'title'),
                'description' => Arr::get($videoData, 'description'),
                'video_path' => $videoUrl,
                'duration' => $durationSeconds,
            ]);

            // ====== إضافة محتوى للكورس ======
            $content = CourseContent::create([
                'course_id' => $course->id,
                'contentable_id' => $video->id,
                'contentable_type' => Video::class,
            ]);

            // ====== إضافة Quiz إذا موجود ======
            if ($quizData = Arr::get($videoData, 'quiz')) {
                $quiz = Quiz::create([
                    'course_id' => $course->id,
                    'content_id' => $content->id,
                    'title' => Arr::get($quizData, 'title'),
                    'type' => 'lesson',
                ]);

                foreach (Arr::get($quizData, 'questions', []) as $q) {
                    Question::create([
                        'quiz_id' => $quiz->id,
                        'text' => Arr::get($q, 'text'),
                        'option' => json_encode(Arr::get($q, 'options', [])),
                        'correct_answer' => Arr::get($q, 'correct_answer'),
                    ]);
                }
            }
        }

        return ['message' => 'course added successfully'];
    });
}
    public function updateCourse(int $courseId, array $data)
    {
        $supabase = new \App\Services\SupabaseService();

        return DB::transaction(function () use ($courseId, $data) {

            $course = Course::findOrFail($courseId);

            // تحديث بيانات الكورس
            foreach (['title', 'description', 'price'] as $field) {
                if (Arr::has($data, $field)) {
                    $course->$field = $data[$field];
                }
            }

          // تحديث صورة الكورس إذا موجودة
        if ($image = Arr::get($data, 'image')) {
            $imageName = $this->generateFileName($course->title, $image->getClientOriginalExtension());
            
            // رفع الصورة على Supabase
            $supabase->uploadImage($image);

            // رابط الصورة العام
            $course->image = env('SUPABASE_URL') 
                            . "/storage/v1/object/public/" 
                            . env('SUPABASE_BUCKET') 
                            . "/" . $imageName;
        }

            $course->save();

            // معالجة الفيديوهات
            foreach (Arr::get($data, 'videos', []) as $videoData) {
                $video = null;

                // تعديل فيديو موجود
                if ($videoId = Arr::get($videoData, 'id')) {
                    $video = Video::findOrFail($videoId);
                } else {
                    // إضافة فيديو جديد
                    $video = new Video();
                    $video->course_id = $course->id;
                }

                foreach (['title', 'description'] as $field) {
                    if (Arr::has($videoData, $field)) {
                        $video->$field = $videoData[$field];
                    }
                }

                  // تحديث أو إضافة ملف الفيديو
            if ($videoFile = Arr::get($videoData, 'video')) {
                $videoName = $this->generateFileName($video->title, $videoFile->getClientOriginalExtension());
                
                // رفع الفيديو على Supabase
                $supabase->uploadImage($videoFile);

                // رابط الفيديو العام
                $video->video_path = env('SUPABASE_URL') 
                                   . "/storage/v1/object/public/" 
                                   . env('SUPABASE_BUCKET') 
                                   . "/" . $videoName;

            }

                $video->save();

                // ربط الفيديو بالمحتوى إذا جديد
                if (!CourseContent::where('contentable_id', $video->id)->exists()) {
                    CourseContent::create([
                        'course_id' => $course->id,
                        'contentable_id' => $video->id,
                        'contentable_type' => Video::class,
                    ]);
                }

                // معالجة الكويزات
                if ($quizData = Arr::get($videoData, 'quiz')) {
                    $quiz = null;

                    // تعديل كويز موجود
                    if ($quizId = Arr::get($quizData, 'id')) {
                        $quiz = Quiz::findOrFail($quizId);
                    } else { // إضافة كويز جديد
                        $quiz = new Quiz();
                        $quiz->course_id = $course->id;
                        $quiz->content_id = CourseContent::where('contentable_id', $video->id)->first()->id;
                        $quiz->type = 'lesson';
                    }

                    if (Arr::has($quizData, 'title')) {
                        $quiz->title = $quizData['title'];
                    }

                    $quiz->save();

                    // معالجة الأسئلة
                    foreach (Arr::get($quizData, 'questions', []) as $qData) {
                        $question = null;

                        // تعديل سؤال موجود
                        if ($qId = Arr::get($qData, 'id')) {
                            $question = Question::findOrFail($qId);
                        } else { // إضافة سؤال جديد
                            $question = new Question();
                            $question->quiz_id = $quiz->id;
                        }

                        if (Arr::has($qData, 'text')) {
                            $question->text = $qData['text'];
                        }

                        if (Arr::has($qData, 'options')) {
                            $question->option = json_encode($qData['options']);
                        }

                        if (Arr::has($qData, 'correct_answer')) {
                            $question->correct_answer = $qData['correct_answer'];
                        }

                        $question->save();
                    }
                }
            }

            return ['message' => 'course updated successfully'];
        });
    }
    private function generateFileName($title, $extension)
    {
        $slug = Str::slug($title); // laravel-basics
        $timestamp = now()->format('Ymd_His');
        return "{$slug}_{$timestamp}.{$extension}";
    }
}
