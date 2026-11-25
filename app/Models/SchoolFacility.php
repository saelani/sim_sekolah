<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFacility extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_school_facilities';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'image_path',
    ];

}
