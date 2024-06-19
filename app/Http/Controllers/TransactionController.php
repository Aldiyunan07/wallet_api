<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function history(){
        $transaction = auth()->user()->transactions;
        return response()->json([
            'data' => $transaction
        ]); 
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $request->validate([
                'amount' => 'required|numeric',
                'type' => 'required|string|in:topup,withdraw',
                'status' => 'required|string|in:success'
            ]);

            $data = $user->transactions()->create([
                'amount' => $request->amount,
                'type' => $request->type,
                'status' => $request->status
            ]);

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
