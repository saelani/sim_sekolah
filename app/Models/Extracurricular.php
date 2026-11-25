<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_extracurriculars';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'instructor_name',
    ];

    public function ExtracurricularScore()
    {
        return $this->hasMany(ExtracurricularScore::class);
    }
}
