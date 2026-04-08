<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EarthDaySetting extends Model
{
    use HasFactory;

    protected $table = 'earth_day_settings';

    protected $fillable = [
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getActive()
    {
        return static::first();
    }

    public static function isActive(): bool
    {
        $setting = static::getActive();
        return $setting && $setting->is_active;
    }

    public static function initialize()
    {
        if (!static::exists()) {
            static::create(['is_active' => true]);
        }
    }
}
