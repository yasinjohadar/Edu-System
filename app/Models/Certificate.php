<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'template_id',
        'student_id',
        'certificate_number',
        'verification_code',
        'type',
        'issue_date',
        'data',
        'file_path',
        'is_verified',
        'issued_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'data' => 'array',
        'is_verified' => 'boolean',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }
}
