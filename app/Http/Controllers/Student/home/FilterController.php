<?php

namespace App\Http\Controllers\Student\home;

use Illuminate\Http\Request;
use App\Services\FilterService;
use App\Http\Controllers\Controller;

class FilterController extends Controller
{
 protected  $filterService;
    public function __construct(FilterService $filterService){
        $this->filterService=$filterService;
    }

    public function filter(Request $request){
   $courses=$this->filterService->filterCourses($request);
   return response()-> json(['data'=> $courses],201);
    }
    
    
}
