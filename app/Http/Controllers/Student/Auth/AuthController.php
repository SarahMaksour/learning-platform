<?php

namespace App\Http\Controllers\Student\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $token = $user->createToken('Auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'User' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {

        if (!Auth::attempt($request->only('email', 'password')))
            return response()->json([
                'message' => 'user login failed'
            ], 401);

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('Auth_token')->plainTextToken;

         return response()->json([
        'message' => 'User login successfully',
       'User' => $user,
        'token' => $token
    ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => ' logout successfully',
        ]);
    }
}
