<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class ScheduleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'date',
        'presenter_primary',
        'presenter_secondary',
        'presenter_bio',
        'max_participants',
        'current_enrollment',
        'equipment_needed',
        'special_requirements',
        'link_title',
        'link_url',
        'link_description',
        'is_active',
        'session_type',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the divisions this schedule item belongs to
     */
    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(Division::class, 'schedule_item_divisions');
    }

    /**
     * Get user enrollments for this schedule item
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
     * Scope for active schedule items
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for items on a specific date
     */
    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope for items for specific divisions
     */
    public function scopeForDivisions($query, $divisionIds)
    {
        return $query->whereHas('divisions', function($q) use ($divisionIds) {
            $q->whereIn('divisions.id', (array) $divisionIds);
        });
    }

    /**
     * Check if schedule item has available capacity
     */
    public function hasAvailableCapacity(): bool
    {
        if ($this->max_participants === null) {
            return true;
        }
        
        return $this->current_enrollment < $this->max_participants;
    }

    /**
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): ?int
    {
        if ($this->max_participants === null) {
            return null;
        }
        
        return max(0, $this->max_participants - $this->current_enrollment);
    }

    /**
     * Check if schedule item has a link
     */
    public function hasLink(): bool
    {
        return !empty($this->link_url) && !empty($this->link_title);
    }

    /**
     * Get formatted link URL (add https if missing)
     */
    public function getFormattedLinkUrlAttribute(): string
    {
        if (empty($this->link_url)) {
            return '';
        }

        if (!str_starts_with($this->link_url, 'http://') && !str_starts_with($this->link_url, 'https://')) {
            return 'https://' . $this->link_url;
        }

        return $this->link_url;
    }
}
