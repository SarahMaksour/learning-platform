<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    protected $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function filter(Request $request)
    {
        $courses = $this->filterService->filterCourses($request);

        $modified = $courses->map(function ($course) {
            return [
                'id' => $course->id,
                'name' => $course->name,
                'price' => $course->price,
                'description' => $course->description,
                'average_rating' => round($course->reviews_avg_rating ?? 0, 1),
                'total_duration_minutes' => floor(($course->videos_sum_duration ?? 0) / 60),
                'videos' => $course->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'duration_seconds' => $video->duration,
                        'duration_minutes' => floor($video->duration / 60),
                    ];
                }),
            ];
        });

        return response()->json([
            'data' => $modified
        ], 200);
    }
}