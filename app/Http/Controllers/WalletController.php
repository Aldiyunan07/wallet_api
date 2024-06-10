<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(){
        if (auth()->user()->wallet) {
            $data = auth()->user()->wallet;
        }else{
            $data = null;
        }

        return response()->json([
            'data' => $data
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'phone_number' => 'required',
            'pin' => 'required|confirmed'
        ]);

        $data = auth()->user()->wallet()->updateOrCreate([
                    'phone_number' => $request->phone_number
                ],[
                    'pin' => $request->pin,
                    'balance' => 0
                ]);
        return response()->json([
            'data' => $data,
            'message' => 'Nomor Wallet Berhasil dibuat'
        ]);
    }

    public function updatePin(Request $request){
        $request->validate([
            'old_pin' => 'required|string',
            'pin' => 'required|string|min:6|confirmed',
        ]);
        $wallet = auth()->user()->wallet;

        if ($wallet) {
            if ($wallet->pin !== $request->old_pin) {
                return response()->json([
                    'message' => 'Pin lama salah'
                ], 400);
            }

            $wallet->pin = $request->pin;
            $wallet->save();

            return response()->json([
                'message' => 'PIN Berhasil di update'
            ], 200);
        }
    }

    public function delete(){
        auth()->user()->wallet->delete();
        return response()->json([
            'message' => 'Wallet Berhasil di hapus'
        ], 200);
    }
}
