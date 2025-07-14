<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Services\SearchService;
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
        public function search(Request $request)
{
    $search = $request->input('search');

    if (empty($search)) {
        return response()->json(['message' => 'يرجى إدخال كلمة البحث'], 400);
    }

    $results = Course::search($search)->get();

    return response()->json([
        'data' => CourseResource::collection($results),
    ]);
}

}
