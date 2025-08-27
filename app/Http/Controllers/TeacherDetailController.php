<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDetailController extends Controller
{
     public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

       
        $request->validate([
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', 
        ]);

       
        $imagePath = $user->detail->image ?? null;
        if ($request->hasFile('image')) {
           
            $imagePath = $request->file('image')->store('teachers', 'public');
        }

       
        $detail = UserDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'specialization' => $request->specialization,
                'bio' => $request->bio,
                'image' => $imagePath,
            ]
        );

        return response()->json([
            'message' => 'Profile details saved successfully',
            'data' => [
                'specialization' => $detail->specialization,
                'bio' => $detail->bio,
                'image_url' => $detail->image ? asset('storage/'.$detail->image) : null, 
            ]
        ], 200);
    }
}


