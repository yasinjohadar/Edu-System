<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_code',
        'type',
        'content',
        'explanation',
        'difficulty',
        'subject_id',
        'grade_id',
        'tags',
        'points',
        'time_limit',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the subject of the question
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the grade of the question
     */
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the creator of the question
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get options for multiple choice questions
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    /**
     * Get boolean answer for true/false questions
     */
    public function booleanAnswer()
    {
        return $this->hasOne(QuestionBooleanAnswer::class);
    }

    /**
     * Get essay question details
     */
    public function essayQuestion()
    {
        return $this->hasOne(EssayQuestion::class);
    }

    /**
     * Get blanks for fill in the blanks questions
     */
    public function blanks()
    {
        return $this->hasMany(QuestionBlank::class);
    }

    /**
     * Get matching pairs
     */
    public function matchingPairs()
    {
        return $this->hasMany(MatchingPair::class);
    }

    /**
     * Get classification items
     */
    public function classificationItems()
    {
        return $this->hasMany(ClassificationItem::class);
    }

    /**
     * Get ordering items
     */
    public function orderingItems()
    {
        return $this->hasMany(OrderingItem::class);
    }

    /**
     * Get hotspot zones
     */
    public function hotspotZones()
    {
        return $this->hasMany(HotspotZone::class);
    }

    /**
     * Get drag drop items
     */
    public function dragDropItems()
    {
        return $this->hasMany(DragDropItem::class);
    }

    /**
     * Get audio question details
     */
    public function audioQuestion()
    {
        return $this->hasOne(AudioQuestion::class);
    }

    /**
     * Get video question details
     */
    public function videoQuestion()
    {
        return $this->hasOne(VideoQuestion::class);
    }

    /**
     * Get categories
     */
    public function categories()
    {
        return $this->hasMany(QuestionCategory::class);
    }

    /**
     * Get comments
     */
    public function comments()
    {
        return $this->hasMany(QuestionComment::class);
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
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for questions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for questions by difficulty
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope for questions by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for questions by grade
     */
    public function scopeByGrade($query, $gradeId)
    {
        return $query->where('grade_id', $gradeId);
    }

    /**
     * Get type name in Arabic
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'multiple_choice' => 'اختيار من متعدد',
            'true_false' => 'صواب وخطأ',
            'essay' => 'مقال',
            'fill_blanks' => 'ملء الفراغات',
            'matching' => 'مطابقة',
            'ordering' => 'ترتيب',
            'classification' => 'تصنيف',
            'drag_drop' => 'سحب وإفلات',
            'hotspot' => 'نقاط ساخنة',
            'audio' => 'صوتي',
            'video' => 'فيديو',
            default => $this->type,
        };
    }

    /**
     * Get difficulty name in Arabic
     */
    public function getDifficultyNameAttribute()
    {
        return match($this->difficulty) {
            'easy' => 'سهل',
            'medium' => 'متوسط',
            'hard' => 'صعب',
            default => $this->difficulty,
        };
    }
}
