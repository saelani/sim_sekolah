<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_report_cards';
    public $timestamps = true;
    protected $fillable = [
        'enrollment_id',
        'sick',
        'permission',
        'unexcused',
        'extracurricular_notes',
        'teacher_notes',
        'height_weight',
        'health_notes',
        'is_promoted',
        'is_locked',
        'place_date_printed',
    ];

    public function Enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }
}
