<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'teacher_code',
        'date_of_birth',
        'gender',
        'address',
        'hire_date',
        'qualification',
        'specialization',
        'experience_years',
        'salary',
        'status',
        'notes',
        'photo',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * العلاقة مع User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع المواد (Many-to-Many)
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject', 'teacher_id', 'subject_id')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الفصول (كمعلم رئيسي)
     */
    public function sections(): HasMany
    {
        return $this->hasMany(Section::class, 'class_teacher_id');
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
     * العلاقة مع الواجبات
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
