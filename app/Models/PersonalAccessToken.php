<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'personal_access_tokens';
    public $timestamps = true;
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'expires_at',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
