<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wellness_session_id',
        'schedule_item_id',
        'status',
        'enrolled_at',
        'notes',
        'rating',
        'feedback',
        'attended',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'attended' => 'boolean',
    ];

    /**
     * Get the user that owns the session enrollment
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the wellness session (if applicable)
     */
    public function wellnessSession(): BelongsTo
    {
        return $this->belongsTo(WellnessSession::class);
    }

    /**
     * Get the schedule item (if applicable)
     */
    public function scheduleItem(): BelongsTo
    {
        return $this->belongsTo(ScheduleItem::class);
    }

    /**
     * Scope for confirmed enrollments
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for waitlisted enrollments
     */
    public function scopeWaitlisted($query)
    {
        return $query->where('status', 'waitlisted');
    }

    /**
     * Scope for cancelled enrollments
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Check if enrollment is confirmed
     */
    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if enrollment is waitlisted
     */
    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlisted';
    }

    /**
     * Check if enrollment is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}