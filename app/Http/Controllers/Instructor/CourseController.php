<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Course;
use Illuminate\Http\Request;
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
                                'options' => json_decode($q->option),
                                'correct_answer' => $q->correct_answer,
                            ];
                        })
                    ] : null
                ];
            })
        ]
    ], 200);
}

 public function updateCourse(courseRequest $request, $id)
{
    // نأخذ البيانات validated
    $data = $request->validated();

    // نضيف معرف المستخدم الحالي
    $data['user_id'] = auth()->id();

    // نستدعي الخدمة
    $response = $this->myCourseService->updateCourse($id, $data);

    // نرجع response بصيغة JSON
    return response()->json($response, 200);
}
public function testUpload(Request $request) {
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('test', 'public');
        return ['path' => $path, 'url' => Storage::url($path)];
    }
    return ['error' => 'no file uploaded'];
}
}
