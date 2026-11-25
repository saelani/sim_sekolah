<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'sessions';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'payload',
        'last_activity',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
