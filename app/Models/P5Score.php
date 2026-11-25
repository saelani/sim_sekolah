<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Score extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_p5_scores';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'p5_subelement_id',
        'teacher_id',
        'score',
    ];

}
