<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $data = auth()->user()->balance;
        return response()->json([
            'data' => $data
        ]);
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'old_pin' => 'required|string',
            'pin' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if ($user->pin !== $request->old_pin) {
            return response()->json([
                'message' => 'Pin lama salah'
            ], 400);
        }

        $user->pin = $request->pin;
        $user->save();

        return response()->json([
            'message' => 'PIN Berhasil di update'
        ], 200);
    }
}
