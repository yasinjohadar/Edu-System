<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DragDropItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'item_text',
        'target_zone_id',
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
     * Get the target zone
     */
    public function targetZone()
    {
        return $this->belongsTo(HotspotZone::class, 'target_zone_id');
    }
}
