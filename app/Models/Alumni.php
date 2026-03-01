<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'graduation_date',
        'degree',
        'major',
        'current_job',
        'company',
        'address',
        'is_active',
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(AlumniDonation::class);
    }
}
