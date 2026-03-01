<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'item_text',
        'correct_order',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
