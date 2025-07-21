<?php

use Illuminate\Http\Request;
use App\Services\CourseReview;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\Auth\RoleController;

use App\Http\Controllers\Student\home\FilterController;
use App\Http\Controllers\Student\home\SearchController;
use App\Http\Controllers\Student\Auth\AuthController;
use App\Http\Controllers\Student\home\HomeController;
use App\Http\Controllers\Student\Quiz\QuizController;
use App\Http\Controllers\Student\home\viewAllController;
use App\Http\Controllers\Student\Courses\CourseDetailsController;
use App\Http\Controllers\Student\Courses\CourseReviewsController;
use App\Http\Controllers\Student\Courses\VideoContentController;
use App\Http\Controllers\Student\myProfile\myProfileController;
use App\Http\Controllers\Student\wallet\WalletController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);
Route::post('logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')
->group(function () {
Route::get('/home', [HomeController::class, 'homePage']);
Route::get('/viewAllCourses' ,[viewAllController::class,'homePage']);
Route::post('role',[RoleController::class ,'setRole']);
Route::get('/courseDetail/{id}' ,[CourseDetailsController::class,'getAboutCourse']);
Route::get('/filter',[FilterController::class ,'filterCourses']);
Route::get('search',[SearchController::class ,'search']);
Route::get('/courseReview/{course_id}' , [CourseReviewsController::class , 'CourseReviews']);
Route::get('/courses/{id}/status',[CourseDetailsController::class,'checkCourseStatus']);
//Route::get('/quiz/{id}',[QuizController::class,'getQuizWithQuestion']);
//Route::post('/quiz/submit-answer', [QuizController::class, 'submitAnswer']);
//Route::post('/quiz/finalize', [QuizController::class, 'finalizeQuiz']);
Route::get('/quiz/{id}', [QuizController::class, 'show']);
Route::post('/quiz/submit', [QuizController::class, 'submit']);
Route::prefix('contents')->group(function () {
    Route::get('{id}', [VideoContentController::class, 'show']);
    Route::post('{id}/comment', [VideoContentController::class, 'storeComment']);
    Route::post('comments/{commentId}/reply', [VideoContentController::class, 'storeReply']);
});
Route::get('myProfile',[myProfileController::class,'show']);

Route::post('wallet/recharge',[WalletController::class,'recharge']);
Route::get('wallet',[WalletController::class,'showWallet']);
Route::get('/courses/{id}/lessons', [CourseDetailsController::class, 'getCourseLesson']);
Route::post('/courses/{id}/pay', [CourseDetailsController::class, 'PayTheCourse']);

});