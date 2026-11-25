<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'cbt_student_answers';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'student_id',
        'cbt_exam_session_id',
        'cbt_question_id',
        'cbt_option_id',
        'answer_text',
        'essay_answer',
        'is_correct',
        'score_given',
    ];

    public function Student()
    {
        return $this->belongsTo(Student::class);
    }
    public function Exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
