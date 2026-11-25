<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlider extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_hero_sliders';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'subtitle',
        'image_path',
        'button_text',
        'button_url',
        'order_number',
        'is_active',
    ];

}
