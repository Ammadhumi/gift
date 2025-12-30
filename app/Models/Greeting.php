<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Greeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'template_id',
        'recipient_name',
        'occasion',
        'style',
        'message',
        'qr_path',
    ];

    protected static function booted()
    {
        static::creating(function (self $greeting) {
            if (empty($greeting->uuid)) {
                $greeting->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function wishes()
    {
        return $this->hasMany(Wish::class);
    }

    public function photos()
    {
        return $this->hasMany(GreetingPhoto::class)->orderBy('sort_order');
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
