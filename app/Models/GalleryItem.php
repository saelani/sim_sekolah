<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_gallery_items';
    public $timestamps = true;
    protected $fillable = [
        'gallery_id',
        'type',
        'file_or_url',
        'caption',
    ];

    public function Gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
