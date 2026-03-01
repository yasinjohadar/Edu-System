<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_id',
        'order',
        'points',
        'is_mandatory',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
        'order' => 'integer',
        'points' => 'decimal:2',
    ];

    /**
     * Get the exam
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get exam answers for this question
     */
    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
