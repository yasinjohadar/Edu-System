<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    protected $fillable = [
        'class_id',
        'name',
        'name_en',
        'capacity',
        'current_students',
        'class_teacher_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * العلاقة مع الصف
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * العلاقة مع المعلم الرئيسي
     */
    public function classTeacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'class_teacher_id');
    }

    /**
     * العلاقة مع الطلاب
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * العلاقة مع الحضور
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * العلاقة مع الجدول الدراسي
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * العلاقة مع الواجبات
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }
}
