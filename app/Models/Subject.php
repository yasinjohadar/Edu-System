<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'code',
        'type',
        'weekly_hours',
        'full_marks',
        'pass_marks',
        'is_active',
        'description',
    ];

    protected $casts = [
        'full_marks' => 'decimal:2',
        'pass_marks' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الصفوف (Many-to-Many)
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(ClassModel::class, 'class_subject', 'subject_id', 'class_id')
            ->withPivot('weekly_hours')
            ->withTimestamps();
    }

    /**
     * العلاقة مع المعلمين (Many-to-Many)
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject', 'subject_id', 'teacher_id')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الجدول الدراسي
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * العلاقة مع الدرجات
     */
    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    /**
     * العلاقة مع المحاضرات الإلكترونية
     */
    public function onlineLectures(): HasMany
    {
        return $this->hasMany(OnlineLecture::class);
    }

    /**
     * العلاقة مع الواجبات
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
