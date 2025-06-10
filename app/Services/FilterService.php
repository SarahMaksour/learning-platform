<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class FilterService
{
    public function filterCourses(Request $request)
    {
        $query = Course::query()
            ->with(['videos', 'reviews']) // تحميل العلاقات
            ->withSum('videos', 'duration') // مجموع مدة الفيديوهات
            ->withAvg('reviews', 'rating'); // متوسط التقييم

        // فلترة السعر
        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        // فلترة التقييم
        if ($request->filled('rating')) {
            $query->having('reviews_avg_rating', '>=', $request->rating);
        }

        // فلترة المدة
        if ($request->filled('duration_min') && $request->filled('duration_max')) {
            $min = $request->duration_min * 60;
            $max = $request->duration_max * 60;
            $query->havingRaw('videos_sum_duration BETWEEN ? AND ?', [$min, $max]);
        }

        return $query->get();
    }
}
