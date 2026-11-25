<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolFeedback extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'school_feedbacks';
    public $timestamps = true;
    protected $fillable = [
        'student_id',
        'date',
        'category',
        'subject',
        'content',
        'is_anonymous',
        'status',
        'response_notes',
        'responded_by',
        'responded_at',
    ];

    public function Student()
    {
        return $this->belongsTo(Student::class);
    }
}
