<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\PDDay;
use App\Models\ScheduleItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AllSchoolScheduleSeeder extends Seeder
{
    /**
     * Import All School schedule from Whole School Professional Learning Overview March 2 & 3, 2026
     */
    public function run(): void
    {
        $springPDDay = PDDay::spring()->active()->first();
        if (! $springPDDay) {
            $this->command->warn('No active Spring PD Day found.');
            return;
        }

        $allSchool = Division::where('name', 'ALL')->first();
        if (! $allSchool) {
            $this->command->warn('All School division not found.');
            return;
        }

        $scheduleItems = [
            // ========== MONDAY, MARCH 2, 2026 ==========
            ['date' => '2026-03-02', 'start' => '07:45', 'end' => '08:15', 'title' => 'Breakfast snacks', 'location' => 'Main Gym'],
            ['date' => '2026-03-02', 'start' => '08:15', 'end' => '09:00', 'title' => "Director's Welcome", 'location' => 'Main Gym'],
            ['date' => '2026-03-02', 'start' => '09:00', 'end' => '09:15', 'title' => 'Transition', 'location' => null],
            ['date' => '2026-03-02', 'start' => '09:15', 'end' => '10:45', 'title' => 'Divisional', 'location' => null],
            ['date' => '2026-03-02', 'start' => '10:45', 'end' => '11:00', 'title' => 'Break', 'location' => 'HS: Breezeway MS: HOP Foyer ES: Neem Cafe NTS: Reception'],
            ['date' => '2026-03-02', 'start' => '11:00', 'end' => '12:30', 'title' => 'Divisional', 'location' => null],
            ['date' => '2026-03-02', 'start' => '12:30', 'end' => '13:15', 'title' => 'Lunch', 'location' => 'Main Gym'],
            ['date' => '2026-03-02', 'start' => '13:15', 'end' => '13:30', 'title' => 'Transition', 'location' => null],
            ['date' => '2026-03-02', 'start' => '13:30', 'end' => '14:30', 'title' => 'Collaborative Community Learning', 'location' => 'Various locations'],
            ['date' => '2026-03-02', 'start' => '14:30', 'end' => '14:45', 'title' => 'Transition', 'location' => 'HS: Breezeway MS: HOP Foyer ES: Neem Cafe NTS: Reception'],
            ['date' => '2026-03-02', 'start' => '14:45', 'end' => '16:00', 'title' => 'Divisional', 'location' => null],

            // ========== TUESDAY, MARCH 3, 2026 ==========
            ['date' => '2026-03-03', 'start' => '07:45', 'end' => '08:15', 'title' => 'Breakfast snacks', 'location' => 'Main Gym'],
            ['date' => '2026-03-03', 'start' => '08:15', 'end' => '08:30', 'title' => 'Transition', 'location' => null],
            ['date' => '2026-03-03', 'start' => '08:30', 'end' => '09:30', 'title' => 'Vertical Teams', 'location' => null, 'link_url' => 'https://docs.google.com/document/d/1I3zZSLtVd9nlF1sSibNIdlFDKqbsohJDM2gJ50MiEv8/edit?tab=t.0', 'link_title' => 'Vertical Teams'],
            ['date' => '2026-03-03', 'start' => '09:30', 'end' => '09:45', 'title' => 'Break', 'location' => 'HS: Breezeway MS: HOP Foyer ES: Neem Cafe NTS: Reception'],
            ['date' => '2026-03-03', 'start' => '09:45', 'end' => '12:00', 'title' => 'Divisional', 'location' => null],
            ['date' => '2026-03-03', 'start' => '12:00', 'end' => '13:30', 'title' => 'Lunch Service to AES Awards', 'location' => 'Main Gym'],
            ['date' => '2026-03-03', 'start' => '13:30', 'end' => '13:45', 'title' => 'Transition', 'location' => null],
            ['date' => '2026-03-03', 'start' => '13:45', 'end' => '14:45', 'title' => 'Collaborative Community Learning', 'location' => 'Various locations'],
            ['date' => '2026-03-03', 'start' => '14:45', 'end' => '15:00', 'title' => 'Transition', 'location' => null],
            ['date' => '2026-03-03', 'start' => '15:00', 'end' => '16:00', 'title' => 'Belonging and Well-being Session', 'location' => 'Various locations'],
            ['date' => '2026-03-03', 'start' => '16:00', 'end' => '17:00', 'title' => 'Closing Social', 'location' => 'Basketball Court near Gate 2'],
        ];

        $created = 0;
        foreach ($scheduleItems as $item) {
            $existing = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                ->where('date', $item['date'])
                ->where('start_time', Carbon::parse($item['date'].' '.$item['start']))
                ->where('title', $item['title'])
                ->first();

            if ($existing) {
                if (! $existing->divisions()->where('divisions.id', $allSchool->id)->exists()) {
                    $existing->divisions()->syncWithoutDetaching([$allSchool->id]);
                    $this->command->info("Attached All School to existing: {$item['title']} ({$item['date']})");
                } else {
                    $this->command->info("Skipping existing: {$item['title']} ({$item['date']})");
                }
                continue;
            }

            $scheduleItem = ScheduleItem::create([
                'title' => $item['title'],
                'description' => null,
                'location' => $item['location'],
                'start_time' => Carbon::parse($item['date'].' '.$item['start']),
                'end_time' => Carbon::parse($item['date'].' '.$item['end']),
                'date' => $item['date'],
                'presenter_primary' => null,
                'is_active' => true,
                'p_d_day_id' => $springPDDay->id,
                'link_url' => $item['link_url'] ?? null,
                'link_title' => $item['link_title'] ?? null,
            ]);

            $scheduleItem->divisions()->attach($allSchool->id);
            $created++;
            $this->command->info("Created: {$scheduleItem->title} - {$scheduleItem->date->format('M d')} {$scheduleItem->start_time->format('g:i A')}");
        }

        $this->command->info("Successfully created {$created} All School schedule items.");
    }
}
