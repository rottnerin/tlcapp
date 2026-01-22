<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CCLSetting extends Model
{
    use HasFactory;

    protected $table = 'ccl_settings';

    protected $fillable = [
        'is_active',
        'title',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the current CCL settings (singleton pattern)
     */
    public static function getSettings(): self
    {
        return static::first() ?? static::create([
            'is_active' => false,
            'title' => 'Collaborative Community Learning Sessions',
            'description' => 'Professional learning sessions led by our own teachers sharing their expertise.',
        ]);
    }

    /**
     * Check if CCL feature is active
     */
    public static function isActive(): bool
    {
        return static::getSettings()->is_active;
    }

    /**
     * Toggle the CCL feature
     */
    public static function toggle(): bool
    {
        $settings = static::getSettings();
        $settings->is_active = !$settings->is_active;
        $settings->save();
        return $settings->is_active;
    }
}
