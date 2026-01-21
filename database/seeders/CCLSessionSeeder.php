<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CCLSession;
use App\Models\CCLSetting;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\ScheduleItem;
use Carbon\Carbon;

class CCLSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure CCL is active
        $settings = CCLSetting::getSettings();
        $settings->is_active = true;
        $settings->save();

        // Get or create a spring PD day
        $springPDDay = PDDay::spring()->active()->first();
        
        if (!$springPDDay) {
            // Create a spring PD day if it doesn't exist
            $springPDDay = PDDay::create([
                'title' => 'Spring 2026 Professional Learning Days',
                'description' => 'Spring professional development sessions including CCL.',
                'start_date' => Carbon::parse('2026-03-02'),
                'end_date' => Carbon::parse('2026-03-03'),
                'is_active' => true,
                'season' => 'spring',
                'academic_year' => PDDay::getCurrentAcademicYear(),
            ]);
        }

        // Get divisions
        $divisions = Division::all();
        $esDivision = Division::where('name', 'ES')->first();
        $msDivision = Division::where('name', 'MS')->first();
        $hsDivision = Division::where('name', 'HS')->first();

        // Create CCL sessions for the two time slots
        // CCL Session 1: March 2nd, 1:30 PM - 2:30 PM
        // CCL Session 2: March 3rd, 1:45 PM - 2:45 PM
        $sessions = [
            // ===== CCL Session 1: March 2nd, 1:30 PM - 2:30 PM =====
            [
                'title' => 'Innovative Assessment Strategies',
                'description' => 'Learn creative ways to assess student learning beyond traditional tests. Explore project-based assessments, portfolios, and authentic evaluation methods that engage students and provide meaningful feedback.',
                'presenter_name' => 'Sarah Johnson',
                'presenter_email' => 'sarah.johnson@aes.ac.in',
                'presenter_bio' => 'Sarah has been teaching for 15 years and specializes in assessment design and student-centered learning.',
                'co_presenter_name' => 'Michael Chen',
                'co_presenter_email' => 'michael.chen@aes.ac.in',
                'location' => 'Room 201',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 13:30'),
                'end_time' => Carbon::parse('2026-03-02 14:30'),
                'contact_hours' => 1.0,
                'division_id' => $hsDivision?->id,
                'category' => ['Assessment', 'Teaching Strategies'],
                'is_active' => true,
            ],
            [
                'title' => 'Technology Integration in the Classroom',
                'description' => 'Discover age-appropriate technology tools and strategies for enhancing learning. Hands-on activities and practical applications you can use immediately.',
                'presenter_name' => 'Emily Rodriguez',
                'presenter_email' => 'emily.rodriguez@aes.ac.in',
                'presenter_bio' => 'Emily is a technology integration specialist with expertise in innovative teaching methods.',
                'location' => 'Computer Lab A',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 13:30'),
                'end_time' => Carbon::parse('2026-03-02 14:30'),
                'contact_hours' => 1.0,
                'division_id' => $esDivision?->id,
                'category' => ['Technology', 'Teaching Strategies'],
                'is_active' => true,
            ],
            [
                'title' => 'Differentiated Instruction Techniques',
                'description' => 'Explore practical strategies for meeting the diverse needs of learners. Learn how to adapt content, process, and products to support all students.',
                'presenter_name' => 'David Kim',
                'presenter_email' => 'david.kim@aes.ac.in',
                'presenter_bio' => 'David has taught for 12 years and is passionate about inclusive education.',
                'co_presenter_name' => 'Lisa Thompson',
                'co_presenter_email' => 'lisa.thompson@aes.ac.in',
                'location' => 'Room 305',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 13:30'),
                'end_time' => Carbon::parse('2026-03-02 14:30'),
                'contact_hours' => 1.0,
                'division_id' => $msDivision?->id,
                'category' => ['Differentiation', 'Teaching Strategies'],
                'is_active' => true,
            ],

            // ===== CCL Session 2: March 3rd (Tuesday), 1:45 PM - 2:45 PM =====
            [
                'title' => 'Project-Based Learning Essentials',
                'description' => 'Learn how to design and implement engaging project-based learning experiences that connect curriculum to real-world applications. Includes examples from various subject areas.',
                'presenter_name' => 'James Wilson',
                'presenter_email' => 'james.wilson@aes.ac.in',
                'presenter_bio' => 'James is a veteran teacher who has implemented PBL across multiple disciplines.',
                'location' => 'Room 401',
                'date' => Carbon::parse('2026-03-03'),
                'start_time' => Carbon::parse('2026-03-03 13:45'),
                'end_time' => Carbon::parse('2026-03-03 14:45'),
                'contact_hours' => 1.0,
                'division_id' => $hsDivision?->id,
                'category' => ['Project-Based Learning', 'Teaching Strategies'],
                'is_active' => true,
            ],
            [
                'title' => 'Social-Emotional Learning in Practice',
                'description' => 'Build your toolkit for supporting student social-emotional development. Practical activities and resources for creating a positive classroom climate.',
                'presenter_name' => 'Amanda Patel',
                'presenter_email' => 'amanda.patel@aes.ac.in',
                'presenter_bio' => 'Amanda is a school counselor with expertise in SEL and student well-being.',
                'location' => 'Room 102',
                'date' => Carbon::parse('2026-03-03'),
                'start_time' => Carbon::parse('2026-03-03 13:45'),
                'end_time' => Carbon::parse('2026-03-03 14:45'),
                'contact_hours' => 1.0,
                'division_id' => null, // All divisions
                'category' => ['SEL', 'Wellness'],
                'is_active' => true,
            ],
            [
                'title' => 'Collaborative Learning Strategies',
                'description' => 'Discover how to structure effective group work and collaborative learning experiences. Learn strategies for managing groups, assigning roles, and ensuring accountability.',
                'presenter_name' => 'Robert Taylor',
                'presenter_email' => 'robert.taylor@aes.ac.in',
                'presenter_bio' => 'Robert specializes in cooperative learning and has presented at multiple conferences.',
                'location' => 'Room 306',
                'date' => Carbon::parse('2026-03-03'),
                'start_time' => Carbon::parse('2026-03-03 13:45'),
                'end_time' => Carbon::parse('2026-03-03 14:45'),
                'contact_hours' => 1.0,
                'division_id' => $msDivision?->id,
                'category' => ['Collaborative Learning', 'Teaching Strategies'],
                'is_active' => true,
            ],
        ];

        foreach ($sessions as $index => $sessionData) {
            $sessionData['p_d_day_id'] = $springPDDay->id;
            // Store original Carbon instances for ScheduleItem
            $date = $sessionData['date'];
            $startTime = $sessionData['start_time'];
            $endTime = $sessionData['end_time'];
            $divisionId = $sessionData['division_id'];
            
            // Convert start_time and end_time to time format (H:i:s) for CCLSession
            $sessionData['start_time'] = $startTime->format('H:i:s');
            $sessionData['end_time'] = $endTime->format('H:i:s');
            
            $tttSession = CCLSession::create($sessionData);
            
            // Create corresponding ScheduleItem with capacity
            // Varying capacities for testing: 15, 20, or 25
            $capacities = [20, 25, 15, 20, 25, 15];
            $maxParticipants = $capacities[$index % count($capacities)];
            
            // Add some random enrollments for testing display
            $currentEnrollment = rand(0, min(12, $maxParticipants));
            
            // Create schedule item with proper datetime format (date + time combined)
            $scheduleItem = ScheduleItem::create([
                'p_d_day_id' => $springPDDay->id,
                'title' => $sessionData['title'],
                'description' => $sessionData['description'],
                'presenter_primary' => $sessionData['presenter_name'],
                'presenter_secondary' => $sessionData['co_presenter_name'] ?? null,
                'date' => $date->format('Y-m-d'),
                'start_time' => $date->format('Y-m-d') . ' ' . $startTime->format('H:i:s'),
                'end_time' => $date->format('Y-m-d') . ' ' . $endTime->format('H:i:s'),
                'location' => $sessionData['location'],
                'session_type' => 'ccl',
                'max_participants' => $maxParticipants,
                'current_enrollment' => $currentEnrollment,
                'is_active' => true,
            ]);
            
            // Attach division if specified, otherwise attach all divisions
            if ($divisionId) {
                $scheduleItem->divisions()->attach($divisionId);
            } else {
                // Attach all divisions if no specific division
                $scheduleItem->divisions()->attach(Division::all()->pluck('id'));
            }
        }

        $this->command->info('Created ' . count($sessions) . ' CCL sessions with schedule items.');
    }
}
