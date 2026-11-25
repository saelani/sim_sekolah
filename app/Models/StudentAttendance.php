<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ops_student_attendances';
    public $timestamps = true;
    protected $fillable = [
        'class_id',
        'teacher_id',
        'student_id',
        'user_id',
        'date',
        'status',
        'notes',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
