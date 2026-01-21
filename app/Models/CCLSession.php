<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Carbon\Carbon;

class CCLSession extends Model
{
    use HasFactory;

    protected $table = 'ccl_sessions';

    protected $fillable = [
        'title',
        'description',
        'presenter_name',
        'presenter_email',
        'presenter_bio',
        'co_presenter_name',
        'co_presenter_email',
        'location',
        'date',
        'start_time',
        'end_time',
        'contact_hours',
        'p_d_day_id',
        'division_id',
        'category',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'contact_hours' => 'decimal:2',
        'category' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the PD day this session belongs to
     */
    public function pdDay(): BelongsTo
    {
        return $this->belongsTo(PDDay::class, 'p_d_day_id');
    }

    /**
     * Get the division this session is for
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the links for this session
     */
    public function links(): HasMany
    {
        return $this->hasMany(CCLLink::class, 'ccl_session_id')->orderBy('order');
    }

    /**
     * Get user selections for this session
     */
    public function userSelections(): MorphMany
    {
        return $this->morphMany(UserSelectedSession::class, 'selectable');
    }

    /**
     * Scope for active sessions
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
     * Get the start time as a Carbon instance
     */
    public function getStartTimeAttribute($value): ?Carbon
    {
        if (!$value) return null;
        
        $dateStr = $this->date ? $this->date->format('Y-m-d') : now()->format('Y-m-d');
        return Carbon::parse($dateStr . ' ' . $value);
    }

    /**
     * Get the end time as a Carbon instance
     */
    public function getEndTimeAttribute($value): ?Carbon
    {
        if (!$value) return null;
        
        $dateStr = $this->date ? $this->date->format('Y-m-d') : now()->format('Y-m-d');
        return Carbon::parse($dateStr . ' ' . $value);
    }

    /**
     * Calculate contact hours from start/end times
     */
    public function calculateContactHours(): float
    {
        if ($this->start_time && $this->end_time) {
            return round($this->start_time->diffInMinutes($this->end_time) / 60, 2);
        }
        return $this->contact_hours ?? 0;
    }

    /**
     * Generate Google Calendar URL
     */
    public function getGoogleCalendarUrlAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return '';
        }

        $timezone = config('services.calendar.timezone', config('app.timezone', 'UTC'));
        
        $startDateTime = $this->start_time->format('Ymd\THis');
        $endDateTime = $this->end_time->format('Ymd\THis');

        $title = $this->title;
        $description = $this->description ?? '';
        
        if ($this->presenter_name) {
            $description .= "\n\nPresenter: " . $this->presenter_name;
        }
        
        if ($this->co_presenter_name) {
            $description .= "\nCo-Presenter: " . $this->co_presenter_name;
        }

        $location = $this->location ?? '';

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
}
