<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;
use Termwind\Components\Raw;
use Illuminate\Support\Facades\DB;

class FilterService
{
    public function filterCourses(Request $request){
        $query = Course::select('courses.*')
            ->leftJoin('videos', 'courses.id', '=', 'videos.course_id')
            ->groupBy('courses.id');
    if ($request->filled('price_min') && $request->filled('price_max')) {
            $query->whereBetween('courses.price', [$request->price_min, $request->price_max]);
        }

    if ($request->filled('rating')) {
    $query->whereHas('reviews', function ($q) use ($request) {
        $q->select(DB::raw('AVG(rating) as avg_rating'))
            ->groupBy('course_id')
            ->havingRaw('AVG(rating) >= ?', [$request->rating]);
    });
   
        }
        
   if($request->filled('duration_min')&&$request->filled('duration_max')){
    $durationMin = $request->duration_min * 60;
    $durationMax = $request->duration_max * 60;
    $query->havingRaw('SUM(videos.duration) BETWEEN ? AND ?', [
        $durationMin,
        $durationMax,
    ]);
   }

   $query->with('reviews','videos');
   return $query->get();
    }
}