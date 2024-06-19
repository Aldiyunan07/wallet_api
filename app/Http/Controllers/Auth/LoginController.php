<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'pin' => 'required'
        ]);
        $credentials = $request->only('phone_number', 'pin');

        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (!$user) {
            return response()->json(['message' => 'Phone number tidak terdaftar'], 404);
        }

        // Jika pin tidak sesuai
        if ($user->pin !== $credentials['pin']) {
            return response()->json(['message' => 'Pin salah'], 401);
        }

        if (!$user || $user->pin !== $credentials['pin']) {
            return response(null, 401);
        }

        $token = auth()->login($user);


        return response()->json([
            'token' => $token,
            'message' => 'Login Berhasil'
        ]);
    }
}
