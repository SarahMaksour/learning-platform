<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'contents', 'reviews'])
            ->when($request->title, function($query, $title) {
                $query->where('title', 'like', "%$title%");
            })
            ->when($request->teacher, function($query, $teacher) {
                $query->whereHas('user', function($q) use ($teacher){
                    $q->where('name', 'like', "%$teacher%");
                });
            })
            ->paginate($request->entries_number ?? 10);

        return view('new-dashboard.courses.index', compact('courses'));
    }
     public function show(Course $course)
    {
        // جلب الدروس المرتبطة بالكورس
        $lessons = $course->contents()->with('contentable')->get();

        // جلب الطلاب المشتركين بالكورس
        $students = $course->enrollments()->with('student')->get();

        // جلب التقييمات للكورس
        $reviews = $course->reviews()->with('user')->get();

        // متوسط التقييم
        $averageRating = $course->reviews()->avg('rating');

        return view('new-dashboard.courses.view', [
            'course' => $course,
            'lessons' => $lessons,
            'students' => $students,
            'reviews' => $reviews,
            'averageRating' => $averageRating
        ]);
    }
}
