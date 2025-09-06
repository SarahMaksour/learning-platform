<?php

namespace App\Http\Controllers\Student\myProfile;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class myProfileController extends Controller
{
public function show(){
    return response()->json([
        'user_name'=>Auth()->user()->name,
        'email'=>Auth()->user()->email
    ]);
}
public function myEnrolledCourses()
{
    $user = auth()->user();

    $courses = Course::whereHas('enrollments', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })->with(['instructor', 'contents.contentable'])->get();

    $response = $courses->map(function($course) use ($user) {
        $totalVideos = $course->contents->where('contentable_type', Video::class)->count();
        $completedVideos = $course->contents
            ->where('contentable_type', Video::class)
            ->filter(fn($content) => $content->studentProgress()
                ->where('user_id', $user->id)
                ->where('is_passed', true)
                ->exists()
            )->count();

        $progress = $totalVideos ? round($completedVideos / $totalVideos * 100) : 0;

        return [
            'id' => $course->id,
            'title' => $course->title,
            'image' => $course->image,
            'instructor' => $course->instructor->name ?? null,
            'progress' => $progress,
        ];
    });

    // هنا نرجع الـ array داخل object مع مفتاح "courses"
    return response()->json([
        'courses' => $response
    ], 200);
}


public function myFullyCompletedCourses()
{
    $user = auth()->user();

    $courses = Course::whereHas('enrollments', function($q) use ($user) {
        $q->where('user_id', $user->id);
    })
    ->with(['instructor', 'contents.contentable'])
    ->get()
    ->filter(function ($course) use ($user) {
        // كل الفيديوهات لازم يكون الطالب مخلصها
        $allVideosCompleted = $course->contents
            ->where('contentable_type', \App\Models\Video::class)
            ->every(fn($content) => $content->isPassedByUser($user));

        // كل الكويزات لازم الطالب نجح فيها
        $allQuizzesPassed = $course->contents
            ->pluck('quiz')
            ->filter()
            ->every(fn($quiz) => $quiz->placementAttempts()
                ->where('user_id', $user->id)
                ->where('score', '>=', 50)
                ->exists()
            );

        return $allVideosCompleted && $allQuizzesPassed;
    })
    ->values();

    return response()->json(['courses' => $courses], 200);
}

public function edit()
    {
        $user = Auth::user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], 200);
    }

      public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق من صحة البيانات
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        // تحديث البيانات
        $user->update($validated);

        return response()->json([
            'message' => 'تم تحديث بياناتك بنجاح',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }
}
