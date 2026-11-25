<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_downloads';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'file_path',
        'file_size',
        'download_count',
    ];

}
