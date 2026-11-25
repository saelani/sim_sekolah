<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningObjective extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_learning_objectives';
    public $timestamps = true;
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'sumative_scope_id',
        'phase',
        'level',
        'semester',
        'code',
        'description',
        'class_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function sumativeScope()
    {
        return $this->belongsTo(SumativeScope::class, 'sumative_scope_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'learning_objective_id');
    }
}
