<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Models\CourseContent;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
 

    public function index()
    {
    $lessons = CourseContent::with('contentable', 'course')->paginate(10);
        return view('new-dashboard.lesson.index', compact('lessons'));
    }
    public function show(CourseContent $lesson)
{
    // جلب المحتوى المورفي المرتبط
    $content = $lesson->contentable;

    return view('new-dashboard.lesson.view', compact('lesson', 'content'));
}
}
