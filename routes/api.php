<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\home\HomeController;
use App\Http\Controllers\home\viewAllController;
use App\Http\Controllers\Courses\CourseDetailsController;

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
});