<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CCLLink extends Model
{
    use HasFactory;

    protected $table = 'ccl_links';

    protected $fillable = [
        'ccl_session_id',
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
        return $this->belongsTo(CCLSession::class, 'ccl_session_id');
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
