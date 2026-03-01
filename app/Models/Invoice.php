<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\User;

class Invoice extends Model
{
    protected $fillable = [
        'student_id',
        'financial_account_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'notes',
        'terms',
        'created_by',
        'paid_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع الحساب المالي
     */
    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    /**
     * العلاقة مع عناصر الفاتورة
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ الفاتورة
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * تحديث حالة الفاتورة والمبالغ
     */
    public function updateStatus(): void
    {
        $this->paid_amount = $this->payments()->where('status', 'completed')->sum('amount');
        $this->remaining_amount = $this->total_amount - $this->paid_amount;

        if ($this->remaining_amount <= 0) {
            $this->status = 'paid';
            $this->paid_at = now();
        } elseif ($this->paid_amount > 0) {
            $this->status = 'partial';
        } elseif ($this->due_date < now() && $this->status != 'cancelled') {
            $this->status = 'overdue';
        } elseif ($this->status == 'draft') {
            // لا تغيير
        } else {
            $this->status = 'pending';
        }

        $this->save();

        // تحديث الحساب المالي
        if ($this->financialAccount) {
            $this->financialAccount->updateBalance();
        }
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'draft' => 'مسودة',
            'pending' => 'معلقة',
            'partial' => 'مدفوعة جزئياً',
            'paid' => 'مدفوعة',
            'overdue' => 'متأخرة',
            'cancelled' => 'ملغاة',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * التحقق من كون الفاتورة متأخرة
     */
    public function isOverdue(): bool
    {
        return $this->due_date < now() && $this->status != 'paid' && $this->status != 'cancelled';
    }
}
