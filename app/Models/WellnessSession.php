<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'co_presenter_name',
        'co_presenter_email',
        'location',
        'date',
        'max_participants',
        'current_enrollment',
        'category',
        'equipment_needed',
        'special_requirements',
        'preparation_notes',
        'is_active',
        'source',
        'external_id',
        'p_d_day_id',
    ];

    protected $casts = [
        'date' => 'date',
        'category' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the PD day this wellness session belongs to
     */
    public function pdDay(): BelongsTo
    {
        return $this->belongsTo(PDDay::class, 'p_d_day_id');
    }

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
     * Get available spots
     */
    public function getAvailableSpotsAttribute(): int
    {
        return max(0, $this->max_participants - $this->current_enrollment);
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
        // Users can only enroll when the session is active AND capacity remains.
        return $this->is_active && $this->hasAvailableCapacity();
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
            // Always report 'full' once capacity is reached. Waitlist is no longer offered.
            return 'full';
        }
        
        return 'available';
    }

    /**
     * Get the fixed start time for all wellness sessions
     */
    public function getStartTimeAttribute(): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' 14:30:00');
    }

    /**
     * Get the fixed end time for all wellness sessions
     */
    public function getEndTimeAttribute(): \Carbon\Carbon
    {
        return \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' 15:30:00');
    }

    /**
     * Get formatted category names
     */
    public function getCategoryNamesAttribute(): string
    {
        if (!$this->category || !is_array($this->category)) {
            return 'Uncategorized';
        }
        
        return implode(', ', $this->category);
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'available' => 'green',
            'full' => 'red',
            'inactive' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Generate Google Calendar URL for this wellness session
     */
    public function getGoogleCalendarUrlAttribute(): string
    {
        $timezone = $this->calendarTimezone();

        $startLocal = $this->convertToCalendarTimezone($this->start_time, $timezone);
        $endLocal = $this->convertToCalendarTimezone($this->end_time, $timezone);

        if (!$startLocal || !$endLocal) {
            return '';
        }

        $startDateTime = $startLocal->format('Ymd\THis');
        $endDateTime = $endLocal->format('Ymd\THis');

        // Prepare event details
        $title = $this->title;
        $description = $this->description ?? '';
        $location = $this->location ?? '';

        // Add presenter info to description if available
        if ($this->presenter_name) {
            $description .= "\n\nPresenter: " . $this->presenter_name;
            if ($this->presenter_bio) {
                $description .= "\nBio: " . $this->presenter_bio;
            }
        }
        
        // Add co-presenter info if available
        if ($this->co_presenter_name) {
            $description .= "\nCo-Presenter: " . $this->co_presenter_name;
        }
        
        // Add category info
        if ($this->category && is_array($this->category)) {
            $description .= "\nCategory: " . implode(', ', $this->category);
        }
        
        // Add equipment needed if available
        if ($this->equipment_needed) {
            $description .= "\n\nEquipment Needed: " . $this->equipment_needed;
        }
        
        // Add special requirements if available
        if ($this->special_requirements) {
            $description .= "\nSpecial Requirements: " . $this->special_requirements;
        }
        
        // Build Google Calendar URL with proper encoding
        $baseUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
        $params = [
            'text' => $title,
            'dates' => $startDateTime . '/' . $endDateTime,
            'details' => $description,
            'location' => $location,
            'ctz' => $timezone,
        ];
        
        return $baseUrl . '&' . http_build_query($params);
    }

    /**
     * Generate mobile-friendly calendar URLs for different calendar apps
     */
    public function getMobileCalendarUrlsAttribute(): array
    {
        $timezone = $this->calendarTimezone();
        $startLocal = $this->convertToCalendarTimezone($this->start_time, $timezone);
        $endLocal = $this->convertToCalendarTimezone($this->end_time, $timezone);

        if (!$startLocal || !$endLocal) {
            return [];
        }

        // Format dates for different calendar apps
        $startDateTime = $startLocal->format('Ymd\THis');
        $endDateTime = $endLocal->format('Ymd\THis');

        // Prepare event details
        $title = urlencode($this->title);
        $description = urlencode(($this->description ?? '') . 
            ($this->presenter_name ? "\n\nPresenter: " . $this->presenter_name : '') .
            ($this->location ? "\nLocation: " . $this->location : ''));
        $location = urlencode($this->location ?? '');
        $timezoneParam = urlencode($timezone);
        $outlookStart = urlencode($startLocal->format('Y-m-d\TH:i:sP'));
        $outlookEnd = urlencode($endLocal->format('Y-m-d\TH:i:sP'));

        return [
            'google' => "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$startDateTime}/{$endDateTime}&details={$description}&location={$location}&ctz={$timezoneParam}",
            'outlook' => "https://outlook.live.com/calendar/0/deeplink/compose?subject={$title}&startdt={$outlookStart}&enddt={$outlookEnd}&body={$description}&location={$location}",
            'yahoo' => "https://calendar.yahoo.com/?v=60&view=d&type=20&title={$title}&st={$startDateTime}&et={$endDateTime}&desc={$description}&in_loc={$location}",
            'ics' => '#' // Placeholder for .ics file download
        ];
    }

    protected function calendarTimezone(): string
    {
        $timezone = config('services.calendar.timezone', config('app.timezone'));

        return $timezone ?: 'UTC';
    }

    protected function convertToCalendarTimezone(?Carbon $date, string $timezone): ?Carbon
    {
        if (!$date) {
            return null;
        }

        return Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d H:i:s'), $timezone);
    }
}
