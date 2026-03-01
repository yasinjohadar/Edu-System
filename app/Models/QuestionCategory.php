<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'question_id',
    ];

    /**
     * Get the question for this category
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get all classification items in this category
     */
    public function classificationItems()
    {
        return $this->hasMany(ClassificationItem::class);
    }
}
