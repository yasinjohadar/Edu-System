<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmissionLink extends Model
{
    protected $fillable = [
        'submission_id',
        'url',
        'title',
        'description',
        'link_type',
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
     * الحصول على اسم نوع الرابط بالعربية
     */
    public function getLinkTypeNameAttribute(): string
    {
        return match($this->link_type) {
            'google_drive' => 'Google Drive',
            'dropbox' => 'Dropbox',
            'youtube' => 'YouTube',
            'onedrive' => 'OneDrive',
            'other' => 'رابط آخر',
            default => 'غير محدد',
        };
    }
}
