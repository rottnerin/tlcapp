<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PLWednesdayLink extends Model
{
    use HasFactory;

    protected $table = 'pl_wednesday_links';

    protected $fillable = [
        'pl_wednesday_session_id',
        'title',
        'url',
        'description',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the session this link belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(PLWednesdaySession::class, 'pl_wednesday_session_id');
    }

    /**
     * Scope for ordered links
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
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
}
