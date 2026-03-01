<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'video_url',
        'thumbnail_url',
        'duration',
        'auto_play',
        'allow_download',
        'transcript',
        'start_time',
        'end_time',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
