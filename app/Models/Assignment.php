<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_assignments';
    public $timestamps = true;
    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'due_date',
    ];

    public function SchoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    public function Subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function Teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
