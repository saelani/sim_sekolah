<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_classes';
    public $timestamps = true;
    protected $fillable = [
        'academic_year_id',
        'student_id',
        'name',
        'phase',
        'level',
        'teacher_id',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
    public function student()
    {
        return $this->hasMany(Student::class, 'student_id');
    }

    public function homeroomTeacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
