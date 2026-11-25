<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sumatif extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_sumatifs';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'subject_id',
        'sumative_scope_id',
        'type',
        'score',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function sumativeScope()
    {
        return $this->belongsTo(SumativeScope::class, 'sumative_scope_id');
    }
}
