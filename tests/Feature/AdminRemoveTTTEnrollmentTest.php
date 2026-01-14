<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TTTSession;
use App\Models\ScheduleItem;
use App\Models\UserSession;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\TTTSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AdminRemoveTTTEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a division for testing
        $this->division = Division::create([
            'name' => 'ES',
            'full_name' => 'Elementary School',
            'color_primary' => '#4CAF50',
            'color_secondary' => '#81C784',
            'is_active' => true,
        ]);

        // Ensure TTT settings exist and are active
        $settings = TTTSetting::firstOrCreate([], [
            'is_active' => true,
        ]);
        $settings->is_active = true;
        $settings->save();
    }

    public function test_admin_can_remove_user_from_ttt_session(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'division_id' => $this->division->id,
        ]);

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Create a PD Day
        $pdDay = PDDay::create([
            'title' => 'Test PD Day',
            'description' => 'Test Professional Development Day',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(7),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);

        // Create a TTT session
        $sessionDate = Carbon::now()->addDays(7);
        $startTime = Carbon::createFromTime(10, 0, 0);
        
        $tttSession = TTTSession::create([
            'title' => 'Test TTT Session',
            'description' => 'A test TTT session',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => $sessionDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => Carbon::createFromTime(11, 0, 0)->format('H:i:s'),
            'contact_hours' => 1.0,
            'p_d_day_id' => $pdDay->id,
            'division_id' => $this->division->id,
            'is_active' => true,
        ]);

        // Refresh to ensure accessor works correctly
        $tttSession->refresh();
        
        // Create corresponding ScheduleItem (using the accessor like the seeder does)
        $scheduleItem = ScheduleItem::create([
            'title' => $tttSession->title,
            'description' => $tttSession->description,
            'location' => $tttSession->location,
            'start_time' => $tttSession->start_time,
            'end_time' => $tttSession->end_time,
            'date' => $tttSession->date,
            'presenter_primary' => $tttSession->presenter_name,
            'is_active' => true,
            'session_type' => 'ttt',
            'p_d_day_id' => $pdDay->id,
            'max_participants' => 20,
            'current_enrollment' => 0,
        ]);

        // Enroll the user in the session
        $enrollment = UserSession::create([
            'user_id' => $user->id,
            'schedule_item_id' => $scheduleItem->id,
            'status' => 'confirmed',
            'enrolled_at' => now(),
        ]);

        // Update enrollment count
        $scheduleItem->increment('current_enrollment');
        $scheduleItem->refresh();

        // Verify initial state
        $this->assertEquals('confirmed', $enrollment->status);
        $this->assertEquals(1, $scheduleItem->current_enrollment);

        // Act as admin and remove the enrollment
        $response = $this->actingAs($admin)->post(
            route('admin.ttt.remove-enrollment', $tttSession),
            [
                'user_id' => $user->id,
            ]
        );

        // Assert redirect with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify enrollment status is updated
        $enrollment->refresh();
        $this->assertEquals('cancelled', $enrollment->status);

        // Verify enrollment count is decremented
        $scheduleItem->refresh();
        $this->assertEquals(0, $scheduleItem->current_enrollment);
    }

    public function test_admin_cannot_remove_user_not_enrolled_in_session(): void
    {
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'division_id' => $this->division->id,
        ]);

        // Create regular user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Create a PD Day
        $pdDay = PDDay::create([
            'title' => 'Test PD Day',
            'description' => 'Test Professional Development Day',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(7),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);

        // Create a TTT session
        $sessionDate = Carbon::now()->addDays(7);
        $startTime = Carbon::createFromTime(10, 0, 0);
        
        $tttSession = TTTSession::create([
            'title' => 'Test TTT Session',
            'description' => 'A test TTT session',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => $sessionDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => Carbon::createFromTime(11, 0, 0)->format('H:i:s'),
            'contact_hours' => 1.0,
            'p_d_day_id' => $pdDay->id,
            'division_id' => $this->division->id,
            'is_active' => true,
        ]);

        // Refresh to ensure accessor works correctly
        $tttSession->refresh();
        
        // Create corresponding ScheduleItem (using the accessor like the seeder does)
        ScheduleItem::create([
            'title' => $tttSession->title,
            'description' => $tttSession->description,
            'location' => $tttSession->location,
            'start_time' => $tttSession->start_time,
            'end_time' => $tttSession->end_time,
            'date' => $tttSession->date,
            'presenter_primary' => $tttSession->presenter_name,
            'is_active' => true,
            'session_type' => 'ttt',
            'p_d_day_id' => $pdDay->id,
        ]);

        // Attempt to remove user who is not enrolled
        $response = $this->actingAs($admin)->post(
            route('admin.ttt.remove-enrollment', $tttSession),
            [
                'user_id' => $user->id,
            ]
        );

        // Assert redirect with error message (should say schedule item not found or user not enrolled)
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_non_admin_cannot_remove_user_from_ttt_session(): void
    {
        // Create regular user (not admin)
        $regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Create another user to be enrolled
        $enrolledUser = User::factory()->create([
            'name' => 'Enrolled User',
            'email' => 'enrolled@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Create a PD Day
        $pdDay = PDDay::create([
            'title' => 'Test PD Day',
            'description' => 'Test Professional Development Day',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(7),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);

        // Create a TTT session
        $sessionDate = Carbon::now()->addDays(7);
        $startTime = Carbon::createFromTime(10, 0, 0);
        
        $tttSession = TTTSession::create([
            'title' => 'Test TTT Session',
            'description' => 'A test TTT session',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => $sessionDate,
            'start_time' => $startTime->format('H:i:s'),
            'end_time' => Carbon::createFromTime(11, 0, 0)->format('H:i:s'),
            'contact_hours' => 1.0,
            'p_d_day_id' => $pdDay->id,
            'division_id' => $this->division->id,
            'is_active' => true,
        ]);

        // Refresh to ensure accessor works correctly
        $tttSession->refresh();
        
        // Create corresponding ScheduleItem (using the accessor like the seeder does)
        $scheduleItem = ScheduleItem::create([
            'title' => $tttSession->title,
            'description' => $tttSession->description,
            'location' => $tttSession->location,
            'start_time' => $tttSession->start_time,
            'end_time' => $tttSession->end_time,
            'date' => $tttSession->date,
            'presenter_primary' => $tttSession->presenter_name,
            'is_active' => true,
            'session_type' => 'ttt',
            'p_d_day_id' => $pdDay->id,
        ]);

        // Enroll the user
        UserSession::create([
            'user_id' => $enrolledUser->id,
            'schedule_item_id' => $scheduleItem->id,
            'status' => 'confirmed',
            'enrolled_at' => now(),
        ]);

        // Attempt to remove enrollment as non-admin
        $response = $this->actingAs($regularUser)->post(
            route('admin.ttt.remove-enrollment', $tttSession),
            [
                'user_id' => $enrolledUser->id,
            ]
        );

        // Should be forbidden (403) due to admin middleware
        $response->assertStatus(403);
    }
}
