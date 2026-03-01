<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchingPair extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'left_item',
        'right_item',
        'pair_order',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
