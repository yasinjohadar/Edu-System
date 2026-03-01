<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeType extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'code',
        'description',
        'category',
        'default_amount',
        'is_recurring',
        'recurring_period',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع عناصر الفواتير
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * الحصول على اسم الفئة بالعربية
     */
    public function getCategoryNameAttribute(): string
    {
        $categories = [
            'tuition' => 'رسوم دراسية',
            'registration' => 'رسوم تسجيل',
            'activity' => 'رسوم نشاطات',
            'book' => 'رسوم كتب',
            'uniform' => 'رسوم زي موحد',
            'transport' => 'رسوم مواصلات',
            'other' => 'أخرى',
        ];

        return $categories[$this->category] ?? $this->category;
    }
}
