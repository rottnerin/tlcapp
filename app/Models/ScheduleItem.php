<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'p_d_day_id',
        'wellness_session_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the PD day this schedule item belongs to
     */
    public function pdDay(): BelongsTo
    {
        return $this->belongsTo(PDDay::class, 'p_d_day_id');
    }

    /**
     * Get the wellness session this schedule item links to (for Wellness type items)
     */
    public function wellnessSession(): BelongsTo
    {
        return $this->belongsTo(WellnessSession::class);
    }

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

    /**
     * Generate Google Calendar URL for this schedule item
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
        if ($this->presenter_primary) {
            $description .= "\n\nPresenter: " . $this->presenter_primary;
        }
        
        // Add division info to description
        if ($this->divisions->count() > 0) {
            $divisionNames = $this->divisions->pluck('full_name')->join(', ');
            $description .= "\nDivisions: " . $divisionNames;
        }
        
        // Add link to description if available
        if ($this->hasLink()) {
            $description .= "\n\nMore info: " . $this->formatted_link_url;
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

        // Format dates for different calendar apps
        if (!$startLocal || !$endLocal) {
            return [];
        }

        $startDateTime = $startLocal->format('Ymd\THis');
        $endDateTime = $endLocal->format('Ymd\THis');

        // Prepare event details
        $title = urlencode($this->title);
        $description = urlencode(($this->description ?? '') . 
            ($this->presenter_primary ? "\n\nPresenter: " . $this->presenter_primary : '') .
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
