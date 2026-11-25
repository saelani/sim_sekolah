<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class P5SubElement extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'grade_p5_subelements';
    public $timestamps = true;
    protected $fillable = [
        'p5_project_id',
        'teacher_id',
        'dimension',
        'element',
        'subelement_name',
        'target_narrative',
    ];

    public function P5Project()
    {
        return $this->belongsTo(P5Project::class);
    }
}
