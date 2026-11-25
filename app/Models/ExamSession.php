<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'cbt_exam_sessions';
    public $timestamps = true;
    protected $fillable = [
        'cbt_exam_id',
        'enrollment_id',
        'student_id',
        'start_time',
        'max_end_time',
        'submitted_at',
        'status',
        'score',
        'total_score',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'cbt_exam_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
