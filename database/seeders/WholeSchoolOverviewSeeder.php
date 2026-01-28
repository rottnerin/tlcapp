<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\PDDay;
use App\Models\Division;
use Carbon\Carbon;

class WholeSchoolOverviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Spring PD Day (March 2-3, 2026)
        $springPDDay = PDDay::spring()->active()->first();

        if (!$springPDDay) {
            $this->command->warn('No active Spring PD Day found. Please create one first.');
            return;
        }

        // Get divisions
        $elementary = Division::where('name', 'ES')->first();
        $middle = Division::where('name', 'MS')->first();
        $high = Division::where('name', 'HS')->first();
        $nts = Division::where('name', 'NTS')->first();

        // All divisions for "All School" items
        $allDivisions = [$elementary, $middle, $high, $nts];

        // Day 1 and Day 2 dates
        $day1Date = Carbon::parse($springPDDay->start_date);
        $day2Date = Carbon::parse($springPDDay->end_date);

        // Define the schedule items that repeat on both days
        $scheduleTemplate = [
            // 7:45-8:15 - Breakfast snacks (All)
            [
                'start_time' => '07:45',
                'end_time' => '08:15',
                'title' => 'Breakfast snacks',
                'description' => 'Groups: All',
                'location' => 'Main Gym',
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 8:15-9:00 - Director's Welcome (All)
            [
                'start_time' => '08:15',
                'end_time' => '09:00',
                'title' => 'Director\'s Welcome',
                'description' => 'Groups: All',
                'location' => 'Main Gym',
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 9:00-9:15 - Transition (All)
            [
                'start_time' => '09:00',
                'end_time' => '09:15',
                'title' => 'Transition',
                'description' => 'Groups: All',
                'location' => null,
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 9:15-10:45 - Divisional (All Faculty) - 4 separate items
            [
                'start_time' => '09:15',
                'end_time' => '10:45',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Breezeway',
                'presenter_primary' => null,
                'divisions' => [$high],
            ],
            [
                'start_time' => '09:15',
                'end_time' => '10:45',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'HOP Foyer',
                'presenter_primary' => null,
                'divisions' => [$middle],
            ],
            [
                'start_time' => '09:15',
                'end_time' => '10:45',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Neem Cafe',
                'presenter_primary' => null,
                'divisions' => [$elementary],
            ],
            [
                'start_time' => '09:15',
                'end_time' => '10:45',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Reception',
                'presenter_primary' => null,
                'divisions' => [$nts],
            ],
            // 10:45-11:00 - Break (All Faculty)
            [
                'start_time' => '10:45',
                'end_time' => '11:00',
                'title' => 'Break',
                'description' => 'Groups: All Faculty',
                'location' => null,
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 11:00-12:30 - Divisional (All Faculty) - 4 separate items
            [
                'start_time' => '11:00',
                'end_time' => '12:30',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Breezeway',
                'presenter_primary' => null,
                'divisions' => [$high],
            ],
            [
                'start_time' => '11:00',
                'end_time' => '12:30',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'HOP Foyer',
                'presenter_primary' => null,
                'divisions' => [$middle],
            ],
            [
                'start_time' => '11:00',
                'end_time' => '12:30',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Neem Cafe',
                'presenter_primary' => null,
                'divisions' => [$elementary],
            ],
            [
                'start_time' => '11:00',
                'end_time' => '12:30',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Reception',
                'presenter_primary' => null,
                'divisions' => [$nts],
            ],
            // 12:30-1:15 - Lunch (All)
            [
                'start_time' => '12:30',
                'end_time' => '13:15',
                'title' => 'Lunch',
                'description' => 'Groups: All',
                'location' => 'Main Gym',
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 1:15-1:30 - Transition (All)
            [
                'start_time' => '13:15',
                'end_time' => '13:30',
                'title' => 'Transition',
                'description' => 'Groups: All',
                'location' => null,
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 1:30-2:30 - Collaborative Community Learning (All Faculty) - 4 separate items
            [
                'start_time' => '13:30',
                'end_time' => '14:30',
                'title' => 'Collaborative Community Learning',
                'description' => 'Groups: All Faculty',
                'location' => 'Breezeway',
                'presenter_primary' => null,
                'divisions' => [$high],
            ],
            [
                'start_time' => '13:30',
                'end_time' => '14:30',
                'title' => 'Collaborative Community Learning',
                'description' => 'Groups: All Faculty',
                'location' => 'HOP Foyer',
                'presenter_primary' => null,
                'divisions' => [$middle],
            ],
            [
                'start_time' => '13:30',
                'end_time' => '14:30',
                'title' => 'Collaborative Community Learning',
                'description' => 'Groups: All Faculty',
                'location' => 'Neem Cafe',
                'presenter_primary' => null,
                'divisions' => [$elementary],
            ],
            [
                'start_time' => '13:30',
                'end_time' => '14:30',
                'title' => 'Collaborative Community Learning',
                'description' => 'Groups: All Faculty',
                'location' => 'Reception',
                'presenter_primary' => null,
                'divisions' => [$nts],
            ],
            // 2:30-2:45 - Transition (All Faculty)
            [
                'start_time' => '14:30',
                'end_time' => '14:45',
                'title' => 'Transition',
                'description' => 'Groups: All Faculty',
                'location' => null,
                'presenter_primary' => null,
                'divisions' => $allDivisions,
            ],
            // 2:45-4:00 - Divisional (All Faculty) - 4 separate items
            [
                'start_time' => '14:45',
                'end_time' => '16:00',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Breezeway',
                'presenter_primary' => null,
                'divisions' => [$high],
            ],
            [
                'start_time' => '14:45',
                'end_time' => '16:00',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'HOP Foyer',
                'presenter_primary' => null,
                'divisions' => [$middle],
            ],
            [
                'start_time' => '14:45',
                'end_time' => '16:00',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Neem Cafe',
                'presenter_primary' => null,
                'divisions' => [$elementary],
            ],
            [
                'start_time' => '14:45',
                'end_time' => '16:00',
                'title' => 'Divisional',
                'description' => 'Groups: All Faculty',
                'location' => 'Reception',
                'presenter_primary' => null,
                'divisions' => [$nts],
            ],
        ];

        $createdCount = 0;
        $skippedCount = 0;

        // Create items for both Day 1 and Day 2
        foreach ([$day1Date, $day2Date] as $date) {
            foreach ($scheduleTemplate as $itemData) {
                // Check if item already exists
                $existing = ScheduleItem::where('p_d_day_id', $springPDDay->id)
                    ->where('date', $date)
                    ->where('start_time', Carbon::parse($date->format('Y-m-d') . ' ' . $itemData['start_time']))
                    ->where('title', $itemData['title'])
                    ->where('location', $itemData['location'])
                    ->first();

                if ($existing) {
                    $this->command->info("Skipping existing item: {$itemData['title']} at {$itemData['location']} on {$date->format('M d')}");
                    $skippedCount++;
                    continue;
                }

                // Create schedule item
                $scheduleItem = ScheduleItem::create([
                    'title' => $itemData['title'],
                    'description' => $itemData['description'],
                    'location' => $itemData['location'],
                    'start_time' => Carbon::parse($date->format('Y-m-d') . ' ' . $itemData['start_time']),
                    'end_time' => Carbon::parse($date->format('Y-m-d') . ' ' . $itemData['end_time']),
                    'date' => $date,
                    'presenter_primary' => $itemData['presenter_primary'],
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
                $location = $itemData['location'] ?? 'N/A';
                $this->command->info("Created: {$scheduleItem->title} ({$location}) - {$scheduleItem->date->format('M d')} {$scheduleItem->start_time->format('g:i A')}");
            }
        }

        $this->command->info("\n✅ Successfully created {$createdCount} schedule items for Whole School Overview.");
        if ($skippedCount > 0) {
            $this->command->info("⏭️  Skipped {$skippedCount} existing items.");
        }
    }
}
