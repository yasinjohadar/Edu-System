<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_code',
        'title',
        'description',
        'type',
        'subject_id',
        'grade_id',
        'section_id',
        'teacher_id',
        'duration',
        'total_marks',
        'passing_marks',
        'start_time',
        'end_time',
        'is_published',
        'is_active',
        'allow_review',
        'show_results',
        'show_answers',
        'randomize_questions',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_active' => 'boolean',
        'allow_review' => 'boolean',
        'show_results' => 'boolean',
        'show_answers' => 'boolean',
        'randomize_questions' => 'boolean',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Get the subject of the exam
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the grade of the exam
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the section of the exam
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get the teacher of the exam
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get exam questions
     */
    public function examQuestions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    /**
     * Get exam answers
     */
    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * Get exam results
     */
    public function examResults()
    {
        return $this->hasMany(ExamResult::class);
    }

    /**
     * Get type name in Arabic
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'quiz' => 'اختبار قصير',
            'exam' => 'امتحان',
            'midterm' => 'امتحان نصفي',
            'final' => 'امتحان نهائي',
            default => $this->type,
        };
    }

    /**
     * Scope for published exams
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for active exams
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for exams by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for exams by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for exams by grade
     */
    public function scopeByGrade($query, $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    /**
     * Scope for exams by section
     */
    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    /**
     * Scope for exams by teacher
     */
    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Check if exam is available now
     */
    public function isAvailable()
    {
        return $this->is_published && 
               $this->is_active && 
               (!$this->start_time || $this->start_time->isFuture()) &&
               (!$this->end_time || $this->end_time->isFuture());
    }

    /**
     * Check if exam has started
     */
    public function hasStarted()
    {
        return $this->start_time && $this->start_time->isPast();
    }

    /**
     * Check if exam has ended
     */
    public function hasEnded()
    {
        return $this->end_time && $this->end_time->isPast();
    }
}
