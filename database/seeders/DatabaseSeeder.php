<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Course;
use App\Models\Quizze;
use App\Models\Review;
use App\Models\Enrolment;
use App\Models\Discussion;
use App\Models\UserDetail;
use App\Models\Certificate;
use App\Models\CourseContent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
         User::factory(5)->create()->each(function ($user) {
            $user->userDetail()->create(UserDetail::factory()->make()->toArray());

             // ننشئ كورس رئيسي أولاً
    $parentCourse = Course::factory()->create(['user_id' => $user->id, 'parent_course_id' => null]);

    // ننشئ 2 كورسات فرعية مرتبطة بالكورس الأب
    $childCourses = Course::factory(2)->create([
        'user_id' => $user->id,
        'parent_course_id' => $parentCourse->id,
    ]);  // نسجل المستخدم في كل الكورسات (الأب و الفرعيين)
    $allCourses = collect([$parentCourse])->merge($childCourses);

    foreach ($allCourses as $course) {
        Enrolment::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
                // مراجعة عشوائية
                Review::factory()->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);

                // شهادة
                Certificate::factory()->create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ]);
            }
        });
    }
}
