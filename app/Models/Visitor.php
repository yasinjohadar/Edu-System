<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitor extends Model
{
    protected $fillable = [
        'student_id',
        'visitor_name',
        'relationship',
        'phone',
        'id_number',
        'visit_date',
        'check_in_time',
        'check_out_time',
        'purpose',
        'registered_by',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
