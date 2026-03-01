<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Report extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
        'filters',
        'data',
        'format',
        'file_path',
        'status',
        'created_by',
        'generated_at',
    ];

    protected $casts = [
        'filters' => 'array',
        'data' => 'array',
        'generated_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم الذي أنشأ التقرير
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        $statuses = [
            'pending' => 'قيد الانتظار',
            'generating' => 'قيد الإنشاء',
            'completed' => 'مكتمل',
            'failed' => 'فشل',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * الحصول على اسم النوع بالعربية
     */
    public function getTypeNameAttribute(): string
    {
        $types = [
            'student_performance' => 'أداء الطلاب',
            'class_performance' => 'أداء الفصول',
            'teacher_performance' => 'أداء المعلمين',
            'attendance' => 'الحضور والغياب',
            'financial' => 'التقارير المالية',
            'library' => 'المكتبة',
            'exams' => 'الاختبارات',
            'assignments' => 'الواجبات',
            'grades' => 'الدرجات',
        ];

        return $types[$this->type] ?? $this->type;
    }
}
