<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class BookBorrowing extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'borrowing_number',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'notes',
        'borrowed_by',
        'returned_by',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * العلاقة مع الكتاب
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع المستخدم الذي استعار الكتاب
     */
    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'borrowed_by');
    }

    /**
     * العلاقة مع المستخدم الذي استرجع الكتاب
     */
    public function returner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    /**
     * العلاقة مع الغرامة
     */
    public function fine(): HasOne
    {
        return $this->hasOne(Fine::class, 'borrowing_id');
    }

    /**
     * التحقق من كون الاستعارة متأخرة
     */
    public function isOverdue(): bool
    {
        return ($this->status === 'borrowed' || $this->status === 'overdue') && 
               $this->due_date < Carbon::today() && 
               $this->return_date === null;
    }

    /**
     * حساب عدد الأيام المتأخرة
     */
    public function getDaysOverdueAttribute(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        
        return Carbon::today()->diffInDays($this->due_date);
    }

    /**
     * توليد رقم الاستعارة
     */
    public static function generateBorrowingNumber(): string
    {
        $lastBorrowing = self::latest('id')->first();
        $number = $lastBorrowing ? $lastBorrowing->id + 1 : 1;
        return 'BRW-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
