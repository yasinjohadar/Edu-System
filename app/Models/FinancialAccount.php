<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialAccount extends Model
{
    protected $fillable = [
        'student_id',
        'account_number',
        'balance',
        'total_invoiced',
        'total_paid',
        'total_due',
        'last_transaction_date',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_invoiced' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'total_due' => 'decimal:2',
        'last_transaction_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * تحديث رصيد الحساب
     */
    public function updateBalance(): void
    {
        $this->total_invoiced = $this->invoices()->sum('total_amount');
        $this->total_paid = $this->payments()->where('status', 'completed')->sum('amount');
        $this->total_due = $this->total_invoiced - $this->total_paid;
        $this->balance = $this->total_paid - $this->total_invoiced;
        $this->last_transaction_date = now();
        $this->save();
    }
}
