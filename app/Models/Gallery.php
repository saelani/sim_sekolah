<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_galleries';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'slug',
        'description',
    ];

    public function GalleryItem()
    {
        return $this->hasMany(GalleryItem::class);
    }
}
