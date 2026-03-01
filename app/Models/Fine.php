<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fine extends Model
{
    protected $fillable = [
        'borrowing_id',
        'student_id',
        'fine_number',
        'amount',
        'type',
        'reason',
        'status',
        'due_date',
        'paid_date',
        'notes',
        'created_by',
        'paid_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * العلاقة مع الاستعارة
     */
    public function borrowing(): BelongsTo
    {
        return $this->belongsTo(BookBorrowing::class);
    }

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ الغرامة
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع المستخدم الذي دفع الغرامة
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * الحصول على اسم النوع بالعربية
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            'late_return' => 'تأخير في الإرجاع',
            'damaged' => 'تلف الكتاب',
            'lost' => 'فقدان الكتاب',
        ];

        return $types[$this->type] ?? $this->type;
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'pending' => 'معلقة',
            'paid' => 'مدفوعة',
            'waived' => 'معفاة',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * توليد رقم الغرامة
     */
    public static function generateFineNumber(): string
    {
        $lastFine = self::latest('id')->first();
        $number = $lastFine ? $lastFine->id + 1 : 1;
        return 'FNE-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
