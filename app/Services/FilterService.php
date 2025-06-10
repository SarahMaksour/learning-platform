<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class FilterService
{
   public function filterCourses(Request $request)
    {
        $query = Course::query()
            ->with(['instructor:id,name'])
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'enrollments'])
            ->withSum('videoContents.contentable as total_video_duration', 'duration');

        if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }

        if ($request->filled('rating')) {
            $query->having('reviews_avg_rating', '>=', $request->rating);
        }

        if ($request->filled('duration_min') && $request->filled('duration_max')) {
            $min = $request->duration_min * 60;
            $max = $request->duration_max * 60;
            $query->havingRaw('total_video_duration BETWEEN ? AND ?', [$min, $max]);
        }

        return $query->get(['id', 'title', 'price', 'image', 'user_id']);
    }
}

