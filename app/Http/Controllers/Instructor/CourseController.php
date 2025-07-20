<?php

namespace App\Http\Controllers\Instructor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Requests\StoreCourseRequest;
use App\Services\Instructor\CreateCourseService;

class CourseController extends Controller
{
    protected $createCourseService;

    public function __construct(CreateCourseService $createCourseService)
    {
        $this->createCourseService = $createCourseService;
    }
    public function store(StoreCourseRequest $request)
{
    $course = $this->createCourseService->getCourseWithLesson($request->validated());
    return new CourseResource($course);
}
}
