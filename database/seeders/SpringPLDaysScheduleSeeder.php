<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ScheduleItem;
use App\Models\PDDay;
use App\Models\Division;
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

        // Get divisions
        $allSchool = Division::where('name', 'ALL')->first();
        $elementary = Division::where('name', 'ES')->first();
        $middle = Division::where('name', 'MS')->first();
        $high = Division::where('name', 'HS')->first();

        // Day 1 Schedule (March 2, 2026)
        $day1Date = Carbon::parse($springPDDay->start_date);
        
        // Day 2 Schedule (March 3, 2026)
        $day2Date = Carbon::parse($springPDDay->end_date);

        $scheduleItems = [
            // ========== DAY 1 SCHEDULE ==========
            [
                'date' => $day1Date,
                'start_time' => '08:00',
                'end_time' => '08:30',
                'title' => 'Welcome & Breakfast',
                'description' => 'Join us for a light breakfast and networking. Meet your colleagues and get ready for an inspiring day of professional learning.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => 'TLC Team',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '08:30',
                'end_time' => '10:00',
                'title' => 'Opening Keynote: Future-Ready Teaching',
                'description' => 'Explore innovative teaching strategies that prepare students for an ever-changing world. This session will focus on critical thinking, creativity, and collaboration in the modern classroom.',
                'location' => 'Auditorium',
                'presenter_primary' => 'Dr. Sarah Chen',
                'presenter_bio' => 'Educational consultant with 20+ years of experience in curriculum design and innovative pedagogy.',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '10:00',
                'end_time' => '10:15',
                'title' => 'Morning Break',
                'description' => 'Coffee, tea, and light refreshments available.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'Differentiated Instruction Strategies',
                'description' => 'Learn practical techniques to meet the diverse learning needs of all students. We\'ll explore tiered assignments, flexible grouping, and assessment strategies.',
                'location' => 'Room 201',
                'presenter_primary' => 'Ms. Jennifer Martinez',
                'presenter_bio' => 'Elementary curriculum coordinator specializing in inclusive education practices.',
                'divisions' => [$elementary],
            ],
            [
                'date' => $day1Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'Project-Based Learning in Middle School',
                'description' => 'Discover how to design and implement engaging PBL units that connect to real-world problems. Includes templates and rubrics you can use immediately.',
                'location' => 'Room 301',
                'presenter_primary' => 'Mr. David Kim',
                'presenter_bio' => 'Middle school science teacher and PBL expert with national recognition.',
                'divisions' => [$middle],
            ],
            [
                'date' => $day1Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'Advanced Assessment Techniques',
                'description' => 'Explore authentic assessment methods including portfolios, performance tasks, and student-led conferences. Learn to create assessments that truly measure student understanding.',
                'location' => 'Room 401',
                'presenter_primary' => 'Dr. Michael Thompson',
                'presenter_bio' => 'High school principal and assessment specialist with expertise in standards-based grading.',
                'divisions' => [$high],
            ],
            [
                'date' => $day1Date,
                'start_time' => '11:45',
                'end_time' => '12:45',
                'title' => 'Lunch Break',
                'description' => 'Enjoy a catered lunch and continue networking with colleagues.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '12:45',
                'end_time' => '14:15',
                'title' => 'Technology Integration Workshop',
                'description' => 'Hands-on session exploring the latest educational technology tools. Bring your device and learn to use AI tools, interactive whiteboards, and digital collaboration platforms effectively.',
                'location' => 'Computer Lab A',
                'presenter_primary' => 'Ms. Lisa Anderson',
                'presenter_bio' => 'Technology integration specialist and Google Certified Educator.',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '14:15',
                'end_time' => '14:30',
                'title' => 'Afternoon Break',
                'description' => 'Quick refreshment break.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day1Date,
                'start_time' => '14:30',
                'end_time' => '16:00',
                'title' => 'Social-Emotional Learning in Action',
                'description' => 'Practical strategies for integrating SEL into your daily instruction. Learn activities and routines that build emotional intelligence and classroom community.',
                'location' => 'Room 202',
                'presenter_primary' => 'Dr. Emily Rodriguez',
                'presenter_bio' => 'School counselor and SEL curriculum developer with expertise in trauma-informed practices.',
                'divisions' => [$elementary, $middle],
            ],
            [
                'date' => $day1Date,
                'start_time' => '14:30',
                'end_time' => '16:00',
                'title' => 'Critical Thinking in High School',
                'description' => 'Develop students\' analytical skills through Socratic seminars, debate, and inquiry-based learning. Explore frameworks for teaching argumentation and evidence evaluation.',
                'location' => 'Room 402',
                'presenter_primary' => 'Mr. James Wilson',
                'presenter_bio' => 'High school humanities teacher and debate coach with 15 years of experience.',
                'divisions' => [$high],
            ],
            [
                'date' => $day1Date,
                'start_time' => '16:00',
                'end_time' => '16:30',
                'title' => 'Day 1 Reflection & Closing',
                'description' => 'Share key takeaways and set goals for Day 2. Light refreshments provided.',
                'location' => 'Auditorium',
                'presenter_primary' => 'TLC Team',
                'divisions' => [$allSchool],
            ],

            // ========== DAY 2 SCHEDULE ==========
            [
                'date' => $day2Date,
                'start_time' => '08:00',
                'end_time' => '08:30',
                'title' => 'Welcome & Breakfast',
                'description' => 'Start Day 2 with breakfast and morning connections.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => 'TLC Team',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '08:30',
                'end_time' => '10:00',
                'title' => 'Culturally Responsive Teaching',
                'description' => 'Learn to create inclusive classrooms that honor and leverage students\' cultural backgrounds. Explore strategies for building cultural competence and engaging diverse learners.',
                'location' => 'Auditorium',
                'presenter_primary' => 'Dr. Priya Patel',
                'presenter_bio' => 'Educational researcher specializing in multicultural education and equity pedagogy.',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '10:00',
                'end_time' => '10:15',
                'title' => 'Morning Break',
                'description' => 'Coffee, tea, and light refreshments available.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'Literacy Development in Elementary',
                'description' => 'Evidence-based strategies for teaching reading and writing across content areas. Explore phonics, comprehension strategies, and writing workshop models.',
                'location' => 'Room 201',
                'presenter_primary' => 'Ms. Rachel Green',
                'presenter_bio' => 'Elementary literacy coach with expertise in balanced literacy and reading intervention.',
                'divisions' => [$elementary],
            ],
            [
                'date' => $day2Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'Adolescent Development & Engagement',
                'description' => 'Understand the unique needs of middle school students. Learn strategies for managing transitions, building relationships, and maintaining high expectations.',
                'location' => 'Room 301',
                'presenter_primary' => 'Mr. Robert Lee',
                'presenter_bio' => 'Middle school principal and adolescent development researcher.',
                'divisions' => [$middle],
            ],
            [
                'date' => $day2Date,
                'start_time' => '10:15',
                'end_time' => '11:45',
                'title' => 'AP & IB Best Practices',
                'description' => 'Share strategies for preparing students for advanced coursework. Explore curriculum design, assessment practices, and student support systems for AP and IB programs.',
                'location' => 'Room 401',
                'presenter_primary' => 'Ms. Catherine Brown',
                'presenter_bio' => 'IB coordinator and AP teacher with 20+ years of experience in advanced placement programs.',
                'divisions' => [$high],
            ],
            [
                'date' => $day2Date,
                'start_time' => '11:45',
                'end_time' => '12:45',
                'title' => 'Lunch Break',
                'description' => 'Enjoy a catered lunch and continue networking with colleagues.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '12:45',
                'end_time' => '14:15',
                'title' => 'Community Culture and Wellbeing',
                'description' => 'This session will be replaced by your selected wellness session. Please check the Wellness tab to enroll.',
                'location' => 'TBD',
                'presenter_primary' => 'TLC Team',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '14:15',
                'end_time' => '14:30',
                'title' => 'Afternoon Break',
                'description' => 'Quick refreshment break.',
                'location' => 'Main Cafeteria',
                'presenter_primary' => null,
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '14:30',
                'end_time' => '16:00',
                'title' => 'Collaborative Planning Session',
                'description' => 'Work with your grade level or department team to plan upcoming units. Bring your curriculum maps and collaborate on cross-curricular connections.',
                'location' => 'Various Classrooms',
                'presenter_primary' => 'Department Heads',
                'divisions' => [$allSchool],
            ],
            [
                'date' => $day2Date,
                'start_time' => '16:00',
                'end_time' => '16:30',
                'title' => 'Closing & Action Planning',
                'description' => 'Reflect on your learning and create an action plan for implementing new strategies. Share commitments and next steps with colleagues.',
                'location' => 'Auditorium',
                'presenter_primary' => 'TLC Team',
                'divisions' => [$allSchool],
            ],
        ];

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
