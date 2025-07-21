<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;
use Termwind\Components\Raw;
use Illuminate\Support\Facades\DB;

class FilterService
{
    public function filterCourses(Request $request)
    {
        // جلب الكورسات مع العلاقات اللازمة
        $query = Course::with(['contents.contentable', 'instructor:id,name', 'reviews', 'enrollments'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'enrollments']);

        // فلترة السعر
        if ($request->filled('minPrice') && $request->filled('maxPrice')) {
            $query->whereBetween('price', [$request->minPrice, $request->maxPrice]);
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
            $course->setAttribute('total_video_duration', $totalDuration);
        }

        // فلترة على مدة الفيديوهات باستخدام Collection
        if ($request->filled('minDuration') && $request->filled('maxDuration')) {
            $min = $request->minDuration * 60;
            $max = $request->maxDuration * 60;
            if ($min > $max) {
                [$min, $max] = [$max, $min];
            }
            $courses = $courses->filter(function ($course) use ($min, $max) {
                return $course->total_video_duration >= $min && $course->total_video_duration <= $max;
            })->values();
        }

        return $courses;
    }
}
