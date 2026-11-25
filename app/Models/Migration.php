<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Migration extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'migrations';
    public $timestamps = true;
    protected $fillable = [
        'migration',
        'batch',
    ];

}
