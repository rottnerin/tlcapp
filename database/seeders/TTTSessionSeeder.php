<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TTTSession;
use App\Models\TTTSetting;
use App\Models\PDDay;
use App\Models\Division;
use Carbon\Carbon;

class TTTSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure TTT is active
        $settings = TTTSetting::getSettings();
        $settings->is_active = true;
        $settings->save();

        // Get or create a spring PD day
        $springPDDay = PDDay::spring()->active()->first();
        
        if (!$springPDDay) {
            // Create a spring PD day if it doesn't exist
            $springPDDay = PDDay::create([
                'title' => 'Spring 2026 Professional Learning Days',
                'description' => 'Spring professional development sessions including TTT.',
                'start_date' => Carbon::parse('2026-03-01'),
                'end_date' => Carbon::parse('2026-03-02'),
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

        // Create fake TTT sessions
        $sessions = [
            [
                'title' => 'Innovative Assessment Strategies',
                'description' => 'Learn creative ways to assess student learning beyond traditional tests. Explore project-based assessments, portfolios, and authentic evaluation methods that engage students and provide meaningful feedback.',
                'presenter_name' => 'Sarah Johnson',
                'presenter_email' => 'sarah.johnson@aes.ac.in',
                'presenter_bio' => 'Sarah has been teaching for 15 years and specializes in assessment design and student-centered learning.',
                'co_presenter_name' => 'Michael Chen',
                'co_presenter_email' => 'michael.chen@aes.ac.in',
                'location' => 'Room 201',
                'date' => Carbon::parse('2026-03-01'),
                'start_time' => Carbon::parse('2026-03-01 09:00'),
                'end_time' => Carbon::parse('2026-03-01 10:30'),
                'contact_hours' => 1.5,
                'division_id' => $hsDivision?->id,
                'category' => ['Assessment', 'Teaching Strategies'],
                'is_active' => true,
            ],
            [
                'title' => 'Technology Integration in the Elementary Classroom',
                'description' => 'Discover age-appropriate technology tools and strategies for enhancing learning in grades PreK-5. Hands-on activities and practical applications you can use immediately.',
                'presenter_name' => 'Emily Rodriguez',
                'presenter_email' => 'emily.rodriguez@aes.ac.in',
                'presenter_bio' => 'Emily is a technology integration specialist with expertise in early childhood education.',
                'location' => 'Computer Lab A',
                'date' => Carbon::parse('2026-03-01'),
                'start_time' => Carbon::parse('2026-03-01 10:45'),
                'end_time' => Carbon::parse('2026-03-01 12:15'),
                'contact_hours' => 1.5,
                'division_id' => $esDivision?->id,
                'category' => ['Technology', 'Elementary Education'],
                'is_active' => true,
            ],
            [
                'title' => 'Differentiated Instruction for Middle School',
                'description' => 'Explore practical strategies for meeting the diverse needs of middle school learners. Learn how to adapt content, process, and products to support all students.',
                'presenter_name' => 'David Kim',
                'presenter_email' => 'david.kim@aes.ac.in',
                'presenter_bio' => 'David has taught middle school for 12 years and is passionate about inclusive education.',
                'co_presenter_name' => 'Lisa Thompson',
                'co_presenter_email' => 'lisa.thompson@aes.ac.in',
                'location' => 'Room 305',
                'date' => Carbon::parse('2026-03-01'),
                'start_time' => Carbon::parse('2026-03-01 13:00'),
                'end_time' => Carbon::parse('2026-03-01 14:30'),
                'contact_hours' => 1.5,
                'division_id' => $msDivision?->id,
                'category' => ['Differentiation', 'Middle School'],
                'is_active' => true,
            ],
            [
                'title' => 'Project-Based Learning in High School',
                'description' => 'Learn how to design and implement engaging project-based learning experiences that connect curriculum to real-world applications. Includes examples from various subject areas.',
                'presenter_name' => 'James Wilson',
                'presenter_email' => 'james.wilson@aes.ac.in',
                'presenter_bio' => 'James is a veteran high school teacher who has implemented PBL across multiple disciplines.',
                'location' => 'Room 401',
                'date' => Carbon::parse('2026-03-01'),
                'start_time' => Carbon::parse('2026-03-01 14:45'),
                'end_time' => Carbon::parse('2026-03-01 16:15'),
                'contact_hours' => 1.5,
                'division_id' => $hsDivision?->id,
                'category' => ['Project-Based Learning', 'High School'],
                'is_active' => true,
            ],
            [
                'title' => 'Social-Emotional Learning Strategies',
                'description' => 'Build your toolkit for supporting student social-emotional development. Practical activities and resources for creating a positive classroom climate.',
                'presenter_name' => 'Amanda Patel',
                'presenter_email' => 'amanda.patel@aes.ac.in',
                'presenter_bio' => 'Amanda is a school counselor with expertise in SEL and student well-being.',
                'location' => 'Room 102',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 09:00'),
                'end_time' => Carbon::parse('2026-03-02 10:30'),
                'contact_hours' => 1.5,
                'division_id' => null, // All divisions
                'category' => ['SEL', 'Wellness'],
                'is_active' => true,
            ],
            [
                'title' => 'Reading Comprehension Strategies',
                'description' => 'Explore evidence-based strategies for improving reading comprehension across all grade levels. Learn techniques for before, during, and after reading activities.',
                'presenter_name' => 'Maria Garcia',
                'presenter_email' => 'maria.garcia@aes.ac.in',
                'presenter_bio' => 'Maria is a literacy specialist with 20 years of experience in reading instruction.',
                'location' => 'Library',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 10:45'),
                'end_time' => Carbon::parse('2026-03-02 12:15'),
                'contact_hours' => 1.5,
                'division_id' => $esDivision?->id,
                'category' => ['Literacy', 'Reading'],
                'is_active' => true,
            ],
            [
                'title' => 'Collaborative Learning in Middle School',
                'description' => 'Discover how to structure effective group work and collaborative learning experiences. Learn strategies for managing groups, assigning roles, and ensuring accountability.',
                'presenter_name' => 'Robert Taylor',
                'presenter_email' => 'robert.taylor@aes.ac.in',
                'presenter_bio' => 'Robert specializes in cooperative learning and has presented at multiple conferences.',
                'location' => 'Room 306',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 13:00'),
                'end_time' => Carbon::parse('2026-03-02 14:30'),
                'contact_hours' => 1.5,
                'division_id' => $msDivision?->id,
                'category' => ['Collaborative Learning', 'Middle School'],
                'is_active' => true,
            ],
            [
                'title' => 'Advanced Placement Strategies',
                'description' => 'Share best practices for teaching AP courses and preparing students for AP exams. Discussion of curriculum pacing, assessment strategies, and student support.',
                'presenter_name' => 'Jennifer Lee',
                'presenter_email' => 'jennifer.lee@aes.ac.in',
                'presenter_bio' => 'Jennifer has taught AP courses for 10 years and serves as an AP exam reader.',
                'co_presenter_name' => 'Thomas Anderson',
                'co_presenter_email' => 'thomas.anderson@aes.ac.in',
                'location' => 'Room 402',
                'date' => Carbon::parse('2026-03-02'),
                'start_time' => Carbon::parse('2026-03-02 14:45'),
                'end_time' => Carbon::parse('2026-03-02 16:15'),
                'contact_hours' => 1.5,
                'division_id' => $hsDivision?->id,
                'category' => ['AP', 'High School'],
                'is_active' => true,
            ],
        ];

        foreach ($sessions as $sessionData) {
            $sessionData['p_d_day_id'] = $springPDDay->id;
            // Convert start_time and end_time to time format (H:i:s)
            $startTime = $sessionData['start_time'];
            $endTime = $sessionData['end_time'];
            $sessionData['start_time'] = $startTime->format('H:i:s');
            $sessionData['end_time'] = $endTime->format('H:i:s');
            
            TTTSession::create($sessionData);
        }

        $this->command->info('Created ' . count($sessions) . ' TTT sessions.');
    }
}
