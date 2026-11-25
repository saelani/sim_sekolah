<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'ops_cash_flows';
    public $timestamps = true;
    protected $fillable = [
        'academic_year_id',
        'class_id',
        'user_id',
        'date',
        'type',
        'amount',
        'description',
    ];

    public function AcademicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
    public function SchoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
