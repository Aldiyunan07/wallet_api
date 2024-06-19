<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class registerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone_number' => 'required',
            'pin' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'phone_number' => $request->phone_number,
            'pin' => $request->pin 
        ]);

        return response()->json([
            'data' => $user,
            'message' => 'registrasi Berhasil'
        ]);
    }
}
