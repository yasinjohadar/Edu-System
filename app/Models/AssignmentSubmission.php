<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'submission_number',
        'attempt_number',
        'is_resubmission',
        'previous_submission_id',
        'submitted_at',
        'status',
        'marks_obtained',
        'feedback',
        'teacher_notes',
        'student_notes',
        'requires_resubmission',
        'resubmission_reason',
        'graded_at',
        'graded_by',
        'is_late',
        'days_late',
        'late_penalty',
    ];

    protected $casts = [
        'attempt_number' => 'integer',
        'is_resubmission' => 'boolean',
        'submitted_at' => 'datetime',
        'marks_obtained' => 'decimal:2',
        'requires_resubmission' => 'boolean',
        'graded_at' => 'datetime',
        'is_late' => 'boolean',
        'days_late' => 'integer',
        'late_penalty' => 'decimal:2',
    ];

    /**
     * العلاقة مع الواجب
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    /**
     * العلاقة مع الطالب
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * العلاقة مع المعلم الذي صحح
     */
    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    /**
     * العلاقة مع التسليم السابق
     */
    public function previousSubmission(): BelongsTo
    {
        return $this->belongsTo(AssignmentSubmission::class, 'previous_submission_id');
    }

    /**
     * العلاقة مع التسليمات التالية (إعادة التسليم)
     */
    public function resubmissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'previous_submission_id');
    }

    /**
     * العلاقة مع الملفات
     */
    public function files(): HasMany
    {
        return $this->hasMany(AssignmentSubmissionFile::class, 'submission_id')->orderBy('sort_order');
    }

    /**
     * العلاقة مع النصوص
     */
    public function texts(): HasMany
    {
        return $this->hasMany(AssignmentSubmissionText::class, 'submission_id')->orderBy('sort_order');
    }

    /**
     * العلاقة مع الروابط
     */
    public function links(): HasMany
    {
        return $this->hasMany(AssignmentSubmissionLink::class, 'submission_id')->orderBy('sort_order');
    }

    /**
     * التحقق من إمكانية إعادة التسليم
     */
    public function canResubmit(): bool
    {
        if (!$this->assignment->allow_resubmission) {
            return false;
        }

        if ($this->requires_resubmission) {
            // التحقق من موعد إعادة التسليم
            if ($this->assignment->resubmission_deadline) {
                return Carbon::now()->lte($this->assignment->resubmission_deadline);
            }
            return true;
        }

        return false;
    }

    /**
     * الحصول على حجم الملفات الكلي
     */
    public function getTotalFilesSize(): int
    {
        return $this->files()->sum('file_size') ?? 0;
    }

    /**
     * التحقق من وجود محتوى
     */
    public function hasContent(): bool
    {
        return $this->files()->count() > 0 || 
               $this->texts()->count() > 0 || 
               $this->links()->count() > 0;
    }

    /**
     * التحقق من أن التسليم متأخر
     */
    public function isLate(): bool
    {
        if ($this->is_late) {
            return true;
        }

        $dueDateTime = Carbon::parse($this->assignment->due_date->format('Y-m-d') . ' ' . $this->assignment->due_time);
        return $this->submitted_at->gt($dueDateTime);
    }

    /**
     * توليد رقم التسليم الفريد
     */
    public static function generateSubmissionNumber(): string
    {
        $lastSubmission = self::latest('id')->first();
        $number = $lastSubmission ? $lastSubmission->id + 1 : 1;
        return 'SUB-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'submitted' => 'مُسلم',
            'late' => 'متأخر',
            'graded' => 'مُصحح',
            'returned' => 'مُرجع',
            'resubmitted' => 'إعادة تسليم',
            default => 'غير محدد',
        };
    }
}
