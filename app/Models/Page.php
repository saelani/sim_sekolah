<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_pages';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'is_published',
    ];

}
