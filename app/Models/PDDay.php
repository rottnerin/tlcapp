<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
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
            return $start->format('F j') . '-' . $end->format('j, Y');
        }
        
        return $start->format('F j') . ' - ' . $end->format('F j, Y');
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
}
