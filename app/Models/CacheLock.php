<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheLock extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sys_cache_locks';
    public $timestamps = true;
    protected $fillable = [
        'key',
        'owner',
        'expiration',
    ];

}
