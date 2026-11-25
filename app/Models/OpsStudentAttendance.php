<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;       // Pastikan model di-import
use App\Models\SchoolClass;   // Pastikan model di-import
use App\Models\Teacher;       // Pastikan model di-import

class OpsStudentAttendance extends Model
{
    /** @use HasFactory<\Database\Factories\OpsStudentAttendanceFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class_id',
        'teacher_id',
        'student_id',
        'user_id',
        'date',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    public function student()                                     
      {                                                                                                                               
          return $this->belongsTo(Student::class, 'student_id');
      }                                                                                                                               
      public function class()                                   
      {
          return $this->belongsTo(SchoolClass::class, 'class_id');
      }                                                                                                                               
      public function teacher()                                     
      {
          return $this->belongsTo(Teacher::class, 'teacher_id');
      }       
}
