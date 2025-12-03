<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WellnessSession;
use App\Models\UserSession;
use App\Models\Division;
use Carbon\Carbon;

class TestEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test division if it doesn't exist
        $division = Division::firstOrCreate(
            ['name' => 'Test Division'],
            [
                'full_name' => 'Test Division for Development',
                'color_primary' => '#3B82F6',
                'color_secondary' => '#DBEAFE',
                'is_active' => true
            ]
        );

        // Create test users
        $testUsers = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST001'
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST002'
            ],
            [
                'name' => 'Mike Wilson',
                'email' => 'mike.wilson@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST003'
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST004'
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST005'
            ],
            [
                'name' => 'Lisa Anderson',
                'email' => 'lisa.anderson@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST006'
            ],
            [
                'name' => 'Tom Miller',
                'email' => 'tom.miller@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST007'
            ],
            [
                'name' => 'Amy Taylor',
                'email' => 'amy.taylor@test.com',
                'password' => bcrypt('password'),
                'division_id' => $division->id,
                'is_admin' => false,
                'id_card_code' => 'TEST008'
            ]
        ];

        foreach ($testUsers as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Get all active wellness sessions
        $sessions = WellnessSession::where('is_active', true)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->get();

        if ($sessions->count() < 2) {
            $this->command->warn('Need at least 2 active wellness sessions to create test enrollments.');
            return;
        }

        // Get the test users
        $users = User::whereIn('email', array_column($testUsers, 'email'))->get();

        // Create enrollments for the first few sessions
        $sessionsToEnroll = $sessions->take(3); // Take first 3 sessions

        foreach ($sessionsToEnroll as $index => $session) {
            // Enroll 3-5 users in each session
            $usersToEnroll = $users->skip($index * 2)->take(rand(3, 5));
            
            foreach ($usersToEnroll as $user) {
                // Check if user already has any active wellness session enrollment
                $existingActiveEnrollment = UserSession::where('user_id', $user->id)
                    ->where('wellness_session_id', '!=', null)
                    ->where('status', 'confirmed')
                    ->first();

                if (!$existingActiveEnrollment) {
                    UserSession::create([
                        'user_id' => $user->id,
                        'wellness_session_id' => $session->id,
                        'status' => 'confirmed',
                        'enrolled_at' => Carbon::now()->subDays(rand(1, 7)),
                        'notes' => 'Test enrollment for transfer practice'
                    ]);

                    // Update session enrollment count
                    $session->increment('current_enrollment');
                }
            }
        }

        $this->command->info('Test enrollments created successfully!');
        $this->command->info('Created enrollments for ' . $sessionsToEnroll->count() . ' sessions');
        $this->command->info('You can now test the transfer functionality between these sessions.');
    }
}
