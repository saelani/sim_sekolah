<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavingsRequest extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'fin_savings_requests';
    public $timestamps = true;
    protected $fillable = [
        'student_id',
        'date',
        'type',
        'amount',
        'notes',
        'status',
        'approved_by',
        'approved_at',
    ];

    public function Student()
    {
        return $this->belongsTo(Student::class);
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
