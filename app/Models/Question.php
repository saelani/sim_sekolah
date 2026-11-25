<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $table = 'cbt_questions';
    public $timestamps = true;
    protected $fillable = [
        'cbt_exam_id',
        'type',
        'question_text',
        'media_path',
        'score_weight',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'cbt_exam_id');
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class, 'cbt_question_id');
    }
}
