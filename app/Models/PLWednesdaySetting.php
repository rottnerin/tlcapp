<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PLWednesdaySetting extends Model
{
    use HasFactory;

    protected $table = 'pl_wednesday_settings';

    protected $fillable = [
        'is_active',
        'start_date',
        'end_date',
        'default_start_time',
        'default_end_time',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
        'default_start_time' => 'string',
        'default_end_time' => 'string',
    ];

    /**
     * Get the active PL Wednesday setting
     */
    public static function getActive()
    {
        return static::first();
    }

    /**
     * Check if PL Wednesday feature is active
     */
    public static function isActive(): bool
    {
        $setting = static::getActive();
        return $setting && $setting->is_active;
    }

    /**
     * Initialize default settings if none exist
     * Also updates existing settings to ensure correct date range
     */
    public static function initialize()
    {
        $settings = static::first();
        
        if (!$settings) {
            static::create([
                'is_active' => false,
                'start_date' => '2025-08-06',
                'end_date' => '2026-05-30',
                'default_start_time' => '15:00:00',
                'default_end_time' => '17:00:00',
            ]);
        } else {
            // Update date range if it doesn't match the required range
            $requiredStartDate = '2025-08-06';
            $requiredEndDate = '2026-05-30';
            
            if ($settings->start_date->format('Y-m-d') !== $requiredStartDate || 
                $settings->end_date->format('Y-m-d') !== $requiredEndDate) {
                $settings->update([
                    'start_date' => $requiredStartDate,
                    'end_date' => $requiredEndDate,
                ]);
            }
        }
    }
}
