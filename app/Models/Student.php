<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'student_code',
        'date_of_birth',
        'gender',
        'address',
        'enrollment_date',
        'status',
        'class_id',
        'section_id',
        'parent_guardian',
        'emergency_contact',
        'medical_notes',
        'photo',
        'birth_certificate',
        'health_certificate',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
    ];

    /**
     * العلاقة مع User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع أولياء الأمور (Many-to-Many)
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentModel::class, 'parent_student', 'student_id', 'parent_id')
            ->withPivot('relationship_type', 'is_primary')
            ->withTimestamps();
    }

    /**
     * العلاقة مع الصف (Class)
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    /**
     * العلاقة مع الفصل (Section)
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * العلاقة مع الحضور
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * العلاقة مع الدرجات
     */
    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    /**
     * العلاقة مع الحساب المالي
     */
    public function financialAccount(): HasOne
    {
        return $this->hasOne(FinancialAccount::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * العلاقة مع استعارات الكتب
     */
    public function bookBorrowings(): HasMany
    {
        return $this->hasMany(BookBorrowing::class);
    }

    /**
     * العلاقة مع الغرامات
     */
    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class);
    }

    /**
     * العلاقة مع حضور المحاضرات
     */
    public function lectureAttendance(): HasMany
    {
        return $this->hasMany(LectureAttendance::class);
    }

    /**
     * العلاقة مع تسليمات الواجبات
     */
    public function assignmentSubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
