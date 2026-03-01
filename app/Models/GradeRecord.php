<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeRecord extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'exam_type',
        'exam_name',
        'marks_obtained',
        'total_marks',
        'percentage',
        'grade',
        'exam_date',
        'academic_year',
        'semester',
        'notes',
        'is_published',
    ];

    protected $casts = [
        'marks_obtained' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'percentage' => 'decimal:2',
        'exam_date' => 'date',
        'is_published' => 'boolean',
    ];

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع المادة الدراسية
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * العلاقة مع المعلم
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * حساب النسبة المئوية تلقائياً
     */
    public function calculatePercentage(): float
    {
        if ($this->total_marks > 0) {
            return round(($this->marks_obtained / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    /**
     * حساب الدرجة الحرفية
     */
    public function calculateGrade(): string
    {
        $percentage = $this->percentage ?? $this->calculatePercentage();
        
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 85) return 'A';
        if ($percentage >= 80) return 'B+';
        if ($percentage >= 75) return 'B';
        if ($percentage >= 70) return 'C+';
        if ($percentage >= 65) return 'C';
        if ($percentage >= 60) return 'D+';
        if ($percentage >= 50) return 'D';
        return 'F';
    }

    /**
     * الحصول على اسم نوع التقييم بالعربية
     */
    public function getExamTypeNameAttribute(): string
    {
        $types = [
            'quiz' => 'اختبار قصير',
            'assignment' => 'واجب',
            'midterm' => 'امتحان نصفي',
            'final' => 'امتحان نهائي',
            'project' => 'مشروع',
            'participation' => 'مشاركة',
            'homework' => 'واجب منزلي',
            'other' => 'أخرى',
        ];

        return $types[$this->exam_type] ?? $this->exam_type;
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
