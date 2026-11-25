<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Option extends Model
{
    protected $table = 'cbt_options';
    public $timestamps = true;
    protected $fillable = [
        'cbt_question_id',
        'option_text',
        'media_path',
        'is_correct',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'cbt_question_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'cbt_option_id');
    }
}
