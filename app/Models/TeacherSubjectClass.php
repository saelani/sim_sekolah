<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSubjectClass extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_teacher_subject_classes';
    public $timestamps = true;
    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_id',
        'academic_year_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    // PERBAIKAN DI SINI: Tambahkan parameter foreign key 'class_id'
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}