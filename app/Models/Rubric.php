<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'max_points',
        'criteria',
        'created_by',
    ];

    protected $casts = [
        'criteria' => 'array',
    ];

    /**
     * Get the creator of the rubric
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all essay questions using this rubric
     */
    public function essayQuestions()
    {
        return $this->hasMany(EssayQuestion::class);
    }

    /**
     * Get all essay evaluations using this rubric
     */
    public function evaluations()
    {
        return $this->hasMany(EssayEvaluation::class);
    }
}
