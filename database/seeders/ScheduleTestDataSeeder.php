<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\Division;
use Carbon\Carbon;

class ScheduleTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating test schedule events...');

        // Get some divisions for testing
        $divisions = Division::all();
        if ($divisions->isEmpty()) {
            $this->command->warn('No divisions found. Creating sample divisions...');
            $divisions = collect([
                Division::create(['name' => 'Engineering', 'description' => 'Engineering Division']),
                Division::create(['name' => 'Marketing', 'description' => 'Marketing Division']),
                Division::create(['name' => 'HR', 'description' => 'Human Resources Division']),
            ]);
        }

        // Test schedule items cleared - add via admin UI or import
        $testScheduleItems = [];

        $createdCount = 0;
        foreach ($testScheduleItems as $itemData) {
            try {
                $scheduleItem = ScheduleItem::create($itemData);
                
                // Attach random divisions to the schedule item
                $randomDivisions = $divisions->random(rand(1, min(3, $divisions->count())));
                $scheduleItem->divisions()->attach($randomDivisions->pluck('id'));
                
                $createdCount++;
                $this->command->info("Created: {$scheduleItem->title} - {$scheduleItem->start_time->format('M d, Y g:i A')}");
            } catch (\Exception $e) {
                $this->command->error("Failed to create schedule item: {$itemData['title']} - {$e->getMessage()}");
            }
        }

        $this->command->info("Successfully created {$createdCount} test schedule events!");
        $this->command->info('You can now test the "Add to Calendar" functionality on these events.');
    }
}
