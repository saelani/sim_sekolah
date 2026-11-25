<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolAchievement extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'web_school_achievements';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'category',
        'level',
        'winner_name',
        'achievement_date',
        'image_path',
    ];

}
