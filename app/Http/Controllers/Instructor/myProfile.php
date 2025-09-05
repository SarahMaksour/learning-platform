<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Wallet;
use App\Models\UserDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class myProfile extends Controller
{
   public function withdraw(Request $request)
{
    $request->validate([
        'amount' => 'required|numeric|min:1',
    ]);

    $user = Auth::user();

    // جلب أو إنشاء المحفظة
    $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

    $amount = $request->amount;

    // تحقق إذا الرصيد كافي
    if ($wallet->balance < $amount) {
        return response()->json([
            'message' => 'Insufficient balance',
          
        ], 400);
    }

    DB::transaction(function() use ($wallet, $amount) {
        // خصم المبلغ
        $wallet->decrement('balance', $amount);

        // تسجيل العملية
        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $amount,
            'type' => 'debit'
        ]);
    });

    // جلب الرصيد الجديد
    $wallet->refresh();

    return response()->json([
        'message' => 'Balance withdrawn successfully',
      
    ], 200);
}



public function show()
    {
        $user = Auth::user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'specialization' => $user->specialization,
            'bio' => $user->bio,
            'image' => $user->image ? url($user->image) : null,
        ], 200);
    }

   public function update(Request $request)
    {
        $user = Auth::user();

        // التحقق من صحة البيانات
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'specialization'=> 'nullable|string|max:255',
            'bio'           => 'nullable|string|max:1000',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // تحديث جدول users
 $user->name  = $request->name;
$user->email = $request->email;
$user->save();

        // جلب التفاصيل الخاصة بالمستخدم
    $userDetail = $user->userDetail;

if (!$userDetail) {
    $userDetail = new UserDetail();
    $userDetail->user_id = $user->id;
}
        // تحديث الحقول
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles', 'public');
            $userDetail->image = $path;
        }

        $userDetail->specialization = $request->specialization;
        $userDetail->bio = $request->bio;

        $userDetail->save();

        return response()->json([
            'message' => 'تم تعديل بياناتك بنجاح'
        ]);
    }
    public function showProfile()  
    {  
        $user = Auth::user();  

        return response()->json([  
           // 'id'=>$user->id,
            'user_name' => $user->name,  
            'email'     => $user->email,  
            'image'     => $user->userDetail && $user->userDetail->image  
                                ? asset('/' . $user->userDetail->image)  
                                : null,  
  //      'image_course' =>$user->userDetail->image? asset($this->image) : null,

        ]);  
    }  
}
