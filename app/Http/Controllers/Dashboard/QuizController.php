<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuizController extends Controller
{
     public function index()
    {
        // جلب كل الكويزات مع الطلاب الذين أتموا الكويز
$quizzes = Quiz::withCount(['placementAttempts as students_completed_count' => function($query){
    $query->where('status', 'completed');
}])->get();

        // حساب الإحصائيات العامة
        $totalQuizzes = $quizzes->count();
        $totalCompletedStudents = $quizzes->sum('students_completed_count');

        return view('new-dashboard.quizzes.index', compact(
            'quizzes',
            'totalQuizzes',
            'totalCompletedStudents'
        ));
    }
}
