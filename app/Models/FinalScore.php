<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalScore extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_final_scores';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'subject_id',
        'formative_avg',
        'sumative_slm_avg',
        'sumative_sas',
        'final_score',
        'highest_achieved_description',
        'lowest_achieved_description',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
