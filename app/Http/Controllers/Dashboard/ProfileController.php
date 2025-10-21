<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // صفحة الملف الشخصي
    public function index()
    {
        $user = Auth::user();
        return view('new-dashboard.profile.profile', compact('user'));
    }

    // تحديث البيانات
  public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // تحديث الاسم والايميل
    $user->name = $request->name;
    $user->email = $request->email;

    // تحديث الباسوورد إذا تم تعبئته
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    // التعامل مع الصورة وحفظها في جدول user_details
    if ($request->hasFile('avatar')) {
        $avatarName = time() . '.' . $request->avatar->extension();
        $request->avatar->move(public_path('uploads/avatars'), $avatarName);

        // التأكد من وجود سجل details أو إنشاؤه
        $details = $user->UserDetail ?? $user->UserDetail()->create([]);

        $details->image = 'uploads/avatars/' . $avatarName;
        $details->save();
    }

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

    // صفحة الإعدادات
    public function settings()
    {
        $user = Auth::user();
        return view('new-dashboard.profile.setting', compact('user'));
    }

    // تحديث الإعدادات
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'theme' => 'nullable|in:light,dark',
        ]);

        $user->theme = $request->theme ?? 'light';
        $user->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }

    // اللوغ أوت
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
