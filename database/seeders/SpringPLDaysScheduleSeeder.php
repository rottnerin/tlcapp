<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\PDDay;
use Carbon\Carbon;

class SpringPLDaysScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Spring PD Day
        $springPDDay = PDDay::spring()->active()->first();
        
        if (!$springPDDay) {
            $this->command->warn('No active Spring PD Day found. Please create one first.');
            return;
        }

        // Schedule items cleared - add via .md import or admin UI
        $scheduleItems = [];

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($scheduleItems as $itemData) {
            // Check if item already exists
            $existing = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $itemData['date'])
                ->where('start_time', Carbon::parse($itemData['date']->format('Y-m-d') . ' ' . $itemData['start_time']))
                ->where('title', $itemData['title'])
                ->first();

            if ($existing) {
                $this->command->info("Skipping existing item: {$itemData['title']}");
                $skippedCount++;
                continue;
            }

            // Create schedule item
            $scheduleItem = ScheduleItem::create([
                'title' => $itemData['title'],
                'description' => $itemData['description'],
                'location' => $itemData['location'],
                'start_time' => Carbon::parse($itemData['date']->format('Y-m-d') . ' ' . $itemData['start_time']),
                'end_time' => Carbon::parse($itemData['date']->format('Y-m-d') . ' ' . $itemData['end_time']),
                'date' => $itemData['date'],
                'presenter_primary' => $itemData['presenter_primary'],
                'presenter_bio' => $itemData['presenter_bio'] ?? null,
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
            ]);

            // Attach divisions
            if (isset($itemData['divisions'])) {
                $scheduleItem->divisions()->attach(
                    collect($itemData['divisions'])->pluck('id')->toArray()
                );
            }

            $createdCount++;
            $this->command->info("Created: {$scheduleItem->title} - {$scheduleItem->date->format('M d')} {$scheduleItem->start_time->format('g:i A')}");
        }

        $this->command->info("\n✅ Successfully created {$createdCount} schedule items for Spring PL Days.");
        if ($skippedCount > 0) {
            $this->command->info("⏭️  Skipped {$skippedCount} existing items.");
        }
    }
}
