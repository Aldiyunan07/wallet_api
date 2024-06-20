<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status'
    ];

    protected $appends = ['date','time'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function getDateAttribute(){
        return $this->created_at->format('d, F Y');
    }

    public function getTimeAttribute(){
        return $this->created_at->format('H:i');
    }
}
