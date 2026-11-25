<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_academic_calendars';
    public $timestamps = true;
    
    protected $fillable = [
        'class_id',
        'subject_id',
        'class_room_id',
        'learning_objective_id',
        'start_date',
        'end_date',
        'activity_type',
        'title',
        'notes',
    ];

    /**
     * Relasi ke Model LearningObjective
     */
    public function learningObjective()
    {
        return $this->belongsTo(LearningObjective::class, 'learning_objective_id');
    }

    /**
     * Relasi ke Model Subject (Mata Pelajaran)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * ⚠️ TAMBAHKAN INI: Relasi ke Model SchoolClass berdasarkan kolom class_id
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}