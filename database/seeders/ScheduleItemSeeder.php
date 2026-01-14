<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\TTTSession;
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
            $this->command->warn('No active Spring PD Day found. Please run PDDaySeeder or TTTSessionSeeder first.');
            return;
        }

        // Get all TTT sessions for this PD day
        $tttSessions = TTTSession::where('p_d_day_id', $springPDDay->id)
            ->where('is_active', true)
            ->get();

        if ($tttSessions->isEmpty()) {
            $this->command->warn('No TTT sessions found for Spring PD Day. Please run TTTSessionSeeder first.');
            return;
        }

        $createdCount = 0;

        foreach ($tttSessions as $tttSession) {
            // Check if schedule item already exists for this TTT session
            $existingItem = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $tttSession->date)
                ->where('start_time', $tttSession->start_time)
                ->where('title', $tttSession->title)
                ->first();

            if ($existingItem) {
                $this->command->info("Schedule item already exists: {$tttSession->title}");
                continue;
            }

            // Create schedule item from TTT session
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
                'session_type' => 'ttt',
                'p_d_day_id' => $springPDDay->id,
            ]);

            // Attach division if TTT session has one
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

        $this->command->info("Successfully created {$createdCount} schedule items from TTT sessions.");
    }
}
