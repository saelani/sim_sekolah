<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $table = 'cbt_exams';

    public $timestamps = true;

    protected $fillable = [
        'assessment_type',
        'subject_id',
        'learning_objective_id',
        'sumative_scope_id',
        'class_room_id',
        'teacher_id',
        'title',
        'duration_minutes',
        'token',
        'randomize_questions',
        'randomize_options',
        'show_result',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time'        => 'datetime',
            'end_time'          => 'datetime',
            'randomize_questions' => 'boolean',
            'randomize_options'   => 'boolean',
            'show_result'       => 'boolean',
            'is_active'         => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function examSession(): HasMany
    {
        return $this->hasMany(ExamSession::class, 'cbt_exam_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_room_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'cbt_exam_id');
    }
}