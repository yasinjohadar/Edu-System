<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBooleanAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'is_correct',
        'explanation',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Get the question for this boolean answer
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
