<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class WellnessSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'presenter_name',
        'presenter_bio',
        'presenter_email',
        'location',
        'start_time',
        'end_time',
        'date',
        'max_participants',
        'current_enrollment',
        'category',
        'equipment_needed',
        'special_requirements',
        'preparation_notes',
        'is_active',
        'allow_waitlist',
        'source',
        'external_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'is_active' => 'boolean',
        'allow_waitlist' => 'boolean',
    ];

    /**
     * Get user enrollments for this wellness session
     */
    public function userSessions(): HasMany
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Get enrolled users
     */
    public function enrolledUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_sessions')
                    ->withPivot(['status', 'enrolled_at', 'notes', 'rating', 'feedback'])
                    ->withTimestamps();
    }

    /**
     * Get confirmed enrollments only
     */
    public function confirmedEnrollments(): HasMany
    {
        return $this->userSessions()->where('status', 'confirmed');
    }

    /**
     * Get waitlisted enrollments
     */
    public function waitlistedEnrollments(): HasMany
    {
        return $this->userSessions()->where('status', 'waitlisted');
    }

    /**
     * Scope for active wellness sessions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sessions on a specific date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for sessions by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for sessions with available capacity
     */
    public function scopeWithCapacity($query)
    {
        return $query->whereRaw('current_enrollment < max_participants');
    }

    /**
     * Check if session has available capacity
     */
    public function hasAvailableCapacity(): bool
    {
        return $this->current_enrollment < $this->max_participants;
    }

    /**
     * Check if session allows waitlist
     */
    public function canJoinWaitlist(): bool
    {
        return $this->allow_waitlist && !$this->hasAvailableCapacity();
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->max_participants - $this->current_enrollment);
    }

    /**
     * Get total waitlisted count
     */
    public function getWaitlistCountAttribute(): int
    {
        return $this->waitlistedEnrollments()->count();
    }

    /**
     * Check if session is full
     */
    public function isFull(): bool
    {
        return $this->current_enrollment >= $this->max_participants;
    }

    /**
     * Check if session is available for enrollment
     */
    public function isAvailableForEnrollment(): bool
    {
        return $this->is_active && ($this->hasAvailableCapacity() || $this->allow_waitlist);
    }

    /**
     * Get session status for display
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if ($this->isFull()) {
            return $this->allow_waitlist ? 'waitlist' : 'full';
        }
        
        return 'available';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'green',
            'waitlist' => 'yellow',
            'full' => 'red',
            'inactive' => 'gray',
            default => 'gray'
        };
    }
}