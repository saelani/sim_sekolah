<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'acad_academic_years';
    public $timestamps = true;
    protected $fillable = [
        'year',
        'semester',
        'is_active',
    ];

    public function SchoolClass()
    {
        return $this->hasMany(SchoolClass::class);
    }
}
