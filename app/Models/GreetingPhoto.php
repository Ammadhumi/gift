<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreetingPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'greeting_id',
        'path',
        'sort_order',
    ];

    public function greeting()
    {
        return $this->belongsTo(Greeting::class);
    }
}
