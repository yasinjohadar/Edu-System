<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'invoice_id',
        'financial_account_id',
        'payment_number',
        'payment_date',
        'amount',
        'payment_method',
        'reference_number',
        'bank_name',
        'notes',
        'status',
        'received_by',
        'processed_at',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع الفاتورة
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * العلاقة مع الحساب المالي
     */
    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    /**
     * العلاقة مع المستخدم الذي استلم الدفعة
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * الحصول على اسم طريقة الدفع بالعربية
     */
    public function getPaymentMethodNameAttribute(): string
    {
        $methods = [
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'card' => 'بطاقة',
            'check' => 'شيك',
            'online' => 'دفع إلكتروني',
            'other' => 'أخرى',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'pending' => 'معلق',
            'completed' => 'مكتمل',
            'failed' => 'فاشل',
            'refunded' => 'مسترد',
        ];

        return $statuses[$this->status] ?? $this->status;
    }
}
