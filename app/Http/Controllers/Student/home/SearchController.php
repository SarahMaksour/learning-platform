<?php

namespace App\Http\Controllers\Student\home;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;

class SearchController extends Controller
{
   protected  $searchservice;
    public function __construct(SearchService $searchservice){
        $this->searchservice=$searchservice;
    }
     /*public function search(Request $request){
        $search=$request->search;
          if (empty($search)) {
        return response()->json([
            'message' => 'يرجى إدخال كلمة البحث'
        ], 400);
    }
        $courses=Course::where(function($query) use ($search){
          $query->where('title','like',"%$search%")
          ->orWhere('description','like',"%$search%");
        })->get();

        return response()->json([
      'data'=>CourseResource::collection($courses ),
        ],200);
    }*/
    /*    public function search(Request $request)
{
    $q = $request->input('q');

    if (empty($q)) {
        return response()->json(['message' => 'يرجى إدخال كلمة البحث'], 400);
    }

    $results = Course::search($q)->get();
 // فلترة دقيقة بعد TNTSearch
    $filtered = $results->filter(function ($course) use ($q) {
        $qLower = mb_strtolower($q);
        return str_contains(mb_strtolower($course->title), $qLower)!== false
         ; 
         });

    return response()->json([
        'data' => CourseResource::collection( $filtered ),
    ]);
}*/
public function search(Request $request)
{
    $q = trim($request->input('q'));

    if (empty($q)) {
        return response()->json(['message' => 'يرجى إدخال كلمة البحث'], 400);
    }

    // البحث باستخدام Scout/TNTSearch
    $results = Course::search($q)->get();

    // فلترة دقيقة لدعم الكلمات الجزئية والتهجئة المختلفة
    $filtered = $results->filter(function ($course) use ($q) {
        $qLower = mb_strtolower($q);
        $titleLower = mb_strtolower($course->title);

        // البحث عن الكلمة الجزئية
        return str_contains($titleLower, $qLower);
    });

    return response()->json([
        'data' => CourseResource::collection($filtered->values()),
    ]);
}


}
