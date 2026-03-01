<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'total_marks',
        'obtained_marks',
        'percentage',
        'status',
        'started_at',
        'submitted_at',
        'time_taken',
        'attempts',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'time_taken' => 'integer',
        'attempts' => 'integer',
        'percentage' => 'decimal:2',
    ];

    /**
     * Get the exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get all answers for this result
     */
    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * Get status name in Arabic
     */
    public function getStatusNameAttribute()
    {
        return match($this->status) {
            'passed' => 'ناجح',
            'failed' => 'راسب',
            'absent' => 'غائب',
            default => $this->status,
        };
    }

    /**
     * Check if student passed
     */
    public function hasPassed()
    {
        return $this->status === 'passed';
    }

    /**
     * Check if student failed
     */
    public function hasFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Check if student was absent
     */
    public function wasAbsent()
    {
        return $this->status === 'absent';
    }
}
