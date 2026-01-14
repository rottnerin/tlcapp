<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\TTTSession;
use App\Models\WellnessSession;
use App\Models\ScheduleItem;
use App\Models\UserSession;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\TTTSetting;
use App\Models\WellnessSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class FullSessionEnrollmentTest extends TestCase
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
        $tttSettings = TTTSetting::firstOrCreate([], [
            'is_active' => true,
        ]);
        $tttSettings->is_active = true;
        $tttSettings->save();

        // Ensure Wellness settings exist and are active
        WellnessSetting::initialize();
        $wellnessSettings = WellnessSetting::getActive();
        $wellnessSettings->is_active = true;
        $wellnessSettings->save();
    }

    public function test_user_cannot_enroll_in_full_wellness_session(): void
    {
        // Create a user
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

        // Create a wellness session with max 20 participants
        $wellnessSession = WellnessSession::create([
            'title' => 'Full Wellness Session',
            'description' => 'A wellness session that is full',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 20, // Session is full
            'is_active' => true,
            'p_d_day_id' => $pdDay->id,
        ]);

        // Verify session is full
        $this->assertTrue($wellnessSession->isFull());
        $this->assertFalse($wellnessSession->hasAvailableCapacity());
        $this->assertEquals(0, $wellnessSession->available_spots);
        $this->assertEquals('full', $wellnessSession->status);

        // Attempt to enroll
        $response = $this->actingAs($user)->post(
            route('wellness.enroll', $wellnessSession)
        );

        // Assert redirect with error message
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('full', strtolower(session('error')));

        // Verify user was not enrolled
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $wellnessSession->id)
            ->first();
        
        $this->assertNull($enrollment);
        $wellnessSession->refresh();
        $this->assertEquals(20, $wellnessSession->current_enrollment);
    }

    public function test_user_cannot_join_full_ttt_session(): void
    {
        // Create a user
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
            'title' => 'Full TTT Session',
            'description' => 'A TTT session that is full',
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
        
        // Create corresponding ScheduleItem with max 20 participants (full)
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
            'current_enrollment' => 20, // Session is full
        ]);

        // Verify schedule item is full
        $scheduleItem->refresh();
        $this->assertEquals(20, $scheduleItem->current_enrollment);
        $this->assertEquals(20, $scheduleItem->max_participants);
        $this->assertTrue($scheduleItem->current_enrollment >= $scheduleItem->max_participants);

        // Attempt to join
        $response = $this->actingAs($user)->post(
            route('spring.ttt.join', $tttSession)
        );

        // Assert redirect with error message
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('full', strtolower(session('error')));

        // Verify user was not enrolled
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->first();
        
        $this->assertNull($enrollment);
        $scheduleItem->refresh();
        $this->assertEquals(20, $scheduleItem->current_enrollment);
    }

    public function test_wellness_session_shows_full_status_when_capacity_reached(): void
    {
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

        // Create a wellness session at capacity
        $wellnessSession = WellnessSession::create([
            'title' => 'Full Wellness Session',
            'description' => 'A wellness session at capacity',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 20,
            'is_active' => true,
            'p_d_day_id' => $pdDay->id,
        ]);

        // Test model methods
        $this->assertTrue($wellnessSession->isFull());
        $this->assertFalse($wellnessSession->hasAvailableCapacity());
        $this->assertFalse($wellnessSession->isAvailableForEnrollment());
        $this->assertEquals(0, $wellnessSession->available_spots);
        $this->assertEquals('full', $wellnessSession->status);
    }

    public function test_ttt_session_schedule_item_shows_full_when_capacity_reached(): void
    {
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
            'title' => 'Full TTT Session',
            'description' => 'A TTT session at capacity',
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

        $tttSession->refresh();
        
        // Create schedule item at capacity
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
            'current_enrollment' => 20,
        ]);

        // Test that schedule item is at capacity
        $scheduleItem->refresh();
        $this->assertEquals(20, $scheduleItem->max_participants);
        $this->assertEquals(20, $scheduleItem->current_enrollment);
        $this->assertTrue($scheduleItem->current_enrollment >= $scheduleItem->max_participants);
    }

    public function test_enrollment_blocked_when_wellness_session_reaches_exact_capacity(): void
    {
        // Create a user
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

        // Create a wellness session at 19/20 (one spot left)
        $wellnessSession = WellnessSession::create([
            'title' => 'Almost Full Wellness Session',
            'description' => 'A wellness session with one spot',
            'presenter_name' => 'Test Presenter',
            'location' => 'Test Location',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 19,
            'is_active' => true,
            'p_d_day_id' => $pdDay->id,
        ]);

        // Create another user to fill the last spot
        $otherUser = User::factory()->create([
            'name' => 'Other User',
            'email' => 'other@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Fill the last spot
        UserSession::create([
            'user_id' => $otherUser->id,
            'wellness_session_id' => $wellnessSession->id,
            'status' => 'confirmed',
            'enrolled_at' => now(),
        ]);
        $wellnessSession->increment('current_enrollment');
        $wellnessSession->refresh();

        // Now session should be full
        $this->assertEquals(20, $wellnessSession->current_enrollment);
        $this->assertTrue($wellnessSession->isFull());

        // Attempt to enroll as the first user
        $response = $this->actingAs($user)->post(
            route('wellness.enroll', $wellnessSession)
        );

        // Should be blocked
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('full', strtolower(session('error')));
    }
}
