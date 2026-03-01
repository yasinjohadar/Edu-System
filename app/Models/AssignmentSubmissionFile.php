<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmissionFile extends Model
{
    protected $fillable = [
        'submission_id',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'file_category',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * العلاقة مع التسليم
     */
    public function submission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'submission_id');
    }

    /**
     * الحصول على حجم الملف بصيغة مقروءة
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'غير محدد';
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * الحصول على اسم فئة الملف بالعربية
     */
    public function getFileCategoryNameAttribute(): string
    {
        return match($this->file_category) {
            'answer' => 'إجابة',
            'attachment' => 'مرفق',
            'other' => 'أخرى',
            default => 'غير محدد',
        };
    }
}
