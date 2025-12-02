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

        $testScheduleItems = [
            [
                'title' => 'Team Building Workshop',
                'description' => 'Join us for an interactive team building session with fun activities and games designed to strengthen collaboration and communication.',
                'location' => 'Conference Room A',
                'date' => Carbon::now()->addDays(3)->toDateString(),
                'start_time' => Carbon::now()->addDays(3)->setTime(10, 0), // 3 days from now at 10 AM
                'end_time' => Carbon::now()->addDays(3)->setTime(12, 0),   // 3 days from now at 12 PM
                'max_participants' => 20,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'Sarah Johnson',
                'session_type' => 'workshop',
            ],
            [
                'title' => 'Monthly All-Hands Meeting',
                'description' => 'Our monthly company-wide meeting where we discuss updates, achievements, and upcoming initiatives.',
                'location' => 'Main Auditorium',
                'date' => Carbon::now()->addDays(7)->toDateString(),
                'start_time' => Carbon::now()->addDays(7)->setTime(14, 0), // 1 week from now at 2 PM
                'end_time' => Carbon::now()->addDays(7)->setTime(15, 30),  // 1 week from now at 3:30 PM
                'max_participants' => 100,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'CEO Team',
                'session_type' => 'meeting',
            ],
            [
                'title' => 'Lunch & Learn: AI in Business',
                'description' => 'Discover how artificial intelligence is transforming modern business practices. Bring your lunch and learn something new!',
                'location' => 'Cafeteria',
                'date' => Carbon::now()->addDays(5)->toDateString(),
                'start_time' => Carbon::now()->addDays(5)->setTime(12, 30), // 5 days from now at 12:30 PM
                'end_time' => Carbon::now()->addDays(5)->setTime(13, 30),   // 5 days from now at 1:30 PM
                'max_participants' => 30,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'Tech Innovation Team',
                'session_type' => 'lunch_learn',
            ],
            [
                'title' => 'Project Kickoff Meeting',
                'description' => 'Kickoff meeting for the new Q4 project. We\'ll discuss timelines, deliverables, and team assignments.',
                'location' => 'Meeting Room B',
                'date' => Carbon::now()->addDays(2)->toDateString(),
                'start_time' => Carbon::now()->addDays(2)->setTime(9, 0),   // 2 days from now at 9 AM
                'end_time' => Carbon::now()->addDays(2)->setTime(11, 0),    // 2 days from now at 11 AM
                'max_participants' => 15,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'Project Manager',
                'session_type' => 'meeting',
            ],
            [
                'title' => 'Office Hours: Career Development',
                'description' => 'Open office hours with HR to discuss career development opportunities, training programs, and growth paths.',
                'location' => 'HR Office',
                'date' => Carbon::now()->addDays(4)->toDateString(),
                'start_time' => Carbon::now()->addDays(4)->setTime(15, 0),  // 4 days from now at 3 PM
                'end_time' => Carbon::now()->addDays(4)->setTime(17, 0),    // 4 days from now at 5 PM
                'max_participants' => 8,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'HR Team',
                'session_type' => 'office_hours',
            ],
            [
                'title' => 'Happy Hour & Networking',
                'description' => 'Join us for drinks and networking with colleagues from different departments. Great opportunity to meet new people!',
                'location' => 'Company Lounge',
                'date' => Carbon::now()->addDays(6)->toDateString(),
                'start_time' => Carbon::now()->addDays(6)->setTime(17, 30), // 6 days from now at 5:30 PM
                'end_time' => Carbon::now()->addDays(6)->setTime(19, 30),   // 6 days from now at 7:30 PM
                'max_participants' => 50,
                'current_enrollment' => 0,
                'is_active' => true,
                'presenter_primary' => 'Social Committee',
                'session_type' => 'social',
            ],
        ];

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
