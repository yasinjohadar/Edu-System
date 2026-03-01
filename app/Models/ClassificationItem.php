<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassificationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'item_text',
        'category_id',
        'item_order',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(QuestionCategory::class);
    }
}
