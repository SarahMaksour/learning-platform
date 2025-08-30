<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Enrolment;

class DashboardController extends Controller
{
    public function index()
{
    $counts = [
        'courses' => Course::count(),
        'students' => User::where('role', 'student')->count(),
        'instructors' => User::where('role', 'instructor')->count(),
        'sales' => Transaction::sum('amount'),
    ];

    // بيانات الرسم البياني لكل شهر (مثال)
   $year = date('Y');
$monthlyLabels = collect(range(1,12))->map(fn($m) => date('F', mktime(0,0,0,$m,1)) );
$monthlyStudents = [];
$monthlySales = [];

foreach(range(1,12) as $month){
    $monthlyStudents[] = User::where('role','student')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->count();

    $monthlySales[] = Transaction::whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->sum('amount');
}


    // أحدث التسجيلات
    $recentEnrollments = Enrolment::with('student','course.contents')
        ->latest()->limit(5)->get();

    // أفضل الكورسات
    $topCourses = Course::withCount('enrollments')->orderBy('enrollments_count','desc')->take(5)->get();
$enrollments = Enrolment::with([
    'student.studentLessonProgress', 
    'course.contents'
])->paginate(10);
// احسب النسبة لكل تسجيل
        foreach ($enrollments as $enroll) {
            $totalContents = $enroll->course->contents->count();

            $completedContents = $enroll->student->studentLessonProgress
                ->whereIn('content_id', $enroll->course->contents->pluck('id'))
                ->where('is_passed', true)
                ->count();

            $enroll->progress = $totalContents > 0 ? ($completedContents / $totalContents) * 100 : 0;
        }
    return view('new-dashboard.dashboard.dashboard', compact(
        'counts','monthlyLabels','monthlyStudents','monthlySales','recentEnrollments','topCourses',
        'enrollments'
    ));
}

}
