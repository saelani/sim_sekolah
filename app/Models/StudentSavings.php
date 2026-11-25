<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSavings extends Model
{
    protected $table = 'ops_student_savings';
    public $timestamps = true;
    
    protected $fillable = [
        'student_id',
        'user_id',
        'date',
        'type',
        'amount',
        'description',
    ];

    // Relasi ke tabel students (menggunakan student_id)
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // Relasi ke tabel users / petugas pencatat (menggunakan user_id)
    // Ini diperlukan agar 'recorder.name' di tabel Filament terbaca dengan benar
    public function recorder()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}