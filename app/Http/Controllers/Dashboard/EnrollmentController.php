<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Enrolment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnrollmentController extends Controller
{
 /* public function index(Request $request)
    {
        $query = Enrolment::with(['student', 'course']);

        if ($request->student) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->student}%");
            });
        }

        if ($request->course) {
            $query->whereHas('course', function($q) use ($request) {
                $q->where('title', 'like', "%{$request->course}%");
            });
        }

        $enrollments = $query->paginate(10);

        return view('new-dashboard.enrollments.index', compact('enrollments'));
    }*/
public function index()
    {
        // جلب التسجيلات مع الكورس والطالب
        $enrollments = Enrolment::with(['student', 'course', 'course.contents'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // تجهيز نسبة التقدم لكل تسجيل
        foreach ($enrollments as $enrollment) {
            $totalContents = $enrollment->course->contents()->count();
            $completed = $enrollment->course->contents()
                ->whereHas('studentProgress', fn($q) => $q->where('user_id', $enrollment->user_id)->where('is_passed', true))
                ->count();

            $enrollment->progressPercentage = $totalContents > 0 ? round(($completed / $totalContents) * 100) : 0;
        }
        return view('new-dashboard.enrollments.index', compact('enrollments'));
    }
    public function show(Enrolment $enrollment)
{
    $course = $enrollment->course;
    $user = $enrollment->student;

    // محتوى الكورس مع حالة تقدم الطالب لكل درس
    $contents = $course->contents()->with(['studentProgress' => function($q) use ($user) {
        $q->where('user_id', $user->id);
    }])->get();

    // حساب الملخص
    $totalLessons = $contents->count();
    $completedLessons = $contents->filter(function($c) {
        return optional($c->studentProgress->first())->is_passed;
    })->count();
    $progressPercentage = $totalLessons ? round(($completedLessons / $totalLessons) * 100) : 0;

    return view('new-dashboard.enrollments.show', compact('enrollment', 'course', 'user', 'contents', 'totalLessons', 'completedLessons', 'progressPercentage'));
}

}
