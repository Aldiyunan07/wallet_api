<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function history()
    {
        $transaction = Transaction::where('user_id', auth()->user()->id)->orderBy('created_at','DESC')->get();
        return response()->json([
            'data' => $transaction
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'amount' => 'required|numeric',
            'paymentMethod' => 'required',
            'paymentNumber' => 'required'
        ]);

        $data = $user->transactions()->create([
            'amount' => $request->amount,
            'paymentMethod' => $request->paymentMethod,
            'paymentNumber' => $request->paymentNumber,
            'type' => $request->type,
            'status' => 'success'
        ]);

        return response()->json([
            'data' => $data
        ]);
    }

    public function confirmation(Transaction $transaction, Request $request)
    {
        $request->validate([
            'pin' => 'required'
        ]);
        if ($request->pin !== auth()->user()->pin) {
            return response()->json([
                'message' => 'Pin salah'
            ]);
        }
        $transaction->update([
            'status' => 'success'
        ]);
        return response()->json([
            'data' => $transaction,
            'message' => 'Transaksi berhasil'
        ]);
    }

    public function detail(Transaction $transaction)
    {
        $transaction = Transaction::whereId($transaction->id)->first();
        return response()->json([
            'data' => $transaction
        ]);
    }
}
