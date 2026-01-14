<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TTTSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_active',
        'title',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the current TTT settings (singleton pattern)
     */
    public static function getSettings(): self
    {
        return static::first() ?? static::create([
            'is_active' => false,
            'title' => 'Teachers Teaching Teachers',
            'description' => 'Professional learning sessions led by our own teachers sharing their expertise.',
        ]);
    }

    /**
     * Check if TTT feature is active
     */
    public static function isActive(): bool
    {
        return static::getSettings()->is_active;
    }

    /**
     * Toggle the TTT feature
     */
    public static function toggle(): bool
    {
        $settings = static::getSettings();
        $settings->is_active = !$settings->is_active;
        $settings->save();
        return $settings->is_active;
    }
}
