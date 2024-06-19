<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'photo',
        'phone_number',
        'pin',
        'username'
    ];

    protected $appends = ['balance','formatted','outbalance','inbalance'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Attribute
    public function getBalanceAttribute(){
        $transaction = $this->transactions->sum('amount');
        $mySender = Transfer::where('sender_id', $this->id)->sum('amount');
        $myReceiver = Transfer::where('receiver_id', $this->id)->sum('amount');
        return $transaction + ($mySender + $myReceiver);
    }

    public function getOutBalanceAttribute(){
        $transaction = $this->transactions->where('type','withdraw')->sum('amount');
        $mySender = Transfer::where('sender_id', $this->id)->sum('amount');
        return $transaction + $mySender;
    }
    
    public function getInBalanceAttribute(){
        $transaction = $this->transactions->where('type','topup')->sum('amount');
        $mySender = Transfer::where('receiver_id', $this->id)->sum('amount');
        return $transaction + $mySender;
    }

    public function getFormattedAttribute(){
        return number_format($this->balance, 0, '','.');
    }

    // Relation 
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function sentTransfers(){
        return $this->hasMany(Transfer::class);
    }

    public function receiveTransfers(){
        return $this->hasMany(Transfer::class);
    }

}
