<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeExtracurricular extends Model
{
    /** @use HasFactory<\Database\Factories\GradeExtracurricularFactory> */
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'grade_extracurriculars'; // Sesuaikan jika nama tabel Anda berbeda

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'instructor_name',
        'teacher_id', // Tambahkan foreign key ini
    ];

    /**
     * Relasi ke model Teacher (Guru Pembina Ekskul)
     */
    public function teacher(): BelongsTo
    {
        // Sesuaikan 'Teacher::class' dengan nama namespace model Guru Anda jika berbeda (misal: App\Models\Teacher)
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}