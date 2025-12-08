<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PLWednesdaySession;
use App\Models\PLWednesdayLink;
use App\Models\PLWednesdaySetting;
use Carbon\Carbon;

class PLWednesdaySessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Initialize settings if they don't exist
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();

        // Get all Wednesdays between start and end date
        $wednesdays = $this->getWednesdayDates($settings->start_date, $settings->end_date);

        // Sample sessions for different divisions
        $sessions = [
            // August sessions
            [
                'date' => $wednesdays[0] ?? '2025-08-06', // First Wednesday
                'title' => 'ES: Reading Strategies Workshop',
                'description' => 'Join us for an interactive workshop on effective reading strategies for elementary students. We will explore phonics, comprehension techniques, and engaging activities.',
                'location' => 'ES Library',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
                'links' => [
                    ['title' => 'Reading Resources', 'url' => 'https://example.com/reading-resources', 'description' => 'Additional reading materials'],
                    ['title' => 'Workshop Slides', 'url' => 'https://example.com/slides', 'description' => 'Presentation slides'],
                ]
            ],
            [
                'date' => $wednesdays[0] ?? '2025-08-06',
                'title' => 'MS: Math Problem Solving Techniques',
                'description' => 'Learn innovative approaches to teaching problem-solving in middle school mathematics. Hands-on activities and collaborative strategies.',
                'location' => 'MS Room 205',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
                'links' => [
                    ['title' => 'Math Resources', 'url' => 'https://example.com/math-resources'],
                ]
            ],
            [
                'date' => $wednesdays[0] ?? '2025-08-06',
                'title' => 'HS: Advanced Placement Strategies',
                'description' => 'Discussion on best practices for AP course preparation and student success. Share experiences and strategies.',
                'location' => 'HS Conference Room',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'HS',
            ],
            // Second Wednesday
            [
                'date' => $wednesdays[1] ?? '2025-08-13',
                'title' => 'ES: Science Experiments for Young Learners',
                'description' => 'Explore safe and engaging science experiments suitable for elementary classrooms. Hands-on demonstrations included.',
                'location' => 'ES Science Lab',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
                'links' => [
                    ['title' => 'Experiment Guide', 'url' => 'https://example.com/experiments'],
                    ['title' => 'Safety Protocols', 'url' => 'https://example.com/safety'],
                ]
            ],
            [
                'date' => $wednesdays[1] ?? '2025-08-13',
                'title' => 'MS: Technology Integration in Classroom',
                'description' => 'Learn how to effectively integrate technology tools into your middle school curriculum. Practical examples and demonstrations.',
                'location' => 'MS Computer Lab',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
            ],
            [
                'date' => $wednesdays[1] ?? '2025-08-13',
                'title' => 'HS: College Preparation Workshop',
                'description' => 'Guidance on helping students prepare for college applications, essays, and interviews. Resources and best practices shared.',
                'location' => 'HS Guidance Office',
                'start_time' => '15:30:00',
                'end_time' => '17:30:00',
                'division' => 'HS',
                'links' => [
                    ['title' => 'College Resources', 'url' => 'https://example.com/college'],
                    ['title' => 'Application Timeline', 'url' => 'https://example.com/timeline'],
                    ['title' => 'Scholarship Info', 'url' => 'https://example.com/scholarships'],
                ]
            ],
            // Third Wednesday
            [
                'date' => $wednesdays[2] ?? '2025-08-20',
                'title' => 'ES: Art Integration Across Curriculum',
                'description' => 'Discover creative ways to incorporate art into various subjects. Enhance student engagement through artistic expression.',
                'location' => 'ES Art Room',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
            ],
            [
                'date' => $wednesdays[2] ?? '2025-08-20',
                'title' => 'MS: Social-Emotional Learning Strategies',
                'description' => 'Workshop on implementing SEL practices in middle school. Activities and resources for supporting student well-being.',
                'location' => 'MS Room 101',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
                'links' => [
                    ['title' => 'SEL Resources', 'url' => 'https://example.com/sel'],
                ]
            ],
            [
                'date' => $wednesdays[2] ?? '2025-08-20',
                'title' => 'HS: Research Skills and Academic Writing',
                'description' => 'Teach students effective research methods and academic writing techniques. Tools and strategies for success.',
                'location' => 'HS Library',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'HS',
            ],
            // Fourth Wednesday
            [
                'date' => $wednesdays[3] ?? '2025-08-27',
                'title' => 'ES: Differentiated Instruction Methods',
                'description' => 'Learn strategies for meeting diverse learning needs in elementary classrooms. Practical approaches and examples.',
                'location' => 'ES Room 15',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
            ],
            [
                'date' => $wednesdays[3] ?? '2025-08-27',
                'title' => 'MS: Project-Based Learning Workshop',
                'description' => 'Explore project-based learning approaches for middle school. Design engaging projects that promote critical thinking.',
                'location' => 'MS Room 210',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
                'links' => [
                    ['title' => 'PBL Templates', 'url' => 'https://example.com/pbl-templates'],
                    ['title' => 'Project Examples', 'url' => 'https://example.com/projects'],
                ]
            ],
            [
                'date' => $wednesdays[3] ?? '2025-08-27',
                'title' => 'HS: Critical Thinking and Analysis',
                'description' => 'Develop students\' analytical skills through structured thinking exercises and discussion techniques.',
                'location' => 'HS Room 301',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'HS',
            ],
            // September - First Wednesday
            [
                'date' => $wednesdays[4] ?? '2025-09-03',
                'title' => 'ES: Classroom Management Techniques',
                'description' => 'Effective strategies for maintaining a positive and productive classroom environment in elementary settings.',
                'location' => 'ES Room 8',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
            ],
            [
                'date' => $wednesdays[4] ?? '2025-09-03',
                'title' => 'MS: Assessment and Feedback Strategies',
                'description' => 'Learn how to create meaningful assessments and provide constructive feedback to middle school students.',
                'location' => 'MS Room 150',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
            ],
            [
                'date' => $wednesdays[4] ?? '2025-09-03',
                'title' => 'HS: Digital Citizenship and Online Safety',
                'description' => 'Important topics on teaching students about responsible digital behavior and online safety practices.',
                'location' => 'HS Computer Lab',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'HS',
                'links' => [
                    ['title' => 'Digital Safety Guide', 'url' => 'https://example.com/digital-safety'],
                ]
            ],
            // October - First Wednesday
            [
                'date' => $wednesdays[8] ?? '2025-10-01',
                'title' => 'ES: Literacy Development Workshop',
                'description' => 'Comprehensive workshop on developing literacy skills across all elementary grade levels.',
                'location' => 'ES Library',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'ES',
            ],
            [
                'date' => $wednesdays[8] ?? '2025-10-01',
                'title' => 'MS: Collaborative Learning Strategies',
                'description' => 'Explore group work and collaborative learning techniques that engage middle school students effectively.',
                'location' => 'MS Room 205',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'MS',
            ],
            [
                'date' => $wednesdays[8] ?? '2025-10-01',
                'title' => 'HS: Career Exploration and Planning',
                'description' => 'Help students explore career options and plan for their future. Resources and guidance for career readiness.',
                'location' => 'HS Career Center',
                'start_time' => '15:00:00',
                'end_time' => '17:00:00',
                'division' => 'HS',
                'links' => [
                    ['title' => 'Career Resources', 'url' => 'https://example.com/careers'],
                    ['title' => 'Internship Opportunities', 'url' => 'https://example.com/internships'],
                ]
            ],
        ];

        foreach ($sessions as $sessionData) {
            $links = $sessionData['links'] ?? [];
            unset($sessionData['links'], $sessionData['division']);

            $session = PLWednesdaySession::create([
                'title' => $sessionData['title'],
                'description' => $sessionData['description'],
                'location' => $sessionData['location'],
                'date' => $sessionData['date'],
                'start_time' => $sessionData['start_time'],
                'end_time' => $sessionData['end_time'],
                'is_active' => true,
            ]);

            // Calculate and save duration
            $session->calculateDuration();
            $session->save();

            // Create links
            foreach ($links as $index => $linkData) {
                PLWednesdayLink::create([
                    'pl_wednesday_session_id' => $session->id,
                    'title' => $linkData['title'],
                    'url' => $linkData['url'],
                    'description' => $linkData['description'] ?? null,
                    'order' => $index,
                ]);
            }
        }

        $this->command->info('Created ' . count($sessions) . ' PL Wednesday sessions with mock data.');
    }

    /**
     * Get all Wednesday dates between start and end date
     */
    private function getWednesdayDates($startDate, $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Find first Wednesday
        while ($current->dayOfWeek !== Carbon::WEDNESDAY && $current->lte($end)) {
            $current->addDay();
        }

        // Collect all Wednesdays
        while ($current->lte($end)) {
            $dates[] = $current->format('Y-m-d');
            $current->addWeek();
        }

        return $dates;
    }
}
