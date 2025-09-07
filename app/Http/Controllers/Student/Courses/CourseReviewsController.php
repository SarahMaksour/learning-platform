<?php

namespace App\Http\Controllers\Student\Courses;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\CourseReview;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CourseReviewsController extends Controller
{
    protected $reviewService;
    public function __construct(CourseReview $reviewService){
        $this->reviewService =$reviewService;
    }
    public function CourseReviews($course_id){
        
    $data=$this->reviewService->getCourseReview($course_id);
    return response()->json([
            'average_rating' => $data['averageRating'],
            'total_reviews' => $data['totalReviews'],
            'ratings' => $data['ratings'],
            'comments' => $data['comments'],

    ],201);
    }

   
 /* public function store(Request $request, $course_id)
{
    $user = auth()->user();
    $course = Course::findOrFail($course_id);

    // جلب كل محتويات الكورس من نوع فيديو
    $videoContents = $course->contents()->where('contentable_type', \App\Models\Video::class)->get();

    // التحقق من اجتياز كل الدروس
    $allVideosCompleted = $course->contents
    ->where('contentable_type', \App\Models\Video::class)
    ->every(fn($content) => $content->isPassedByUser($user));

    if (!$allVideosCompleted) {
        return response()->json([
            'message' => 'You cannot rate this course because you have not completed the course yet.' ], 201);
    }

    // جلب آخر محتوى فيديو (الدرس الأخير)
    $lastContent = $videoContents->last();

    // جلب الكويز المرتبط بالدرس الأخير عبر العلاقة المورف
    $lastQuiz = $lastContent->quiz;

    if ($lastQuiz) {
        $attempt = $lastQuiz->placementAttempts()
            ->where('user_id', $user->id)
            ->where('status', 'passed')
            ->first();

        if (!$attempt) {
            return response()->json([
                'message' => 'لا يمكنك تقييم هذا الكورس لأنك لم تجتاز اختبار الدرس الأخير'
            ], 403);
        }
    }

    // التحقق من صحة البيانات
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // حفظ أو تحديث الريفيو
    Review::updateOrCreate(
        [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ],
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return response()->json([
        'message' => 'Your rating has been submitted successfully.'  ],201);
}
*/
public function store(Request $request, $course_id)
{
    $user = auth()->user();
    $course = Course::findOrFail($course_id);

    // جلب كل محتويات الكورس من نوع فيديو
    $videoContents = $course->contents()->where('contentable_type', \App\Models\Video::class)->get();

    // التحقق من اجتياز كل الدروس وكل الكويزات المرتبطة بها
    $allVideosCompleted = $videoContents->every(function($content) use ($user) {
        $videoPassed = $content->isPassedByUser($user);

        if ($quiz = $content->quiz) {
            $quizPassed = $quiz->placementAttempts()
                ->where('user_id', $user->id)
                ->where('status', 'passed')
                ->exists();
            return $videoPassed && $quizPassed;
        }

        return $videoPassed;
    });

    if (!$allVideosCompleted) {
        return response()->json([
            'message' => 'You cannot rate this course because you have not completed all lessons and quizzes.'
        ], 403);
    }

    // التحقق من صحة البيانات
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // حفظ أو تحديث الريفيو
    Review::updateOrCreate(
        [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ],
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]
    );

    return response()->json([
        'message' => 'Your rating has been submitted successfully.'
    ], 201);
}

}
