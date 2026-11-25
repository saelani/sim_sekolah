<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ops_attendance_requests';
    public $timestamps = true;
    protected $fillable = [
        'class_id',
        'student_id',
        'teacher_id',
        'date',
        'status',
        'notes',
        'attachment_path',
        'approval_status',
        'approved_by',
        'approved_at',
    ];

    public function SchoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    public function Student()
    {
        return $this->belongsTo(Student::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
