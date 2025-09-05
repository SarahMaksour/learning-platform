<?php

namespace App\Http\Controllers\Student\wallet;

use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RechargeRequest;
use App\Models\Wallet;

class WalletController extends Controller
{
    protected $walletService;
    public function __construct(WalletService $walletService){
        $this->walletService=$walletService;
    }
    public function showWallet(Request $request){
        $user_id=Auth()->user()->id;
        $wallet=Wallet::where('user_id', $user_id)->first();

         return response()->json([
            'message' => 'get balance successfully',
            'balance' => optional($wallet)->balance??"0"
]);

    }
    public function recharge(RechargeRequest $request){
    $wallet=$this->walletService->recharge(Auth::user(),$request->amount);
    return response()->json([
            'message' => 'recharge balance successfully',
            'balance' => $wallet->balance
]);
    }
}
