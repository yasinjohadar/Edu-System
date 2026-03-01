<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'min_age',
        'max_age',
        'fees',
        'order',
        'is_active',
        'description',
    ];

    protected $casts = [
        'fees' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الصفوف
     */
    public function classes(): HasMany
    {
        return $this->hasMany(ClassModel::class, 'grade_id');
    }
}
