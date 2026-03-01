<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question_id',
        'student_id',
        'answer',
        'is_correct',
        'marks_obtained',
        'time_taken',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'time_taken' => 'integer',
        'marks_obtained' => 'decimal:2',
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
     * Get the student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get evaluation (for essay questions)
     */
    public function evaluation()
    {
        return $this->hasOne(EssayEvaluation::class);
    }
}
