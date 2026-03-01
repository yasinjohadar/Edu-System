<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    protected $fillable = [
        'user_id',
        'driver_code',
        'license_number',
        'license_expiry',
        'phone',
        'address',
        'status',
    ];

    protected $casts = [
        'license_expiry' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studentTransports(): HasMany
    {
        return $this->hasMany(StudentTransport::class, 'driver_id');
    }
}
