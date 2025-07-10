<?php

namespace App\Http\Controllers\wallet;

use App\Services\WalletService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RechargeRequest;

class WalletController extends Controller
{
    protected $walletService;
    public function __construct(WalletService $walletService){
        $this->walletService=$walletService;
    }
    public function recharge(RechargeRequest $request){
    $wallet=$this->walletService->recharge(Auth::user(),$request->amount);
    return response()->json([
            'message' => 'recharge balance successfully',
            'balance' => $wallet->balance
]);
    }
}
