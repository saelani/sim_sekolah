<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formatif extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_formatifs';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'learning_objective_id',
        'type',
        'score',
        'is_achieved',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function learningObjective()
    {
        return $this->belongsTo(LearningObjective::class);
    }
}
