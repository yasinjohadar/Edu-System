<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LectureAttendance extends Model
{
    protected $table = 'lecture_attendance';

    protected $fillable = [
        'lecture_id',
        'student_id',
        'status',
        'joined_at',
        'left_at',
        'duration_minutes',
        'notes',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    /**
     * العلاقة مع المحاضرة
     */
    public function lecture(): BelongsTo
    {
        return $this->belongsTo(OnlineLecture::class, 'lecture_id');
    }

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * الحصول على حالة الحضور بالعربية
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'present' => 'حاضر',
            'absent' => 'غائب',
            'late' => 'متأخر',
            'excused' => 'معذور',
            default => 'غير محدد',
        };
    }

    /**
     * الحصول على لون الحالة
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'excused' => 'info',
            default => 'secondary',
        };
    }
}
