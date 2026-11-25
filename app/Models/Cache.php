<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sys_cache';
    public $timestamps = true;
    protected $fillable = [
        'key',
        'value',
        'expiration',
    ];

}
