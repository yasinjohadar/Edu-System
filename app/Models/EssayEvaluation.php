<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_answer_id',
        'rubric_id',
        'criteria_scores',
        'total_score',
        'feedback',
        'evaluated_by',
        'evaluated_at',
    ];

    protected $casts = [
        'criteria_scores' => 'array',
        'evaluated_at' => 'datetime',
    ];

    /**
     * Get the exam answer
     */
    public function examAnswer()
    {
        return $this->belongsTo(ExamAnswer::class);
    }

    /**
     * Get the rubric
     */
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    /**
     * Get the evaluator
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
