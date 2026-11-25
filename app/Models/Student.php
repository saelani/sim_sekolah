<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_students';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'class_id',
        'nis',
        'nisn',
        'name',
        'gender',
        'religion',
        'pob',
        'dob',
        'address',
        'parent_name',
        'photo_path',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
}
