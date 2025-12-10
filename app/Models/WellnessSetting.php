<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WellnessSetting extends Model
{
    use HasFactory;

    protected $table = 'wellness_settings';

    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active Wellness setting
     */
    public static function getActive()
    {
        return static::first();
    }

    /**
     * Check if Wellness feature is active
     */
    public static function isActive(): bool
    {
        $setting = static::getActive();
        return $setting && $setting->is_active;
    }

    /**
     * Initialize default settings if none exist
     */
    public static function initialize()
    {
        if (!static::exists()) {
            static::create([
                'is_active' => true,
            ]);
        }
    }
}
