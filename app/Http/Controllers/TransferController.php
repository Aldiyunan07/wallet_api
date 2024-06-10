<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{

    public function history(){
        $transfer = Transfer::where('sender_id', auth()->user()->wallet->id)->get();
        return response()->json([
            'data' => $transfer,
        ]);
    }

    public function store(Request $request){
        $wallet = auth()->user()->wallet;
        $request->validate([
            'receiver_id' => 'required|exists:wallets,id',
            'amount' => 'required',
        ]);

        if ($request->receiver_id === $wallet->id) {
            return response()->json([
                'message' => 'Maaf anda tidak bisa melakukan transfer ke nomor anda'
            ]);
        }else{
            if ($request->amount > $wallet->balance) {
                return response()->json([
                    'message' => 'Maaf saldo anda tidak mencukupi'
                ]);
            }else{
                $transfer = $wallet->sentTransfers()->create([
                    'receiver_id' => $request->receiver_id,
                    'amount' => $request->amount
                ]);
                $wallet->balance -= $request->amount;
                $wallet->save();
                $receiver = Wallet::whereId($request->receiver_id)->first();
                $receiver->update([
                    'balance' => $receiver->balance + $request->amount
                ]);
                return response()->json([
                    'data' => $transfer,
                    'message' => 'Transfer Saldo Berhasil'
                ]);
            }
        }
    }
}
