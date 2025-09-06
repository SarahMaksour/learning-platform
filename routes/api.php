<?php

use Illuminate\Http\Request;
use App\Services\CourseReview;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\myProfile;

use App\Http\Controllers\TeacherDetailController;
use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\Student\Auth\AuthController;
use App\Http\Controllers\Student\Auth\RoleController;
use App\Http\Controllers\Student\home\HomeController;
use App\Http\Controllers\Student\Quiz\QuizController;
use App\Http\Controllers\Student\home\FilterController;
use App\Http\Controllers\Student\home\SearchController;
use App\Http\Controllers\Student\home\viewAllController;
use App\Http\Controllers\Student\wallet\WalletController;
use App\Http\Controllers\Student\myProfile\myProfileController;
use App\Http\Controllers\Student\Courses\VideoContentController;
use App\Http\Controllers\Student\Courses\CourseDetailsController;
use App\Http\Controllers\Student\Courses\CourseReviewsController;

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
Route::get('/viewAllCourses' ,[viewAllController::class,'homePage']);
Route::post('role',[RoleController::class ,'setRole']);
Route::get('/filter',[FilterController::class ,'filterCourses']);
Route::get('/courses/{id}/status',[CourseDetailsController::class,'checkCourseStatus']);
Route::post('/teacherDetail',[TeacherDetailController::class ,'storeOrUpdate']);
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
Route::post('/courses/{id}/pay', [CourseDetailsController::class, 'PayTheCourse']);
Route::get('/instructor/courses', [CourseController::class, 'getMyCourse']);
Route::post('/add/courses', [CourseController::class, 'addCourse']);
Route::post('/courses/{id}/update', [CourseController::class, 'updateCourse']);
Route::get('/editCourses/{id}', [CourseController::class, 'getCourse']);
Route::get('/my-courses/all', [myProfileController::class, 'myEnrolledCourses']);
Route::get('/my-courses/complete', [myProfileController::class, 'myFullyCompletedCourses']);
Route::get('tecaherMyProfile',[myProfile::class,'showProfile']);

Route::delete('/deleteCourses/{id}', [CourseController::class, 'deleteCourse']);
Route::delete('/courses/{courseId}/lessons/{contentId}', [CourseController::class, 'deleteLesson']);
Route::post('/wallet/withdraw', [myProfile::class, 'withdraw']);
Route::post('/courses/{course_id}/review', [CourseReviewsController::class, 'store']);

 Route::get('/student/profile', [myProfileController::class, 'edit']);

    // تحديث بيانات الطالب
    Route::put('/student/profile', [myProfileController::class, 'update']);
});



Route::post('/image', [CourseController::class, 'testUpload']);
Route::get('/home', [HomeController::class, 'homePage']);
Route::get('search',[SearchController::class ,'search']);
Route::get('/courseDetail/{id}' ,[CourseDetailsController::class,'getAboutCourse']);
Route::get('/courseReview/{course_id}' , [CourseReviewsController::class , 'CourseReviews']);
//Route::get('/courses/{id}/lessons', [CourseDetailsController::class, 'getCourseLesson']);
Route::get('/courses/{id}/lessons', [CourseDetailsController::class, 'getCourseLesson']);

// فيديوهات
Route::get('/media/videos/{filename}', function ($filename) {
    $path = '/mnt/volumes/media/videos/' . $filename;
    if (!file_exists($path)) abort(404);
    return response()->file($path);
});

// صور
Route::get('/media/images/{filename}', function ($filename) {
    $path = '/mnt/volumes/media/images/' . $filename;
    if (!file_exists($path)) abort(404);
    return response()->file($path);
});
/*Route::get('/media/{folder}/{filename}', function($folder, $filename){
    $path = "/media/$folder/$filename";

    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
});*/
