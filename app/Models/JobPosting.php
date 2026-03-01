<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPosting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'company',
        'location',
        'salary_range',
        'employment_type',
        'application_deadline',
        'contact_email',
        'contact_phone',
        'is_active',
        'posted_by',
    ];

    protected $casts = [
        'application_deadline' => 'date',
        'is_active' => 'boolean',
    ];

    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }
}
