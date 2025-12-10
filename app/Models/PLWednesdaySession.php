<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PLWednesdaySession extends Model
{
    use HasFactory;

    protected $table = 'pl_wednesday_sessions';

    protected $fillable = [
        'title',
        'description',
        'location',
        'date',
        'start_time',
        'end_time',
        'duration',
        'is_active',
        'division_id',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the links for this session
     */
    public function links(): HasMany
    {
        return $this->hasMany(PLWednesdayLink::class, 'pl_wednesday_session_id')->orderBy('order');
    }

    /**
     * Get the division this session belongs to
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
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
     * Scope for sessions between dates
     */
    public function scopeBetweenDates($query, $start, $end)
    {
        return $query->whereBetween('date', [$start, $end]);
    }

    /**
     * Get formatted time range
     */
    public function getFormattedTimeAttribute(): string
    {
        if (!$this->start_time || !$this->end_time) {
            return '';
        }
        
        $start = Carbon::createFromFormat('H:i:s', $this->start_time)->format('g:i A');
        $end = Carbon::createFromFormat('H:i:s', $this->end_time)->format('g:i A');
        return "{$start} - {$end}";
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute(): ?int
    {
        if (isset($this->attributes['duration']) && $this->attributes['duration']) {
            return $this->attributes['duration'];
        }

        if ($this->start_time && $this->end_time) {
            $start = Carbon::createFromFormat('H:i:s', $this->start_time);
            $end = Carbon::createFromFormat('H:i:s', $this->end_time);
            return $start->diffInMinutes($end);
        }

        return null;
    }

    /**
     * Set duration based on start and end times
     */
    public function calculateDuration(): void
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::createFromFormat('H:i:s', $this->start_time);
            $end = Carbon::createFromFormat('H:i:s', $this->end_time);
            $this->duration = $start->diffInMinutes($end);
        }
    }

    /**
     * Check if session is visible (feature active and session active)
     */
    public function isVisible(): bool
    {
        return PLWednesdaySetting::isActive() && $this->is_active;
    }
}
