<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Services\Instructor\MyCourseService;
use App\Http\Requests\Instructor\courseRequest;

class CourseController extends Controller
{
    protected $myCourseService;
    public function __construct(MyCourseService $myCourseService){
        $this->myCourseService=$myCourseService;
    }
    public function getMyCourse(){
$courses=$this->myCourseService->getMyCourse();
return response()->json([
            'data' => CourseResource::collection($courses)
        ], 200);
    }

    public function addCourse(courseRequest $request){
         $data = $request->validated();
          $data['user_id'] = auth()->id();
$response=$this->myCourseService->addCourse($data);
return response()->json([
    $response
],201);
    }
}
