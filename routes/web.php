<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

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
    return view('welcome');
});
Route::get('/debug-db', function () {
    return DB::select('SELECT DATABASE() AS db, NOW() AS time');
});
Route::get('/db-test', function () {
    try {
       DB::connection()->getPdo();
        return '✅ Connected to DB successfully';
    } catch (\Exception $e) {
        return '❌ DB Connection Error: ' . $e->getMessage();
    }
});
