<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Assignment extends Model
{
    protected $fillable = [
        'assignment_number',
        'subject_id',
        'teacher_id',
        'section_id',
        'title',
        'description',
        'instructions',
        'total_marks',
        'due_date',
        'due_time',
        'allow_late_submission',
        'late_penalty_per_day',
        'max_late_days',
        'max_attempts',
        'allow_resubmission',
        'resubmission_deadline',
        'submission_types',
        'status',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'total_marks' => 'decimal:2',
        'due_date' => 'date',
        'due_time' => 'string',
        'allow_late_submission' => 'boolean',
        'late_penalty_per_day' => 'decimal:2',
        'max_attempts' => 'integer',
        'allow_resubmission' => 'boolean',
        'resubmission_deadline' => 'date',
        'submission_types' => 'array',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'submission_types' => '["file","text","link"]',
    ];

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
     * العلاقة مع الفصل الدراسي
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * العلاقة مع المرفقات
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(AssignmentAttachment::class)->orderBy('sort_order');
    }

    /**
     * العلاقة مع التسليمات
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ الواجب
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * التحقق من إمكانية التسليم للطالب
     */
    public function canSubmit(Student $student): bool
    {
        // التحقق من حالة الواجب
        if ($this->status !== 'published' || !$this->is_active) {
            return false;
        }

        // التحقق من عدد المحاولات
        if ($this->max_attempts !== null) {
            $submissionsCount = $this->submissions()
                ->where('student_id', $student->id)
                ->count();
            
            if ($submissionsCount >= $this->max_attempts) {
                return false;
            }
        }

        // التحقق من الموعد النهائي
        $dueDateTime = Carbon::parse($this->due_date->format('Y-m-d') . ' ' . $this->due_time);
        if (!$this->allow_late_submission && Carbon::now()->gt($dueDateTime)) {
            return false;
        }

        return true;
    }

    /**
     * الحصول على عدد المحاولات المتبقية للطالب
     */
    public function getRemainingAttempts(Student $student): ?int
    {
        if ($this->max_attempts === null) {
            return null; // غير محدود
        }

        $submissionsCount = $this->submissions()
            ->where('student_id', $student->id)
            ->count();

        return max(0, $this->max_attempts - $submissionsCount);
    }

    /**
     * التحقق من أن التسليم مسموح حالياً
     */
    public function isSubmissionAllowed(): bool
    {
        return $this->status === 'published' && $this->is_active;
    }

    /**
     * توليد رقم الواجب الفريد
     */
    public static function generateAssignmentNumber(): string
    {
        $lastAssignment = self::latest('id')->first();
        $number = $lastAssignment ? $lastAssignment->id + 1 : 1;
        return 'ASS-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * الحصول على اسم الحالة بالعربية
     */
    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'published' => 'منشور',
            'closed' => 'مغلق',
            default => 'غير محدد',
        };
    }

    /**
     * التحقق من أن الواجب متأخر
     */
    public function isOverdue(): bool
    {
        $dueDateTime = Carbon::parse($this->due_date->format('Y-m-d') . ' ' . $this->due_time);
        return Carbon::now()->gt($dueDateTime);
    }
}
