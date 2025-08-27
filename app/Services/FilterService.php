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
        return $courses;
    }
}
