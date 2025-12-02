<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PDDay;
use Carbon\Carbon;

class PDDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample PD Day event
        PDDay::create([
            'title' => 'September 2025 Professional Learning Days',
            'description' => 'Two days of professional development focused on innovative teaching strategies and wellness.',
            'start_date' => Carbon::parse('2025-09-25'),
            'end_date' => Carbon::parse('2025-09-26'),
            'is_active' => true,
        ]);

        // Create a future PD Day event
        PDDay::create([
            'title' => 'January 2026 Professional Learning Days',
            'description' => 'Mid-year professional development sessions.',
            'start_date' => Carbon::parse('2026-01-15'),
            'end_date' => Carbon::parse('2026-01-16'),
            'is_active' => false,
        ]);
    }
}
