<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_teachers';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'nip',
        'nuptk',
        'name',
        'front_title',
        'back_title',
        'gender',
        'photo_path',
        'role_type',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function assignment()
    {
        return $this->hasMany(Assignment::class);
    }
    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }
    public function teacherSubjectClass()
    {
        return $this->hasMany(TeacherSubjectClass::class);
    }
}
