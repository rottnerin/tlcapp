<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WellnessSession;
use App\Models\UserSession;
use App\Models\Division;
use Carbon\Carbon;

class ReportsTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating comprehensive test data for reports...');

        // Get divisions
        $es = Division::where('name', 'ES')->first();
        $ms = Division::where('name', 'MS')->first();
        $hs = Division::where('name', 'HS')->first();

        // Create diverse test users across all divisions
        $testUsers = [
            // Elementary School Users
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $es->id,
                'is_admin' => false,
                'id_card_code' => 'ES001',
                'last_login_at' => Carbon::now()->subDays(2)
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $es->id,
                'is_admin' => false,
                'id_card_code' => 'ES002',
                'last_login_at' => Carbon::now()->subDays(5)
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol.davis@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $es->id,
                'is_admin' => false,
                'id_card_code' => 'ES003',
                'last_login_at' => null // Never logged in
            ],

            // Middle School Users
            [
                'name' => 'David Wilson',
                'email' => 'david.wilson@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $ms->id,
                'is_admin' => false,
                'id_card_code' => 'MS001',
                'last_login_at' => Carbon::now()->subHours(6)
            ],
            [
                'name' => 'Emma Brown',
                'email' => 'emma.brown@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $ms->id,
                'is_admin' => false,
                'id_card_code' => 'MS002',
                'last_login_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'Frank Miller',
                'email' => 'frank.miller@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $ms->id,
                'is_admin' => false,
                'id_card_code' => 'MS003',
                'last_login_at' => Carbon::now()->subDays(3)
            ],
            [
                'name' => 'Grace Taylor',
                'email' => 'grace.taylor@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $ms->id,
                'is_admin' => false,
                'id_card_code' => 'MS004',
                'last_login_at' => null // Never logged in
            ],

            // High School Users
            [
                'name' => 'Henry Anderson',
                'email' => 'henry.anderson@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS001',
                'last_login_at' => Carbon::now()->subHours(2)
            ],
            [
                'name' => 'Ivy Thomas',
                'email' => 'ivy.thomas@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS002',
                'last_login_at' => Carbon::now()->subDays(1)
            ],
            [
                'name' => 'Jack Jackson',
                'email' => 'jack.jackson@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS003',
                'last_login_at' => Carbon::now()->subDays(4)
            ],
            [
                'name' => 'Kate White',
                'email' => 'kate.white@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS004',
                'last_login_at' => Carbon::now()->subDays(7)
            ],
            [
                'name' => 'Leo Harris',
                'email' => 'leo.harris@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS005',
                'last_login_at' => null // Never logged in
            ],
            [
                'name' => 'Maya Clark',
                'email' => 'maya.clark@aes.ac.in',
                'password' => bcrypt('password'),
                'division_id' => $hs->id,
                'is_admin' => false,
                'id_card_code' => 'HS006',
                'is_active' => false // Inactive account
            ],
        ];

        $createdUsers = [];
        foreach ($testUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            $createdUsers[] = $user;
        }

        $this->command->info('Created ' . count($createdUsers) . ' test users');

        // Create diverse wellness sessions with different categories and dates
        $wellnessSessions = [
            [
                'title' => 'Morning Yoga Flow',
                'description' => 'Start your day with gentle yoga poses to energize your body and mind.',
                'presenter_name' => 'Sarah Wellness',
                'presenter_bio' => 'Certified yoga instructor with 10+ years experience',
                'presenter_email' => 'sarah.wellness@aes.ac.in',
                'location' => 'Main Gym',
                'date' => Carbon::today()->addDays(7),
                'max_participants' => 20,
                'category' => ['Yoga', 'Mindfulness'],
                'equipment_needed' => 'Yoga mats provided',
                'special_requirements' => 'Comfortable clothing recommended',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Stress Management Workshop',
                'description' => 'Learn practical techniques to manage stress and improve work-life balance.',
                'presenter_name' => 'Dr. Michael Chen',
                'presenter_bio' => 'Licensed psychologist specializing in workplace wellness',
                'presenter_email' => 'michael.chen@aes.ac.in',
                'location' => 'Library Conference Room',
                'date' => Carbon::today()->addDays(10),
                'max_participants' => 15,
                'category' => ['Mental Health', 'Workshop'],
                'equipment_needed' => 'Notebook and pen',
                'special_requirements' => 'None',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'High-Intensity Interval Training',
                'description' => 'Boost your fitness with this energetic HIIT workout session.',
                'presenter_name' => 'Coach James Rodriguez',
                'presenter_bio' => 'Certified personal trainer and former athlete',
                'presenter_email' => 'james.rodriguez@aes.ac.in',
                'location' => 'Fitness Center',
                'date' => Carbon::today()->addDays(14),
                'max_participants' => 25,
                'category' => ['Fitness', 'HIIT'],
                'equipment_needed' => 'Water bottle, towel',
                'special_requirements' => 'Basic fitness level required',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Meditation & Mindfulness',
                'description' => 'Discover the power of meditation for mental clarity and focus.',
                'presenter_name' => 'Zen Master Lisa',
                'presenter_bio' => 'Meditation teacher and mindfulness coach',
                'presenter_email' => 'zen.lisa@aes.ac.in',
                'location' => 'Quiet Garden',
                'date' => Carbon::today()->addDays(21),
                'max_participants' => 30,
                'category' => ['Meditation', 'Mindfulness'],
                'equipment_needed' => 'Cushions provided',
                'special_requirements' => 'Quiet environment needed',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Nutrition & Healthy Cooking',
                'description' => 'Learn to prepare nutritious meals that fuel your body and mind.',
                'presenter_name' => 'Chef Maria Santos',
                'presenter_bio' => 'Registered dietitian and professional chef',
                'presenter_email' => 'maria.santos@aes.ac.in',
                'location' => 'Home Economics Kitchen',
                'date' => Carbon::today()->addDays(28),
                'max_participants' => 12,
                'category' => ['Nutrition', 'Cooking'],
                'equipment_needed' => 'Apron and hair tie',
                'special_requirements' => 'Food allergies must be disclosed',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Dance Fitness Party',
                'description' => 'Have fun while getting fit with this energetic dance workout.',
                'presenter_name' => 'DJ Fitness Mike',
                'presenter_bio' => 'Certified dance fitness instructor and DJ',
                'presenter_email' => 'dj.mike@aes.ac.in',
                'location' => 'Dance Studio',
                'date' => Carbon::today()->addDays(35),
                'max_participants' => 40,
                'category' => ['Dance', 'Fitness'],
                'equipment_needed' => 'Comfortable dance shoes',
                'special_requirements' => 'No dance experience required',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Pilates Core Strength',
                'description' => 'Build core strength and improve posture with Pilates exercises.',
                'presenter_name' => 'Pilates Pro Anna',
                'presenter_bio' => 'Certified Pilates instructor and physiotherapist',
                'presenter_email' => 'anna.pilates@aes.ac.in',
                'location' => 'Yoga Studio',
                'date' => Carbon::today()->addDays(42),
                'max_participants' => 18,
                'category' => ['Pilates', 'Core Training'],
                'equipment_needed' => 'Pilates mats provided',
                'special_requirements' => 'Beginners welcome',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Art Therapy Workshop',
                'description' => 'Express yourself through art and discover its therapeutic benefits.',
                'presenter_name' => 'Art Therapist Sophie',
                'presenter_bio' => 'Licensed art therapist and professional artist',
                'presenter_email' => 'sophie.art@aes.ac.in',
                'location' => 'Art Room',
                'date' => Carbon::today()->addDays(49),
                'max_participants' => 20,
                'category' => ['Art Therapy', 'Creativity'],
                'equipment_needed' => 'All materials provided',
                'special_requirements' => 'No artistic experience needed',
                'is_active' => true,
                'source' => 'manual'
            ],
            [
                'title' => 'Outdoor Adventure Challenge',
                'description' => 'Team building and physical challenge in the great outdoors.',
                'presenter_name' => 'Adventure Guide Tom',
                'presenter_bio' => 'Certified outdoor guide and team building facilitator',
                'presenter_email' => 'tom.adventure@aes.ac.in',
                'location' => 'School Grounds',
                'date' => Carbon::today()->addDays(56),
                'max_participants' => 24,
                'category' => ['Outdoor', 'Team Building'],
                'equipment_needed' => 'Comfortable outdoor clothing',
                'special_requirements' => 'Weather dependent',
                'is_active' => false, // Inactive session
                'source' => 'manual'
            ],
        ];

        $createdSessions = [];
        foreach ($wellnessSessions as $sessionData) {
            $session = WellnessSession::create($sessionData);
            $createdSessions[] = $session;
        }

        $this->command->info('Created ' . count($createdSessions) . ' wellness sessions');

        // Create diverse enrollment scenarios (each user can only enroll in one wellness session due to unique constraint)
        $enrollmentScenarios = [
            // High enrollment session (Yoga) - 7 users
            ['session_index' => 0, 'user_emails' => ['alice.johnson@aes.ac.in', 'david.wilson@aes.ac.in', 'emma.brown@aes.ac.in', 'henry.anderson@aes.ac.in', 'ivy.thomas@aes.ac.in', 'frank.miller@aes.ac.in', 'jack.jackson@aes.ac.in'], 'status' => 'confirmed'],
            
            // Medium enrollment session (Stress Management) - 3 users
            ['session_index' => 1, 'user_emails' => ['bob.smith@aes.ac.in', 'grace.taylor@aes.ac.in', 'kate.white@aes.ac.in'], 'status' => 'confirmed'],
            
            // Low enrollment session (HIIT) - 2 users
            ['session_index' => 2, 'user_emails' => ['leo.harris@aes.ac.in', 'maya.clark@aes.ac.in'], 'status' => 'confirmed'],
            
            // High enrollment session (Meditation) - 5 users
            ['session_index' => 3, 'user_emails' => ['carol.davis@aes.ac.in'], 'status' => 'confirmed'],
            
            // Cancelled enrollments for variety
            ['session_index' => 1, 'user_emails' => ['alice.johnson@aes.ac.in'], 'status' => 'cancelled'],
        ];

        $enrollmentCount = 0;
        $enrolledUsers = []; // Track users who are already enrolled
        
        foreach ($enrollmentScenarios as $scenario) {
            $session = $createdSessions[$scenario['session_index']];
            
            foreach ($scenario['user_emails'] as $email) {
                $user = User::where('email', $email)->first();
                if ($user && !in_array($user->id, $enrolledUsers)) {
                    $enrolledAt = Carbon::now()->subDays(rand(1, 30));
                    
                    try {
                        $userSession = UserSession::create([
                            'user_id' => $user->id,
                            'wellness_session_id' => $session->id,
                            'status' => $scenario['status'],
                            'enrolled_at' => $enrolledAt,
                            'rating' => $scenario['status'] === 'confirmed' ? rand(3, 5) : null,
                            'feedback' => $scenario['status'] === 'confirmed' ? $this->getRandomFeedback() : null,
                            'attended' => $scenario['status'] === 'confirmed' ? rand(0, 1) : null,
                        ]);
                        
                        $enrollmentCount++;
                        if ($scenario['status'] === 'confirmed') {
                            $enrolledUsers[] = $user->id; // Only track confirmed enrollments
                        }
                    } catch (\Exception $e) {
                        // Skip if user already has an active enrollment
                        $this->command->warn("Skipped enrollment for {$email} - already has active enrollment");
                    }
                }
            }
        }

        // Update session enrollment counts
        foreach ($createdSessions as $session) {
            $confirmedCount = $session->userSessions()->where('status', 'confirmed')->count();
            $session->update(['current_enrollment' => $confirmedCount]);
        }

        $this->command->info('Created ' . $enrollmentCount . ' user enrollments');

        // Create some users with no enrollments (for unenrolled users report)
        $unenrolledUsers = [
            ['name' => 'Unenrolled User 1', 'email' => 'unenrolled1@aes.ac.in', 'division_id' => $es->id, 'id_card_code' => 'ES999'],
            ['name' => 'Unenrolled User 2', 'email' => 'unenrolled2@aes.ac.in', 'division_id' => $ms->id, 'id_card_code' => 'MS999'],
            ['name' => 'Unenrolled User 3', 'email' => 'unenrolled3@aes.ac.in', 'division_id' => $hs->id, 'id_card_code' => 'HS999'],
        ];

        foreach ($unenrolledUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => bcrypt('password'),
                    'is_admin' => false,
                    'last_login_at' => null
                ])
            );
        }

        $this->command->info('Created 3 unenrolled users for testing');

        $this->command->info('✅ Test data creation completed!');
        $this->command->info('📊 You now have:');
        $this->command->info('   • ' . count($createdUsers) . ' test users across all divisions');
        $this->command->info('   • ' . count($createdSessions) . ' wellness sessions with various categories');
        $this->command->info('   • ' . $enrollmentCount . ' enrollments with different statuses');
        $this->command->info('   • 3 unenrolled users');
        $this->command->info('   • Users with different activity levels and login patterns');
    }

    private function getRandomFeedback(): string
    {
        $feedbackOptions = [
            'Great session! Very helpful.',
            'Really enjoyed this wellness activity.',
            'Excellent instructor and content.',
            'Would definitely recommend to others.',
            'Very relaxing and informative.',
            'Good workout, felt energized afterwards.',
            'Learned a lot about stress management.',
            'Fun and engaging session.',
            'Perfect for beginners.',
            'Looking forward to more sessions like this.'
        ];

        return $feedbackOptions[array_rand($feedbackOptions)];
    }
}
