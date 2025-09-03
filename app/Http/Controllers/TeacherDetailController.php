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

        if ($user->role !== 'teacher') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

       
        $request->validate([
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', 
        ]);

       
        $imagePath = $user->detail->image ?? null;
       // إذا في صورة جديدة
        if ($request->hasFile('image')) {
            // حذف القديمة إذا موجودة
            if ($imagePath && File::exists(public_path($imagePath))) {
                File::delete(public_path($imagePath));
            }

            // رفع الصورة الجديدة داخل public/images/teachers
            $filename = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/teachers'), $filename);

            // نخزن المسار النسبي (من public/)
            $imagePath = 'images/teachers/' . $filename;
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


