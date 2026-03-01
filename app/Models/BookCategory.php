<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCategory extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'description',
        'code',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الكتب
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    /**
     * العلاقة مع التصنيفات الفرعية
     */
    public function children(): HasMany
    {
        return $this->hasMany(BookCategory::class, 'parent_id');
    }

    /**
     * العلاقة مع التصنيف الأب
     */
    public function parent()
    {
        return $this->belongsTo(BookCategory::class, 'parent_id');
    }
}
