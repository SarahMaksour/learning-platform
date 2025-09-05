<?php

namespace App\Http\Controllers\Student\myProfile;

use App\Models\Video;
use App\Models\Course;
use Illuminate\Http\Request;
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
        // مجموع الفيديوهات في الكورس
        $totalVideos = $course->contents->where('contentable_type', Video::class)->count();

        // عدد الفيديوهات اللي خلصها الطالب
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
            'progress' => $progress, // النسبة بالمئة
        ];
    });

    return response()->json($response, 200);
}

}
