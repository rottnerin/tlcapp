<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\WellnessSession;
use App\Models\UserSession;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\WellnessSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

/**
 * Test suite demonstrating the differences between regular users and admin users
 * when joining wellness sessions.
 *
 * Key Differences:
 * 1. Regular users can only join ONE wellness session total
 * 2. Admin users can join MULTIPLE wellness sessions (for testing purposes)
 * 3. Admin users see an "Unjoin" button on joined sessions
 * 4. Admin users can automatically switch between sessions (previous enrollment is cancelled)
 *
 * UI Elements:
 * - Regular User (Not Enrolled): Shows "Join Session" button
 * - Regular User (Enrolled in Session A): Session A shows "✓ JOINED" badge + green highlight
 *                                         Other sessions show "Already Enrolled" (disabled)
 * - Admin User (Enrolled in Session A): Session A shows "✓ JOINED" badge + "Unjoin" button
 *                                       Other sessions show "Join Session" (enabled for testing)
 */
class WellnessUserTypesTest extends TestCase
{
    use RefreshDatabase;

    protected $division;
    protected $pdDay;

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

        // Ensure Wellness settings exist and are active
        WellnessSetting::initialize();
        $wellnessSettings = WellnessSetting::first();
        $wellnessSettings->is_active = true;
        $wellnessSettings->save();

        // Create a PD Day
        $this->pdDay = PDDay::create([
            'title' => 'Spring PD Day 2026',
            'description' => 'Spring Professional Development Day',
            'start_date' => Carbon::now()->addDays(7),
            'end_date' => Carbon::now()->addDays(7),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);
    }

