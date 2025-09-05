<?php

namespace App\Http\Controllers\Student\home;

use Illuminate\Http\Request;
use App\Services\Api\HomeService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\CourseResource;

class HomeController extends Controller
{
    protected $homeService;
    public function __construct(HomeService $homeService){
        $this->homeService=$homeService;
    }
    
public function homePage(){
    $featuredPopular=$this->homeService->getPopularCourses();
    $featuredTopRated=$this->homeService->getTopRatedCourses();
$userModel = auth()->user(); // ترجع null إذا مش مسجل دخول
$user = $userModel 
    ? new UserResource($userModel)
    : new UserResource((object)[
        'id' => 0,
        'name' => 'Guest',
        'email' => 'null@gmail.com'
    ]);
    return response()->json([
         'user' => $user,
        'popular_courses' => CourseResource::collection($featuredPopular),
        'top_rated_courses' => CourseResource::collection($featuredTopRated),
        ]
    ,201);
}
}
