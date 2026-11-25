<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5Project extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_p5_projects';
    public $timestamps = true;
    protected $fillable = [
        'teacher_id',
        'academic_year_id',
        'phase',
        'theme',
        'title',
        'description',
    ];

    public function Teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function AcademicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function P5SubElement()
    {
        return $this->hasMany(P5SubElement::class);
    }
}
