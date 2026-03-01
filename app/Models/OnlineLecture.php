<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineLecture extends Model
{
    protected $fillable = [
        'subject_id',
        'section_id',
        'teacher_id',
        'title',
        'description',
        'content',
        'type',
        'video_url',
        'audio_url',
        'scheduled_at',
        'started_at',
        'ended_at',
        'duration',
        'meeting_link',
        'meeting_id',
        'meeting_password',
        'is_published',
        'is_active',
        'views_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'duration' => 'integer',
        'is_published' => 'boolean',
        'is_active' => 'boolean',
        'views_count' => 'integer',
    ];

    /**
     * العلاقة مع المادة
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * العلاقة مع الفصل
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * العلاقة مع المعلم
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * العلاقة مع المواد
     */
    public function materials(): HasMany
    {
        return $this->hasMany(LectureMaterial::class, 'lecture_id')->orderBy('sort_order');
    }

    /**
     * العلاقة مع الحضور
     */
    public function attendance(): HasMany
    {
        return $this->hasMany(LectureAttendance::class, 'lecture_id');
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * التحقق من كون المحاضرة مباشرة
     */
    public function isLive(): bool
    {
        return $this->type === 'live' && 
               $this->scheduled_at && 
               $this->scheduled_at->isFuture();
    }

    /**
     * التحقق من كون المحاضرة منتهية
     */
    public function isEnded(): bool
    {
        return $this->ended_at !== null;
    }
}
