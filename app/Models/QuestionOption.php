<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
        'option_order',
        'explanation',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    /**
     * Get the question for this option
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