    /**
     * Test: Regular user can join a wellness session
     *
     * Expected UI:
     * - Before joining: "Join Session" button is enabled
     * - After joining: Session card has green highlight, "✓ JOINED" badge, "Enrolled" button (disabled)
     */
    public function test_regular_user_can_join_wellness_session(): void
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        $session = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Yoga / Meditation'],
        ]);

        // User joins the session
        $response = $this->actingAs($user)->post(
            route('wellness.enroll', $session)
        );

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Successfully enrolled in the session!');

        // Verify enrollment was created
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->first();

        $this->assertNotNull($enrollment);
        $this->assertEquals('confirmed', $enrollment->status);

        // Verify session enrollment count increased
        $session->refresh();
        $this->assertEquals(1, $session->current_enrollment);
    }

    /**
     * Test: Regular user cannot join a second wellness session
     *
     * Expected UI:
     * - Session A (enrolled): Shows "✓ JOINED" badge with green highlight
     * - Session B (not enrolled): Shows "Already Enrolled" button (disabled, gray)
     * - User sees error message: "You can only enroll in one wellness session..."
     */
    public function test_regular_user_cannot_join_second_wellness_session(): void
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        // Create two wellness sessions
        $sessionA = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Yoga / Meditation'],
        ]);

        $sessionB = WellnessSession::create([
            'title' => 'Basketball Tournament',
            'description' => 'Friendly basketball games',
            'presenter_name' => 'John Doe',
            'location' => 'Gym B',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Sports and Exercise'],
        ]);

        // User joins Session A
        $this->actingAs($user)->post(route('wellness.enroll', $sessionA));

        // Verify user is enrolled in Session A
        $enrollmentA = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $sessionA->id)
            ->first();
        $this->assertNotNull($enrollmentA);

        // Attempt to join Session B (should fail)
        $response = $this->actingAs($user)->post(
            route('wellness.enroll', $sessionB)
        );

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString(
            'can only enroll in one wellness session',
            session('error')
        );

        // Verify user is NOT enrolled in Session B
        $enrollmentB = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $sessionB->id)
            ->first();
        $this->assertNull($enrollmentB);

        // Verify Session B enrollment count did not increase
        $sessionB->refresh();
        $this->assertEquals(0, $sessionB->current_enrollment);
    }

    /**
     * Test: Admin user can join multiple wellness sessions (for testing)
     *
     * Expected UI:
     * - Admin joins Session A: Shows "✓ JOINED" badge + "Unjoin" button (red)
     * - Admin joins Session B: Session A is auto-cancelled, Session B shows "✓ JOINED" + "Unjoin"
     * - All other sessions still show "Join Session" button (enabled)
     */
    public function test_admin_user_can_join_multiple_wellness_sessions(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'division_id' => $this->division->id,
        ]);

        // Create two wellness sessions
        $sessionA = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Yoga / Meditation'],
        ]);

        $sessionB = WellnessSession::create([
            'title' => 'Basketball Tournament',
            'description' => 'Friendly basketball games',
            'presenter_name' => 'John Doe',
            'location' => 'Gym B',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Sports and Exercise'],
        ]);

        // Admin joins Session A
        $response = $this->actingAs($admin)->post(
            route('wellness.enroll', $sessionA)
        );
        $response->assertSessionHas('success');

        // Verify admin is enrolled in Session A
        $enrollmentA = UserSession::where('user_id', $admin->id)
            ->where('wellness_session_id', $sessionA->id)
            ->where('status', 'confirmed')
            ->first();
        $this->assertNotNull($enrollmentA);
        $sessionA->refresh();
        $this->assertEquals(1, $sessionA->current_enrollment);

        // Admin joins Session B (should auto-cancel Session A enrollment)
        $response = $this->actingAs($admin)->post(
            route('wellness.enroll', $sessionB)
        );
        $response->assertSessionHas('success');

        // Verify Session A enrollment is now cancelled
        $enrollmentA->refresh();
        $this->assertEquals('cancelled', $enrollmentA->status);
        $sessionA->refresh();
        $this->assertEquals(0, $sessionA->current_enrollment);

        // Verify admin is enrolled in Session B
        $enrollmentB = UserSession::where('user_id', $admin->id)
            ->where('wellness_session_id', $sessionB->id)
            ->where('status', 'confirmed')
            ->first();
        $this->assertNotNull($enrollmentB);
        $sessionB->refresh();
        $this->assertEquals(1, $sessionB->current_enrollment);
    }

    /**
     * Test: Admin user can use "Unjoin" button to leave a session
     *
     * Expected UI:
     * - Before unjoin: Session shows "✓ JOINED" badge + "Unjoin" button (red, top-right)
     * - After unjoin: Session returns to normal state with "Join Session" button
     * - Success message: "Successfully left the session."
     */
    public function test_admin_user_can_unjoin_wellness_session(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'division_id' => $this->division->id,
        ]);

        $session = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
        ]);

        // Admin joins the session
        $this->actingAs($admin)->post(route('wellness.enroll', $session));
        $session->refresh();
        $this->assertEquals(1, $session->current_enrollment);

        // Admin unjoins the session
        $response = $this->actingAs($admin)->post(
            route('wellness.unjoin', $session)
        );

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Successfully left the session.');

        // Verify enrollment is cancelled
        $enrollment = UserSession::where('user_id', $admin->id)
            ->where('wellness_session_id', $session->id)
            ->first();
        $this->assertEquals('cancelled', $enrollment->status);

        // Verify enrollment count decreased
        $session->refresh();
        $this->assertEquals(0, $session->current_enrollment);
    }

    /**
     * Test: Regular user cannot use unjoin endpoint (admin only)
     *
     * Expected UI:
     * - Regular users don't see "Unjoin" button at all
     * - If they try to access the endpoint directly, they get a permission error
     */
    public function test_regular_user_cannot_unjoin_wellness_session(): void
    {
        $user = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        $session = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 0,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
        ]);

        // User joins the session
        $this->actingAs($user)->post(route('wellness.enroll', $session));

        // User attempts to unjoin (should fail)
        $response = $this->actingAs($user)->post(
            route('wellness.unjoin', $session)
        );

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You do not have permission to perform this action.');

        // Verify enrollment is still confirmed
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->first();
        $this->assertEquals('confirmed', $enrollment->status);
    }

    /**
     * Test: Enrollment count updates correctly for both user types
     *
     * This test verifies that enrollment counts are properly maintained
     * when both regular users and admin users interact with sessions.
     */
    public function test_enrollment_counts_maintained_for_different_user_types(): void
    {
        $regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'regular@test.com',
            'is_admin' => false,
            'division_id' => $this->division->id,
        ]);

        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'is_admin' => true,
            'division_id' => $this->division->id,
        ]);

        // Create two wellness sessions
        $sessionA = WellnessSession::create([
            'title' => 'Yoga and Mindfulness',
            'description' => 'A relaxing yoga session',
            'presenter_name' => 'Jane Smith',
            'location' => 'Gym A',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 5,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Yoga / Meditation'],
        ]);

        $sessionB = WellnessSession::create([
            'title' => 'Basketball Tournament',
            'description' => 'Friendly basketball games',
            'presenter_name' => 'John Doe',
            'location' => 'Gym B',
            'date' => Carbon::now()->addDays(7),
            'max_participants' => 20,
            'current_enrollment' => 12,
            'is_active' => true,
            'p_d_day_id' => $this->pdDay->id,
            'category' => ['Sports and Exercise'],
        ]);

        // Regular user joins Session A
        $this->actingAs($regularUser)->post(route('wellness.enroll', $sessionA));
        $sessionA->refresh();
        $this->assertEquals(6, $sessionA->current_enrollment);

        // Admin user joins Session B
        $this->actingAs($adminUser)->post(route('wellness.enroll', $sessionB));
        $sessionB->refresh();
        $this->assertEquals(13, $sessionB->current_enrollment);

        // Admin switches to Session A (auto-cancels Session B)
        $this->actingAs($adminUser)->post(route('wellness.enroll', $sessionA));
        $sessionA->refresh();
        $sessionB->refresh();
        $this->assertEquals(7, $sessionA->current_enrollment);
        $this->assertEquals(12, $sessionB->current_enrollment); // Back to original

        // Verify both users are now in Session A
        $enrollments = UserSession::where('wellness_session_id', $sessionA->id)
            ->where('status', 'confirmed')
            ->get();
        $this->assertCount(2, $enrollments);
    }
}
