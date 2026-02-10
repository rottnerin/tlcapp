<?php

namespace Tests\Feature;

use App\Models\CCLSession;
use App\Models\CCLSetting;
use App\Models\Division;
use App\Models\PDDay;
use App\Models\ScheduleItem;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * CCL session capacity: multiple users fill a session, next user is rejected.
 * After reviewing results (e.g. if you ran against a real DB), clear enrollments with:
 *   php artisan ccl:clear-test-enrollments
 * (option: --session-title="CCL Capacity Test Session")
 */
class CclCapacityEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** Session title used for capacity test; use with ccl:clear-test-enrollments to clean up. */
    public const TEST_SESSION_TITLE = 'CCL Capacity Test Session';

    protected Division $division;

    protected function setUp(): void
    {
        parent::setUp();

        $this->division = Division::create([
            'name' => 'ES',
            'full_name' => 'Elementary School',
            'color_primary' => '#4CAF50',
            'color_secondary' => '#81C784',
            'is_active' => true,
        ]);

        $settings = CCLSetting::firstOrCreate([], ['is_active' => true]);
        $settings->is_active = true;
        $settings->save();
    }

    public function test_several_users_fill_capacity_then_next_user_is_rejected(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $capacity = 3;
        $pdDay = PDDay::create([
            'title' => 'Test PD Day',
            'description' => 'Test PD Day',
            'start_date' => Carbon::parse('2026-03-02'),
            'end_date' => Carbon::parse('2026-03-03'),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => PDDay::getCurrentAcademicYear(),
        ]);

        $sessionDate = Carbon::parse('2026-03-02');
        $cclSession = CCLSession::create([
            'title' => self::TEST_SESSION_TITLE,
            'description' => 'Session for capacity test',
            'presenter_name' => 'Test Presenter',
            'location' => 'Room 1',
            'date' => $sessionDate,
            'start_time' => '13:30:00',
            'end_time' => '14:30:00',
            'contact_hours' => 1.0,
            'p_d_day_id' => $pdDay->id,
            'division_id' => $this->division->id,
            'is_active' => true,
        ]);
        $cclSession->refresh();

        $startDateTime = $sessionDate->format('Y-m-d') . ' 13:30:00';
        $endDateTime = $sessionDate->format('Y-m-d') . ' 14:30:00';
        $scheduleItem = ScheduleItem::create([
            'title' => $cclSession->title,
            'description' => $cclSession->description,
            'location' => $cclSession->location,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'date' => $sessionDate,
            'presenter_primary' => $cclSession->presenter_name,
            'is_active' => true,
            'session_type' => 'ccl',
            'p_d_day_id' => $pdDay->id,
            'max_participants' => $capacity,
            'current_enrollment' => 0,
        ]);

        // Ensure start_time/date stored exactly as controller queries (SQLite/datetime format)
        DB::table('schedule_items')->where('id', $scheduleItem->id)->update([
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'date' => $sessionDate->format('Y-m-d'),
        ]);

        // So controller's exact where('start_time', $string) finds the row (SQLite/datetime format)
        $cclSession->refresh();
        $lookupStart = $cclSession->date->format('Y-m-d') . ' ' . $cclSession->start_time->format('H:i:s');
        $this->assertNotNull(
            ScheduleItem::where('p_d_day_id', $cclSession->p_d_day_id)
                ->where('date', $cclSession->date->format('Y-m-d'))
                ->where('start_time', $lookupStart)
                ->where('title', $cclSession->title)
                ->where('session_type', 'ccl')
                ->first(),
            'Schedule item must be findable with controller query'
        );

        $users = [];
        for ($i = 1; $i <= $capacity + 1; $i++) {
            $users[] = User::factory()->create([
                'name' => "Capacity Test User {$i}",
                'email' => "capacity-test-user-{$i}@test.aes.ac.in",
                'is_admin' => false,
                'division_id' => $this->division->id,
            ]);
        }

        // First N users join successfully
        for ($i = 0; $i < $capacity; $i++) {
            $response = $this->actingAs($users[$i])->post(
                route('spring.ccl.join', $cclSession)
            );
            $response->assertRedirect();
            $response->assertSessionHas('success');
            $this->assertStringContainsString('joined', strtolower($response->getSession()->get('success')));
        }

        $scheduleItem->refresh();
        $this->assertSame($capacity, (int) $scheduleItem->current_enrollment);

        $confirmedCount = UserSession::where('schedule_item_id', $scheduleItem->id)
            ->where('status', 'confirmed')
            ->count();
        $this->assertSame($capacity, $confirmedCount);

        // Next user tries to join after capacity is filled
        $overCapacityUser = $users[$capacity];
        $response = $this->actingAs($overCapacityUser)->post(
            route('spring.ccl.join', $cclSession)
        );
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('full', strtolower(session('error')));

        $overEnrollment = UserSession::where('user_id', $overCapacityUser->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->where('status', '!=', 'cancelled')
            ->first();
        $this->assertNull($overEnrollment);

        $scheduleItem->refresh();
        $this->assertSame($capacity, (int) $scheduleItem->current_enrollment);
    }
}
