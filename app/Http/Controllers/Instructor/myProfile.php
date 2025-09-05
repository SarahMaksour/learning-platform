<?php

namespace App\Http\Controllers\Instructor;

use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
            'balance' => $wallet->balance
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
        'balance' => $wallet->balance
    ], 200);
}

}
