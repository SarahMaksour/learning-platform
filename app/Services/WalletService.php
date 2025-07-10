<?php
namespace Services;

use App\Models\Transaction;
use App\Services;
use App\Models\Walled;
use Illuminate\Support\Facades\DB;

class WalletService{
    public function recharge($user,$amount){
        return DB::transaction(function () use ($user,$amount){
            $wallet=Walled::firstOrCreate(['user_id'=>$user->id]);
            $wallet->increment('balance',$amount);
            Transaction::create([
                'walled_id'=>$wallet->id,
                'amount'=>$amount,
                'type'=>'credit'
            ]);
            return $wallet->fresh();
        });
    }
}


