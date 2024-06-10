<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'phone_number',
        'balance',
        'pin'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function transaction(){
        return $this->hasMany(Transaction::class);
    }

    public function sentTransfers()
    {
        return $this->hasMany(Transfer::class, 'sender_id');
    }

    public function receivedTransfers()
    {
        return $this->hasMany(Transfer::class, 'receiver_id');
    }
}
