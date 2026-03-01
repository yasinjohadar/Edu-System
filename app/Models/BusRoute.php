<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BusRoute extends Model
{
    protected $fillable = [
        'route_name',
        'route_number',
        'description',
        'distance',
        'start_time',
        'end_time',
        'fee',
        'is_active',
    ];

    protected $casts = [
        'distance' => 'decimal:2',
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function stops(): HasMany
    {
        return $this->hasMany(BusStop::class, 'route_id');
    }

    public function studentTransports(): HasMany
    {
        return $this->hasMany(StudentTransport::class, 'route_id');
    }
}
