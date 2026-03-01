<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'min_words',
        'max_words',
        'allow_attachments',
        'rubric_id',
    ];

    protected $casts = [
        'allow_attachments' => 'boolean',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the rubric
     */
    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }

    /**
     * Get evaluations
     */
    public function evaluations()
    {
        return $this->hasManyThrough(EssayEvaluation::class, ExamAnswer::class);
    }
}
