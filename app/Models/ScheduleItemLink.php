<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleItemLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_item_id',
        'title',
        'url',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the schedule item this link belongs to
     */
    public function scheduleItem(): BelongsTo
    {
        return $this->belongsTo(ScheduleItem::class);
    }

    /**
     * Get formatted URL (add https if missing)
     */
    public function getFormattedUrlAttribute(): string
    {
        if (empty($this->url)) {
            return '';
        }

        if (!str_starts_with($this->url, 'http://') && !str_starts_with($this->url, 'https://')) {
            return 'https://' . $this->url;
        }

        return $this->url;
    }

    /**
     * Scope to order links by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}