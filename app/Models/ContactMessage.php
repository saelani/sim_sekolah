<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_contact_messages';
    public $timestamps = true;
    protected $fillable = [
        'sender_name',
        'email_or_phone',
        'subject',
        'message',
        'is_read',
    ];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
