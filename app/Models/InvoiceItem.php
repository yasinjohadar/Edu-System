<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'fee_type_id',
        'item_name',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'tax',
        'total',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع الفاتورة
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * العلاقة مع نوع الرسوم
     */
    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    /**
     * حساب الإجمالي تلقائياً
     */
    public function calculateTotal(): float
    {
        $subtotal = ($this->unit_price * $this->quantity) - $this->discount;
        $total = $subtotal + $this->tax;
        return round($total, 2);
    }
}
