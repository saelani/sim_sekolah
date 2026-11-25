<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_subjects';
    public $timestamps = true;
    protected $fillable = [
        'schedule_id',
        'school_id',
        'assignment_id',
        'learning_objective_id',
        'code',
        'name',
        'category',
        'order_number',
    ];

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'schedule_id', 'id');
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
    public function learningObjective()
    {
        return $this->hasMany(LearningObjective::class, 'learning_objective_id', 'id');
    }
    public function p5Project()
    {
        return $this->hasMany(P5Project::class);
    }
    public function exam()
    {
        return $this->hasMany(Exam::class);
    }
    public function material()
    {
        return $this->hasMany(Material::class);
    }
    // TAMBAHKAN RELASI INI
    public function teacherSubjectClasses()
    {
        return $this->hasMany(TeacherSubjectClass::class, 'subject_id', 'id');
    }

    
}
