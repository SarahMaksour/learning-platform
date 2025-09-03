<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TeacherDetailController extends Controller
{
     public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

       
       
        $request->validate([
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', 
        ]);

       
       $imagePath = $user->detail->image ?? null;

if ($request->hasFile('image')) {
    // حذف القديمة
    if ($imagePath && File::exists(public_path($imagePath))) {
        File::delete(public_path($imagePath));
    }

    $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
    $request->file('image')->move(public_path('images/teachers'), $filename);

    $imagePath = 'images/teachers/' . $filename; // نخزن المسار النسبي
}

$detail = UserDetail::updateOrCreate(
    ['user_id' => $user->id],
    [
        'specialization' => $request->specialization,
        'bio' => $request->bio,
        'image' => $imagePath, // تأكد إنو هذا يتخزن بالـ DB
    ]
);

// Response
return response()->json([
    'message' => 'Profile details saved successfully',
    'data' => [
        'specialization' => $detail->specialization,
        'bio' => $detail->bio,
        'image_url' => $detail->image ? asset($detail->image) : null,
    ]
], 200);
    }
}


