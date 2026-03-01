<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBlank extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'blank_order',
        'answer',
        'case_sensitive',
    ];

    protected $casts = [
        'case_sensitive' => 'boolean',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
