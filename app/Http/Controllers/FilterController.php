<?php

namespace App\Http\Controllers;

use App\Services\FilterService;
use Illuminate\Http\Request;

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
