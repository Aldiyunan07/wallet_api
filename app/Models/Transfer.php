<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['formatted','date','time'];

    public function sender(){
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class,'receiver_id');
    }

    public function getFormattedAttribute(){
        return number_format(($this->amount), 0, '', '.');
    }

    public function getDateAttribute(){
        return $this->created_at->format('d F, Y');
    }
    
    public function getTimeAttribute(){
        return $this->created_at->format('H:i');
    }
}
