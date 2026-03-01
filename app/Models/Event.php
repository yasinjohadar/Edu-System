<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Event extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'type',
        'recurrence',
        'recurrence_end_date',
        'is_all_day',
        'is_active',
        'target_audience',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'recurrence_end_date' => 'date',
        'is_all_day' => 'boolean',
        'is_active' => 'boolean',
        'target_audience' => 'array',
    ];

    /**
     * العلاقة مع فئة الحدث
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ الحدث
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * الحصول على اسم النوع بالعربية
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            'holiday' => 'عطلة',
            'exam' => 'امتحان',
            'activity' => 'نشاط',
            'meeting' => 'اجتماع',
            'other' => 'أخرى',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * الحصول على اسم التكرار بالعربية
     */
    public function getRecurrenceNameAttribute(): string
    {
        $recurrences = [
            'none' => 'لا يوجد',
            'daily' => 'يومي',
            'weekly' => 'أسبوعي',
            'monthly' => 'شهري',
            'yearly' => 'سنوي',
        ];

        return $recurrences[$this->recurrence] ?? $this->recurrence;
    }
}
