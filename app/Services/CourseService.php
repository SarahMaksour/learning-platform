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
    if (!$user) {
        return false; // الزائر لم يشترِ الكورس
    }

    return $user->enrollments()->where('course_id', $course->id)->exists();
}
    public function enrollUserInCourseWithPayment($course_id){
        $user=Auth()->user();
        $course=Course::findOrFail($course_id);
        $instructor=$course->instructor;
       $studentWallet = Wallet::firstOrCreate(['user_id' => $user->id]);
       $instructorWallet = Wallet::firstOrCreate(['user_id' => $instructor->id]);
       $price=$course->price;
        if (!$studentWallet || $studentWallet->balance < $price) {
        return response()->json([
            'message' => 'your balance is not enough',
        ], 404);
    }
       DB::transaction(function () use ($studentWallet,  $instructorWallet, $price, $user, $course) {
        // خصم من الطالب
        $studentWallet->decrement('balance', $price);
        // إضافة للمدرّس
          $instructorWallet->increment('balance', $price);

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
public function getCourseLessonsWithStatus($course_id, $user)
{
    $course = Course::with('contents')->findOrFail($course_id);
 $lessons = $course->contents->sortBy('id')->values();

    // إذا المستخدم مسجّل دخول نتحقق إذا اشترى الكورس
   $isPaid = $this->isUserPaid($user, $course);
    // إذا الزائر مو مسجّل دخول
  /*  if (!$user) {
        $lessons = $course->contents->sortBy('id')->values();
        foreach ($lessons as $index => $lesson) {
            $lesson->is_paid = false;
            $lesson->is_previous_lesson_passed = false;
            $lesson->videoNum = $index + 1;
        }
        return $lessons;
    }
*/
    // إذا المستخدم مسجّل دخول
    $isPaid = $this->isUserPaid($user, $course);
    $lessons = $course->contents->sortBy('id')->values();

    foreach ($lessons as $index => $lesson) {
        $lesson->is_paid = $isPaid;
        $lesson->is_previous_lesson_passed = false;

        if ($isPaid) {
            if ($index === 0) {
                $unlock = true;
            } else {
                $previousLesson = $lessons[$index - 1];
                $unlock = $previousLesson->isPassedByUser($user);
            }

            $lesson->is_previous_lesson_passed = $unlock;
        }

        $lesson->videoNum = $index + 1;
    }

    return $lessons;
}
}