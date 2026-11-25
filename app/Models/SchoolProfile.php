<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_school_profiles';
    public $timestamps = true;
    protected $fillable = [
        'npsn',
        'school_name',
        'headmaster_name',
        'accreditation',
        'address',
        'phone',
        'email',
        'website',
        'vision',
        'mission',
        'logo_path',
        'maps_embed',
    ];

}
