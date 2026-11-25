<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtracurricularScore extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_extracurricular_scores';
    
    public $timestamps = true;

    protected $fillable = [
        'enrollment_id',
        'extracurricular_id', // Pastikan kolom foreign key di tabel database sesuai (misal: grade_extracurricular_id atau extracurricular_id)
        'grade',
        'description',
    ];

    /**
     * Relasi ke tabel Enrollment (Siswa)
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Relasi ke tabel GradeExtracurricular (Ekstrakurikuler)
     */
    public function extracurricular(): BelongsTo
    {
        // Hubungkan ke model GradeExtracurricular
        // Jika nama foreign key di database adalah 'grade_extracurricular_id', 
        // ubah parameter kedua menjadi 'grade_extracurricular_id'
        return $this->belongsTo(GradeExtracurricular::class, 'extracurricular_id');
    }
}