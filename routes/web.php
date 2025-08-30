<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Dashboard\QuizController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CourseController;
use App\Http\Controllers\Dashboard\LessonController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EnrollmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboards', [DashboardController::class, 'index'])->name('dashboard.index');
//user
    Route::resource('users', UserController::class);
     // Route::get('/users', [UserController::class, 'index'])->name('users.index');
     Route::get('users/trash', [UserController::class, 'trashedUsers'])->name('users.trashed');
    Route::delete('users/{id}/forceDelete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
//courses
    Route::resource('courses', CourseController::class);
  // Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
//lesson
 Route::resource('lessons', LessonController::class);
    //Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::resource('/enrollments', EnrollmentController::class);
//quiz
    Route::resource('/quizzes', QuizController::class);
//profile
 Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
 Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::post('/settings/update', [ProfileController::class, 'updateSettings'])->name('settings.update');

    // اللوغ أوت
    Route::post('/logout', [ProfileController::class, 'logout'])->name('logout');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/ratings', [RatingController::class, 'index'])->name('ratings.index');

