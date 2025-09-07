<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Quiz;
use App\Models\Video;
use App\Models\Course;
use App\Models\Question;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Services\SupabaseService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Storage;
use App\Services\Instructor\MyCourseService;
use App\Http\Requests\Instructor\courseRequest;

class CourseController extends Controller
{
    protected $myCourseService;
    public function __construct(MyCourseService $myCourseService){
        $this->myCourseService=$myCourseService;
    }
    public function getMyCourse(){
$courses=$this->myCourseService->getMyCourse();
return response()->json([
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    public function addCourse(courseRequest $request){
         $data = $request->validated();
          $data['user_id'] = auth()->id();
          // مرر كل الملفات للـ service
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image');
    }

  if ($request->hasFile('videos')) {
    $data['videos'] = $request->file('videos'); // array of UploadedFile
}


$response=$this->myCourseService->addCourse($data);
return response()->json(
    $response
,201);
    }
public function getCourse($id)
{
    $course = Course::with(['videos.quiz.questions'])->findOrFail($id);

    return response()->json([
        'course' => [
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'price' => $course->price,
            'image' => $course->image,
            'videos' => $course->videos->map(function($video){
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'video_path' => $video->video_path,
                    'quiz' => $video->quiz ? [
                        'id' => $video->quiz->id,
                        'title' => $video->quiz->title,
                        'questions' => $video->quiz->questions->map(function($q){
                            return [
                                'id' => $q->id,
                                'text' => $q->text,
'options' => is_string($q->option) ? json_decode($q->option, true) : $q->option,                                'correct_answer' => $q->correct_answer,
                            ];
                        })
                    ] : null
                ];
            })
        ]
    ], 200);
}

/*public function updateCourse(courseRequest $request, $id)
{
    // نأخذ البيانات validated
    $data = $request->validated();

    // نضيف معرف المستخدم الحالي
    $data['user_id'] = auth()->id();

    // نستدعي الخدمة
    $response = $this->myCourseService->updateCourse($id, $data);

    // نرجع response بصيغة JSON
    return response()->json($response, 200);
}*/
public function updateCourse(int $courseId, array $data)
{
    $supabase = new SupabaseService();

    return DB::transaction(function () use ($courseId, $data, $supabase) {

        $course = Course::findOrFail($courseId);

        // تحديث بيانات الكورس
        foreach (['title', 'description', 'price'] as $field) {
            if (Arr::has($data, $field)) {
                $course->$field = $data[$field];
            }
        }

        // تحديث صورة الكورس إذا موجودة
        if ($image = Arr::get($data, 'image')) {
            if ($course->image) {
                // استخرج اسم الملف القديم من الرابط
                $oldImageName = basename(parse_url($course->image, PHP_URL_PATH));

                // استبدل الملف بنفس الاسم
                $supabase->uploadImage($image, $oldImageName);

                $imageName = $oldImageName;
            } else {
                // إذا جديد → اعمل اسم جديد
                $imageName = $this->generateFileName($course->title, $image->getClientOriginalExtension());
                $supabase->uploadImage($image, $imageName);
            }

            // حدّث الرابط
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
                if ($video->video_path) {
                    // استخرج اسم الملف القديم
                    $oldVideoName = basename(parse_url($video->video_path, PHP_URL_PATH));

                    // استبدل الملف بنفس الاسم
                    $supabase->uploadImage($videoFile, $oldVideoName);

                    $videoName = $oldVideoName;
                } else {
                    // إذا جديد → اعمل اسم جديد
                    $videoName = $this->generateFileName($video->title, $videoFile->getClientOriginalExtension());
                    $supabase->uploadImage($videoFile, $videoName);
                }

                // حدّث الرابط
                $video->video_path = env('SUPABASE_URL')
                                   . "/storage/v1/object/public/"
                                   . env('SUPABASE_BUCKET')
                                   . "/" . $videoName;
            }

            $video->save();

            // ربط الفيديو بالمحتوى إذا جديد
            if (!CourseContent::where('contentable_id', $video->id)->exists()) {
                CourseContent::create([
                    'course_id'        => $course->id,
                    'contentable_id'   => $video->id,
                    'contentable_type' => Video::class,
                ]);
            }

            // معالجة الكويزات
            if ($quizData = Arr::get($videoData, 'quiz')) {
                $quiz = null;

                // تعديل كويز موجود
                if ($quizId = Arr::get($quizData, 'id')) {
                    $quiz = Quiz::findOrFail($quizId);
                } else {
                    // إضافة كويز جديد
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
                    } else {
                        // إضافة سؤال جديد
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

public function testUpload(Request $request) {
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('test', 'public');
        return ['path' => $path, 'url' => Storage::url($path)];
    }
    return ['error' => 'no file uploaded'];
}

public function deleteCourse($id)
{
    $course = Course::findOrFail($id);
    $course->delete();

    return response()->json([
        'message' => 'Course deleted successfully'
    ], 200);
}
public function deleteLesson($courseId, $contentId)
{
    $user = auth()->user();

    // تأكد إنو الكورس ملك للمستخدم
    $course = Course::where('id', $courseId)
        ->where('user_id', $user->id)
        ->firstOrFail();

    $content = $course->contents()->with('contentable')->findOrFail($contentId);

    // إذا كان المحتوى فيديو
    if ($content->contentable && $content->contentable_type === \App\Models\Video::class) {
        $video = $content->contentable;

        // حذف الفيديو من التخزين إذا موجود
        if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
            Storage::disk('public')->delete($video->video_path);
        }

        $video->delete();
    }

    // إذا عنده كويز مربوط
    $quiz = \App\Models\Quiz::where('content_id', $content->id)->first();
    if ($quiz) {
        // حذف الأسئلة التابعة
        $quiz->questions()->delete();
        $quiz->delete();
    }

    // حذف الـ CourseContent نفسه
    $content->delete();

    return response()->json(['message' => 'Lesson and related quiz deleted successfully'], 200);
}


}
