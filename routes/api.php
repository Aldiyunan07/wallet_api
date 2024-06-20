<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\registerController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\WalletController;

Route::post('/login', [LoginController::class,'__invoke']);
Route::post('/register', [registerController::class,'__invoke']);
Route::get('/check',[CheckController::class,'__invoke']);
Route::middleware('auth:api')->group(function(){
  Route::post('/logout', [LogoutController::class,'__invoke']);
  Route::get('/users',[MainController::class,'listUser']);
  Route::get('/user/{user}',[MainController::class,'userDetail']);
  Route::get('/me', [MainController::class,'me']);

  Route::prefix('wallet')->group(function(){
    Route::get('/my-balance',[WalletController::class,'index']);
    Route::post('/update-pin',[WalletController::class,'updatePin']);
  });
  
  Route::prefix('transaction')->group(function(){
    Route::post('/',[TransactionController::class,'store']);
    Route::get('/history', [TransactionController::class, 'history']);

  });

  Route::prefix('transfer')->group(function(){
    Route::post('/',[TransferController::class,'store']);
    Route::post('/confirmation/{transfer}',[TransferController::class,'confirmation']);
    Route::get('/history',[TransferController::class,'history']);
  });
});