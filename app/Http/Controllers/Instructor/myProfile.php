<?php

namespace App\Http\Controllers\Instructor;

use App\Models\User;
use App\Models\Wallet;
use App\Models\UserDetail;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\SupabaseService;

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

  /* public function update(User $user,Request $request)
    {
       // $user = Auth::user();
  //dd($request->all());
      // التحقق من صحة البيانات
       $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'specialization'=> 'nullable|string|max:255',
            'bio'           => 'nullable|string|max:1000',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
      
$name = $request->input('name');
$email = $request->input('email');
$specialization = $request->input('specialization');
$bio = $request->input('bio');
        // تحديث جدول users
 $user->name  = $name;
$user->email = $email;
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

        $userDetail->specialization = $specialization;
        $userDetail->bio = $bio;

        $userDetail->save();

        return response()->json([
          'message' => 'data updated successfully',
          ]);
    }*/
    
    
    public function update(User $user, Request $request)
{
    // التحقق من صحة البيانات
    $request->validate([
        'name'           => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email,' . $user->id,
        'specialization' => 'nullable|string|max:255',
        'bio'            => 'nullable|string|max:1000',
        'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $name = $request->input('name');
    $email = $request->input('email');
    $specialization = $request->input('specialization');
    $bio = $request->input('bio');

    // تحديث جدول users
    $user->name  = $name;
    $user->email = $email;
    $user->save();

    // جلب التفاصيل الخاصة بالمستخدم
    $userDetail = $user->userDetail ?? new UserDetail(['user_id' => $user->id]);

    // رفع الصورة إلى Supabase إذا موجودة
    if ($request->hasFile('image')) {
        $supabase = new SupabaseService();
        $imageFile = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

        // رفع الصورة
        $supabase->uploadImage($imageFile, $imageName);

        // رابط مباشر للصورة
        $imageUrl = env('SUPABASE_URL') 
                    . "/storage/v1/object/public/" 
                    . env('SUPABASE_BUCKET') 
                    . "/" . $imageName;

        $userDetail->image = $imageUrl;
    }

    // تحديث باقي الحقول
    $userDetail->specialization = $specialization;
    $userDetail->bio = $bio;
    $userDetail->save();

    return response()->json([
        'message' => 'Data updated successfully',
        'data' => [
            'name' => $user->name,
            'email' => $user->email,
            'specialization' => $userDetail->specialization,
            'bio' => $userDetail->bio,
            'image_url' => $userDetail->image,
        ]
    ], 200);
}
          public function showProfile()  
    {  
        $user = Auth::user();  

        return response()->json([  
            'id'=>$user->id,
            'user_name' => $user->name,  
            'email'     => $user->email,  
            'image'     => $user->userDetail && $user->userDetail->image  
                                ? asset('/' . $user->userDetail->image)  
                                : null,  
  //      'image_course' =>$user->userDetail->image? asset($this->image) : null,
'specialization'=> $user->UserDetail->specialization,
            'bio'           =>  $user->UserDetail->bio,
           
        ]);  
    }  
}
