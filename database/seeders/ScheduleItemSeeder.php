<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\CCLSession;
use App\Models\PDDay;
use App\Models\Division;
use Carbon\Carbon;

class ScheduleItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get spring PD day
        $springPDDay = PDDay::spring()->active()->first();

        if (!$springPDDay) {
            $this->command->warn('No active Spring PD Day found. Please run PDDaySeeder or CCLSessionSeeder first.');
            return;
        }

        // Get all CCL sessions for this PD day
        $tttSessions = CCLSession::where('p_d_day_id', $springPDDay->id)
            ->where('is_active', true)
            ->get();

        if ($tttSessions->isEmpty()) {
            $this->command->warn('No CCL sessions found for Spring PD Day. Please run CCLSessionSeeder first.');
            return;
        }

        $createdCount = 0;

        foreach ($tttSessions as $tttSession) {
            // Check if schedule item already exists for this CCL session
            $existingItem = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $tttSession->date)
                ->where('start_time', $tttSession->start_time)
                ->where('title', $tttSession->title)
                ->first();

            if ($existingItem) {
                $this->command->info("Schedule item already exists: {$tttSession->title}");
                continue;
            }

            // Create schedule item from CCL session
            $scheduleItem = ScheduleItem::create([
                'title' => $tttSession->title,
                'description' => $tttSession->description,
                'location' => $tttSession->location,
                'start_time' => $tttSession->start_time,
                'end_time' => $tttSession->end_time,
                'date' => $tttSession->date,
                'presenter_primary' => $tttSession->presenter_name,
                'presenter_secondary' => $tttSession->co_presenter_name,
                'presenter_bio' => $tttSession->presenter_bio,
                'is_active' => true,
                'session_type' => 'ccl',
                'p_d_day_id' => $springPDDay->id,
            ]);

            // Attach division if CCL session has one
            if ($tttSession->division_id) {
                $scheduleItem->divisions()->attach($tttSession->division_id);
            } else {
                // If no division specified, attach all divisions
                $allDivisions = Division::all();
                $scheduleItem->divisions()->attach($allDivisions->pluck('id'));
            }

            $createdCount++;
            $this->command->info("Created schedule item: {$scheduleItem->title} - {$scheduleItem->start_time->format('M d, Y g:i A')}");
        }

        $this->command->info("Successfully created {$createdCount} schedule items from CCL sessions.");
    }
}
