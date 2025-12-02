<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'full_name',
        'color_primary',
        'color_secondary',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all users belonging to this division
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all schedule items for this division
     */
    public function scheduleItems(): BelongsToMany
    {
        return $this->belongsToMany(ScheduleItem::class, 'schedule_item_divisions');
    }

    /**
     * Scope to get only active divisions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get division by name (ES, MS, HS)
     */
    public function scopeByName($query, $name)
    {
        return $query->where('name', $name);
    }
}
