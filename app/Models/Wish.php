<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wish extends Model
{
    use HasFactory;

    protected $fillable = [
        'greeting_id',
        'sender_name',
        'message',
        'gift_choice',
    ];

    public function greeting()
    {
        return $this->belongsTo(Greeting::class);
    }
}
