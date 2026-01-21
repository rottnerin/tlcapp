<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PDDay extends Model
{
    use HasFactory;

    protected $table = 'p_d_days';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'archived_at',
        'season',
        'academic_year',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /**
     * Get schedule items for this PD day
     */
    public function scheduleItems(): HasMany
    {
        return $this->hasMany(ScheduleItem::class);
    }

    /**
     * Get wellness sessions for this PD day
     */
    public function wellnessSessions(): HasMany
    {
        return $this->hasMany(WellnessSession::class);
    }

    /**
     * Get the formatted date range
     */
    public function getDateRangeAttribute(): string
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($start->isSameDay($end)) {
            return $start->format('F j, Y');
        }

        if ($start->month === $end->month) {
            return $start->format('F j').'-'.$end->format('j, Y');
        }

        return $start->format('F j').' - '.$end->format('F j, Y');
    }

    /**
     * Scope to get only active PD days
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the currently active PD day
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Scope to filter by season
     */
    public function scopeBySeason($query, string $season)
    {
        return $query->where('season', $season);
    }

    /**
     * Scope to filter by academic year
     */
    public function scopeByAcademicYear($query, string $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope for fall PD days
     */
    public function scopeFall($query)
    {
        return $query->where('season', 'fall');
    }

    /**
     * Scope for spring PD days
     */
    public function scopeSpring($query)
    {
        return $query->where('season', 'spring');
    }

    /**
     * Get CCL sessions for this PD day
     */
    public function cclSessions(): HasMany
    {
        return $this->hasMany(CCLSession::class, 'p_d_day_id');
    }

    /**
     * Get the current academic year
     */
    public static function getCurrentAcademicYear(): string
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;

        // Academic year runs Aug 1 - Jul 31
        if ($month >= 8) {
            return $year.'-'.($year + 1);
        }

        return ($year - 1).'-'.$year;
    }

    /**
     * Check if this PD day is in the current academic year
     */
    public function isCurrentAcademicYear(): bool
    {
        return $this->academic_year === self::getCurrentAcademicYear();
    }

    /**
     * Check if this PD day is archived
     */
    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /**
     * Scope to get only archived PD days
     */
    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    /**
     * Scope to get only non-archived PD days
     */
    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Archive this PD day
     */
    public function archive(): bool
    {
        if ($this->is_active) {
            return false;
        }

        $this->archived_at = Carbon::now();

        return $this->save();
    }

    /**
     * Unarchive this PD day
     */
    public function unarchive(): bool
    {
        $this->archived_at = null;

        return $this->save();
    }

    /**
     * Get the status of this PD day (active, inactive, or archived)
     */
    public function getStatusAttribute(): string
    {
        if ($this->isArchived()) {
            return 'archived';
        }

        return $this->is_active ? 'active' : 'inactive';
    }

    /**
     * Get archived PD days for a specific season
     */
    public static function getArchivedBySeason(string $season)
    {
        return static::archived()
            ->where('season', $season)
            ->orderBy('start_date', 'desc')
            ->get();
    }
}
