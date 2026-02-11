<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\PDDay;
use App\Models\ScheduleItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NTSScheduleSeeder extends Seeder
{
    /**
     * Create NTS schedule items - same sessions as All School, at NTS-specific times.
     * NTS times: Mon 9:15-10:30, 11:00-12:15, 2:45-4:00 | Tue 9:15-10:30, 11:00-12:15
     * Also creates 5 Optional Sign-up slots (user picks one).
     */
    public function run(): void
    {
        $springPDDay = PDDay::spring()->active()->first();
        if (! $springPDDay) {
            $this->command->warn('No active Spring PD Day found.');
            return;
        }

        $ntsDivision = Division::where('name', 'NTS')->first();
        if (! $ntsDivision) {
            $this->command->warn('NTS division not found. Run DivisionSeeder first.');
            return;
        }

        $mon = Carbon::parse($springPDDay->start_date)->format('Y-m-d');
        $tue = Carbon::parse($springPDDay->end_date)->format('Y-m-d');

        $scheduleItems = [
            // Monday - same sessions as All School, NTS times
            ['date' => $mon, 'start' => '07:45', 'end' => '08:15', 'title' => 'Breakfast snacks', 'location' => 'Main Gym'],
            ['date' => $mon, 'start' => '08:15', 'end' => '09:00', 'title' => "Director's Welcome", 'location' => 'Main Gym'],
            ['date' => $mon, 'start' => '09:00', 'end' => '09:15', 'title' => 'Transition', 'location' => null],
            ['date' => $mon, 'start' => '09:15', 'end' => '10:30', 'title' => 'Divisional', 'location' => 'Reception'],
            ['date' => $mon, 'start' => '10:30', 'end' => '11:00', 'title' => 'Break', 'location' => 'Reception'],
            ['date' => $mon, 'start' => '11:00', 'end' => '12:15', 'title' => 'Divisional', 'location' => null],
            ['date' => $mon, 'start' => '12:15', 'end' => '13:15', 'title' => 'Lunch', 'location' => 'Main Gym'],
            ['date' => $mon, 'start' => '13:15', 'end' => '13:30', 'title' => 'Transition', 'location' => null],
            ['date' => $mon, 'start' => '13:30', 'end' => '14:30', 'title' => 'Collaborative Community Learning', 'location' => 'Various locations'],
            ['date' => $mon, 'start' => '14:30', 'end' => '14:45', 'title' => 'Transition', 'location' => 'Reception'],
            ['date' => $mon, 'start' => '14:45', 'end' => '16:00', 'title' => 'Divisional', 'location' => null],

            // Tuesday - same sessions as All School, NTS times
            ['date' => $tue, 'start' => '07:45', 'end' => '08:15', 'title' => 'Breakfast snacks', 'location' => 'Main Gym'],
            ['date' => $tue, 'start' => '08:15', 'end' => '08:30', 'title' => 'Transition', 'location' => null],
            ['date' => $tue, 'start' => '08:30', 'end' => '09:15', 'title' => 'Vertical Teams', 'location' => null, 'link_url' => 'https://docs.google.com/document/d/1I3zZSLtVd9nlF1sSibNIdlFDKqbsohJDM2gJ50MiEv8/edit?tab=t.0', 'link_title' => 'Vertical Teams'],
            ['date' => $tue, 'start' => '09:15', 'end' => '10:30', 'title' => 'Divisional', 'location' => null],
            ['date' => $tue, 'start' => '10:30', 'end' => '11:00', 'title' => 'Break', 'location' => 'Reception'],
            ['date' => $tue, 'start' => '11:00', 'end' => '12:15', 'title' => 'Lunch Service to AES Awards', 'location' => 'Main Gym'],
            ['date' => $tue, 'start' => '12:15', 'end' => '12:30', 'title' => 'Transition', 'location' => null],
            ['date' => $tue, 'start' => '12:30', 'end' => '13:45', 'title' => 'Collaborative Community Learning', 'location' => 'Various locations'],
            ['date' => $tue, 'start' => '13:45', 'end' => '14:00', 'title' => 'Transition', 'location' => null],
            ['date' => $tue, 'start' => '14:00', 'end' => '16:00', 'title' => 'Belonging and Well-being Session', 'location' => 'Various locations'],
            ['date' => $tue, 'start' => '16:00', 'end' => '17:00', 'title' => 'Closing Social', 'location' => 'Basketball Court near Gate 2'],
        ];

        $created = 0;
        foreach ($scheduleItems as $item) {
            $existing = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $item['date'])
                ->where('start_time', Carbon::parse($item['date'] . ' ' . $item['start']))
                ->where('title', $item['title'])
                ->whereHas('divisions', fn ($q) => $q->where('divisions.name', 'NTS'))
                ->first();

            if ($existing) {
                $this->command->info("Skipping existing: {$item['title']} ({$item['date']})");
                continue;
            }

            $scheduleItem = ScheduleItem::create([
                'title' => $item['title'],
                'description' => null,
                'location' => $item['location'] ?? null,
                'start_time' => Carbon::parse($item['date'] . ' ' . $item['start']),
                'end_time' => Carbon::parse($item['date'] . ' ' . $item['end']),
                'date' => $item['date'],
                'presenter_primary' => null,
                'is_active' => true,
                'session_type' => 'regular',
                'p_d_day_id' => $springPDDay->id,
                'link_url' => $item['link_url'] ?? null,
                'link_title' => $item['link_title'] ?? null,
            ]);

            $scheduleItem->divisions()->attach($ntsDivision->id);
            $created++;
            $this->command->info("Created: {$scheduleItem->title} - {$scheduleItem->date->format('M d')} {$scheduleItem->start_time->format('g:i A')}");
        }

        $optionalSlots = [
            ['date' => $mon, 'start' => '09:15', 'end' => '10:30'],
            ['date' => $mon, 'start' => '11:00', 'end' => '12:15'],
            ['date' => $mon, 'start' => '14:45', 'end' => '16:00'],
            ['date' => $tue, 'start' => '09:15', 'end' => '10:30'],
            ['date' => $tue, 'start' => '11:00', 'end' => '12:15'],
        ];

        foreach ($optionalSlots as $slot) {
            $existing = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $slot['date'])
                ->where('start_time', Carbon::parse($slot['date'] . ' ' . $slot['start']))
                ->where('session_type', 'nts_optional')
                ->first();

            if ($existing) {
                $this->command->info("Skipping existing Optional Sign-up: {$slot['date']} {$slot['start']}");
                continue;
            }

            $scheduleItem = ScheduleItem::create([
                'title' => 'Recognizing and Managing Stress: Practical Strategies for Everyday Well-Being',
                'description' => 'This session helps participants develop practical and sustainable strategies for recognizing, addressing, and managing stress in their daily lives. Participants will build an understanding of the physical signs of stress and learn how to identify stress at an early stage. The session will also introduce a toolkit of simple, in-the-moment practices that can be used independently, as well as routines that strengthen proactive and long-term stress management.',
                'location' => null,
                'start_time' => Carbon::parse($slot['date'] . ' ' . $slot['start']),
                'end_time' => Carbon::parse($slot['date'] . ' ' . $slot['end']),
                'date' => $slot['date'],
                'presenter_primary' => null,
                'is_active' => true,
                'session_type' => 'nts_optional',
                'p_d_day_id' => $springPDDay->id,
                'max_participants' => null,
            ]);

            $scheduleItem->divisions()->attach($ntsDivision->id);
            $created++;
            $this->command->info("Created Optional Sign-up: {$scheduleItem->date->format('M d')} {$scheduleItem->start_time->format('g:i A')}");
        }

        $this->command->info("Successfully created {$created} NTS schedule items.");
    }
}
