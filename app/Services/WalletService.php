<?php
namespace App\Services;
use App\Models\Walled;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class WalletService{
    public function recharge($user,$amount){
        return DB::transaction(function () use ($user,$amount){
            $wallet=Wallet::firstOrCreate(['user_id'=>$user->id]);
            $wallet->increment('balance',$amount);
            Transaction::create([
                'wallet_id'=>$wallet->id,
                'amount'=>$amount,
                'type'=>'credit'
            ]);
            return $wallet->fresh();
        });
    }
}


