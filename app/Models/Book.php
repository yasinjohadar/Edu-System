<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'isbn',
        'title',
        'title_en',
        'author',
        'publisher',
        'publication_year',
        'language',
        'total_copies',
        'available_copies',
        'description',
        'cover_image',
        'location',
        'price',
        'pages',
        'edition',
        'is_active',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'total_copies' => 'integer',
        'available_copies' => 'integer',
        'price' => 'decimal:2',
        'pages' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع التصنيف
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * العلاقة مع الاستعارات
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(BookBorrowing::class);
    }

    /**
     * التحقق من توفر الكتاب
     */
    public function isAvailable(): bool
    {
        return $this->is_active && $this->available_copies > 0;
    }

    /**
     * تحديث عدد النسخ المتاحة
     */
    public function updateAvailableCopies(): void
    {
        $borrowedCount = $this->borrowings()
            ->whereIn('status', ['borrowed', 'overdue'])
            ->count();
        
        $this->available_copies = max(0, $this->total_copies - $borrowedCount);
        $this->save();
    }
}
