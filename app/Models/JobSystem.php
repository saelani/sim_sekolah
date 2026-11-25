<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSystem extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sys_jobs';
    public $timestamps = true;
    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
    ];

}
