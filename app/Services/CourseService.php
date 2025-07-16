<?php
namespace App\Services;


use App\Models\Course;
use App\Models\Wallet;
use App\Models\Enrolment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    public function getAboutCourse($id){
        return Course::withCount(['enrollments','reviews'])
        ->withAvg('reviews','rating')
        ->with('instructor.UserDetail','contents.contentable')
        ->findOrFail($id);

    }
       public function isUserPaid($user, $course): bool
    {
        return $user->enrollments()->where('course_id', $course->id)->exists();
    }
    public function enrollUserInCourseWithPayment($course_id){
        $user=Auth()->user();
        $course=Course::findOrFail($course_id);
        $instructor=$course->instructor;
        $studentWallet=Wallet::where('user_id',$user->id)->lockForUpdate()->first();
         $instructorWallet=Wallet::where('instructor_id',$instructor->id)->lockForUpdate()->first();
       $price=$course->price;
        if (!$studentWallet || $studentWallet->balance < $price) {
        return response()->json([
            'message' => 'your balance is not enough',
        ], 400);
    }
       DB::transaction(function () use ($studentWallet,  $instructorWallet, $price, $user, $course) {
        // خصم من الطالب
        $studentWallet->balance -= $price;
        $studentWallet->save();

        // إضافة للمدرّس
         $instructorWallet->balance += $price;
        $instructorWallet->save();

        // تسجيل الطالب
        Enrolment::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    });

    return response()->json([
        'message' => 'enrolment successfully',
    ]);
}
}