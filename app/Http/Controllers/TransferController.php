<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{

    public function history(){
        $transfer = Transfer::where('sender_id', auth()->user()->id)->get();
        return response()->json([
            'data' => $transfer,
        ]);
    }

    public function store(Request $request){
        $user = auth()->user();
        $request->validate([
            'phone_number' => 'required|exists:users,phone_number',
            'amount' => 'required',
        ]);

        $receiver = User::where('phone_number',$request->phone_number)->first();

        if ($request->phone_number === $user->phone_number) {
            return response()->json([
                'message' => 'Maaf anda tidak bisa melakukan transfer ke nomor anda'
            ]);
        }else{
            if ($request->amount > $user->balance) {
                return response()->json([
                    'message' => 'Maaf saldo anda tidak mencukupi'
                ]);
            }else{
                $transfer = Transfer::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $receiver->id,
                    'amount' => $request->amount
                ]);
                return response()->json([
                    'data' => $transfer,
                    'message' => 'Transfer Saldo Berhasil'
                ]);
            }
        }
    }
}
