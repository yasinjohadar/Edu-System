<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LectureMaterial extends Model
{
    protected $fillable = [
        'lecture_id',
        'title',
        'description',
        'type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'external_url',
        'download_count',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'download_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع المحاضرة
     */
    public function lecture(): BelongsTo
    {
        return $this->belongsTo(OnlineLecture::class, 'lecture_id');
    }

    /**
     * زيادة عدد التحميلات
     */
    public function incrementDownloads(): void
    {
        $this->increment('download_count');
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
}
