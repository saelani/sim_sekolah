<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SumativeScope extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_sumative_scopes';
    public $timestamps = true;
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'phase',
        'semester',
        'name',
        'class_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
