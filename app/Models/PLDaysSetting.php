<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PLDaysSetting extends Model
{
    use HasFactory;

    protected $table = 'pl_days_settings';

    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active PL Days setting
     */
    public static function getActive()
    {
        return static::first();
    }

    /**
     * Check if PL Days feature is active
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
