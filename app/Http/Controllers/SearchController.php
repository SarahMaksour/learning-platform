<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SearchService;
use App\Http\Resources\CourseResource;

class SearchController extends Controller
{
   protected  $searchservice;
    public function __construct(SearchService $searchservice){
        $this->searchservice=$searchservice;
    }
    public function search(Request $request){
        $query=$request->input('q');
        if(!$query){
            return response()->json([
          'message'=>'يرجى إدخال كلمة البحث'
            ],400);
        }
        $courses=$this->searchservice->getsearch($query);
      
        return response()->json([
      'data'=>CourseResource::collection($courses ),
        ],200);
    }
}
