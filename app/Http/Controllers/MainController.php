<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function me(){
        return response()->json([
            'data' => auth()->user()
        ]);
    }

    public function listUser(){
        $user = User::where('id','!=',auth()->user()->id)->orderBy('name','ASC')->limit(10)->get();
        return response()->json([
            'data' => $user
        ]);
    }

    public function userDetail(User $user){
        return response()->json([
            'data' => $user
        ]);
    }
}
