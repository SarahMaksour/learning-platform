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
// نجيب الـ user من Sanctum إذا مسجل دخول
    $user = auth('sanctum')->user();

    // إذا ما فيه user، نرجع guest
    $userResource = $user
        ? new UserResource($user)
        : new UserResource((object)[
            'id' => 0,
            'name' => 'Guest',
            'email' => 'guest@example.com'
        ]);

    return response()->json([
         'user' => $userResource,
        'popular_courses' => CourseResource::collection($featuredPopular),
        'top_rated_courses' => CourseResource::collection($featuredTopRated),
        ]
    ,201);
}
}
