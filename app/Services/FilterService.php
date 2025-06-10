<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class FilterService
{
   public function filterCourses(Request $request)
    {
        // جلب الكورسات مع العلاقات اللازمة
        $query = Course::with(['contents.contentable', 'instructor:id,name', 'reviews', 'enrollments'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'enrollments']);

        // فلترة السعر
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        // فلترة التقييم
        if ($request->filled('rating')) {
            $query->having('reviews_avg_rating', '>=', $request->rating);
        }

        $courses = $query->get(['id', 'title', 'price', 'image', 'user_id']);

        // حساب مدة الفيديوهات يدويًا
        foreach ($courses as $course) {
            $totalDuration = 0;
            foreach ($course->contents as $content) {
                if ($content->contentable_type === \App\Models\Video::class && $content->contentable) {
                    $totalDuration += $content->contentable->duration;
                }
            }
            $course->total_video_duration = $totalDuration;
        }

        // فلترة على مدة الفيديوهات باستخدام Collection
        if ($request->filled('duration_min') && $request->filled('duration_max')) {
            $min = $request->duration_min * 60;
            $max = $request->duration_max * 60;

            $courses = $courses->filter(function ($course) use ($min, $max) {
                return $course->total_video_duration >= $min && $course->total_video_duration <= $max;
            })->values();
        }

        return $courses;
    }

}