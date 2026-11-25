<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_schedules';
    public $timestamps = true;
    protected $fillable = [
        'class_id',
        'subject_id',
        'day_name',
        'start_time',
        'end_time',
        'room_name',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
