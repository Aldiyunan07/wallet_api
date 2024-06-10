<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function history(){
        $transaction = auth()->user()->wallet->transaction;
        return response()->json([
            'data' => $transaction
        ]); 
    }

    public function store(Request $request)
    {
        $wallet = auth()->user()->wallet;
        if ($wallet) {
            $request->validate([
                'amount' => 'required|numeric',
                'type' => 'required|string|in:topup,withdraw',
                'status' => 'required|string|in:success'
            ]);

            $data = $wallet->transaction()->create([
                'amount' => $request->amount,
                'type' => $request->type,
                'status' => $request->status
            ]);

            if ($request->status == 'success') {
                if ($request->type == "topup") {
                    $wallet->balance += $request->amount;
                } else if ($request->type == "withdraw") {
                    $wallet->balance -= $request->amount;
                }
                $wallet->save();
            }

            return response()->json([
                'data' => $data
            ]);
        } else {
            return response()->json([
                'message' => 'Maaf, anda tidak mempunyai wallet'
            ], 404);
        }
    }

}
