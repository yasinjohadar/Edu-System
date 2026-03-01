<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotspotZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'zone_name',
        'coordinates_x',
        'coordinates_y',
        'width',
        'height',
        'shape',
    ];

    /**
     * Get the question
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get drag drop items
     */
    public function dragDropItems()
    {
        return $this->hasMany(DragDropItem::class);
    }
}
