<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TTTLink extends Model
{
    use HasFactory;

    protected $table = 'ttt_links';

    protected $fillable = [
        'ttt_session_id',
        'title',
        'url',
        'type',
        'order',
    ];

    /**
     * Get the session this link belongs to
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(TTTSession::class, 'ttt_session_id');
    }

    /**
     * Get the formatted URL
     */
    public function getFormattedUrlAttribute(): string
    {
        $url = $this->url;
        
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return 'https://' . $url;
        }
        
        return $url;
    }
}
