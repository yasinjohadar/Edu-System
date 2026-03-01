<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hostel extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'total_rooms',
        'total_beds',
        'description',
        'gender',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function accommodations(): HasMany
    {
        return $this->hasMany(StudentAccommodation::class);
    }
}
