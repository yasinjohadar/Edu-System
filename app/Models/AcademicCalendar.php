<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class AcademicCalendar extends Model
{
    protected $fillable = [
        'academic_year',
        'semester',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'exams_start',
        'exams_end',
        'results_publish_date',
        'holidays',
        'notes',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'exams_start' => 'date',
        'exams_end' => 'date',
        'results_publish_date' => 'date',
        'holidays' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع المستخدم الذي أنشأ التقويم
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * الحصول على اسم الفصل الدراسي بالعربية
     */
    public function getSemesterNameAttribute(): string
    {
        $semesters = [
            'first' => 'الفصل الأول',
            'second' => 'الفصل الثاني',
            'summer' => 'الفصل الصيفي',
        ];

        return $semesters[$this->semester] ?? $this->semester;
    }
}
