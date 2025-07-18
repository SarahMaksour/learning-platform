<?php

namespace App\Http\Controllers\Student\myProfile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class myProfileController extends Controller
{
public function show(){
    return response()->json([
        'user_name'=>Auth()->user()->name,
        'email'=>Auth()->user()->email
    ]);
}
}
