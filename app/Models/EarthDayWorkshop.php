<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EarthDayWorkshop extends Model
{
    use HasFactory;

    const CAPACITY = 15;

    protected $fillable = [
        'title',
        'presenter',
        'location',
        'description',
        'date',
        'start_time',
        'end_time',
        'current_enrollment',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_active' => 'boolean',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(EarthDayEnrollment::class);
    }

    public function userSelections(): MorphMany
    {
        return $this->morphMany(UserSelectedSession::class, 'selectable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasAvailableCapacity(): bool
    {
        return $this->current_enrollment < self::CAPACITY;
    }

    public function isFull(): bool
    {
        return $this->current_enrollment >= self::CAPACITY;
    }

    public function getFillPercentageAttribute(): int
    {
        return min(100, (int) round($this->current_enrollment / self::CAPACITY * 100));
    }

    public function getGoogleCalendarUrlAttribute(): string
    {
        $timezone = config('services.calendar.timezone', config('app.timezone', 'UTC'));

        $dateStr = $this->date->format('Y-m-d');
        $start = Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $this->start_time, $timezone);
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $this->end_time, $timezone);

        if (!$start || !$end) {
            return '';
        }

        $description = $this->description ?? '';
        if ($this->presenter) {
            $description .= ($description ? "\n\n" : '') . 'Presenter: ' . $this->presenter;
        }

        $params = [
            'text'     => $this->title,
            'dates'    => $start->format('Ymd\THis') . '/' . $end->format('Ymd\THis'),
            'details'  => $description,
            'location' => $this->location ?? '',
            'ctz'      => $timezone,
        ];

        return 'https://calendar.google.com/calendar/render?action=TEMPLATE&' . http_build_query($params);
    }
}
