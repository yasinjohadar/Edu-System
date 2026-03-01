<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'audio_url',
        'transcript',
        'duration',
        'allow_replay',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
