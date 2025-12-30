<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'theme',
        'font_family',
        'intro_title',
        'intro_subtitle',
        'cake_title',
        'cake_subtitle',
        'album_title',
        'album_subtitle',
        'final_title',
        'final_subtitle',
        'video_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function greetings()
    {
        return $this->hasMany(Greeting::class);
    }

    public static function fontOptions(): array
    {
        return [
            'Playfair Display' => 'Playfair+Display:wght@400;600;700',
            'Cinzel' => 'Cinzel:wght@400;600;700',
            'Cormorant Garamond' => 'Cormorant+Garamond:wght@400;600;700',
            'DM Serif Display' => 'DM+Serif+Display',
            'Prata' => 'Prata',
            'Lora' => 'Lora:wght@400;600;700',
            'Merriweather' => 'Merriweather:wght@400;700',
            'Space Grotesk' => 'Space+Grotesk:wght@400;500;600',
        ];
    }

    public static function fontUrl(string $fontFamily): ?string
    {
        $options = self::fontOptions();
        if (!isset($options[$fontFamily])) {
            return null;
        }

        return 'https://fonts.googleapis.com/css2?family=' . $options[$fontFamily] . '&display=swap';
    }
}
