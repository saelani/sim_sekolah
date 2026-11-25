<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'password_reset_tokens';
    public $timestamps = true;
    protected $fillable = [
        'email',
        'token',
    ];

}
