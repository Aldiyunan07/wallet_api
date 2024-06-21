<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

class TransferController extends Controller
{

    public function history(){
        $transfer = Transfer::where('sender_id', auth()->user()->id)->orWhere('receiver_id', auth()->user()->id)->with(['receiver','sender'])->orderBy('created_at','desc')->get();
        return response()->json([
            'data' => $transfer,
        ]);
    }

    public function store(Request $request){
        $user = auth()->user();
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required',
        ]);

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
                    'receiver_id' => $request->user_id,
                    'amount' => $request->amount,
                    'status' => 'pending'
                ]);
                return response()->json([
                    'data' => $transfer,
                ]);
            }
        }
    }

    public function confirmation(Transfer $transfer, Request $request){
        $request->validate([
            'pin' => 'required'
        ]);
        if ($request->pin !== auth()->user()->pin) {
            return response()->json([
                'message' => 'Pin salah'
            ]); 
        }
        $transfer->update([
            'status' => 'success'
        ]);
        return response()->json([
            'data' => $transfer,
            'message' => 'Transfer berhasil'
        ]);
    }
    
    public function detail(Transfer $transfer){
        $transfer = Transfer::whereId($transfer->id)->with(['receiver','sender'])->first();
        return response()->json([
            'data' => $transfer
        ]);
    }
}
