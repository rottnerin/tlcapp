<?php

namespace Tests\Feature;

use App\Models\Division;
use App\Models\PDDay;
use App\Models\PLDaysSetting;
use App\Models\ScheduleItem;
use App\Models\User;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NTSSessionsTest extends TestCase
{
    use RefreshDatabase;

    protected $ntsDivision;

    protected $springPDDay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ntsDivision = Division::create([
            'name' => 'NTS',
            'full_name' => 'Non-Teaching Staff',
            'color_primary' => '#9E9E9E',
            'color_secondary' => '#BDBDBD',
            'is_active' => true,
        ]);

        Division::create([
            'name' => 'ES',
            'full_name' => 'Elementary School',
            'color_primary' => '#4CAF50',
            'color_secondary' => '#81C784',
            'is_active' => true,
        ]);

        PLDaysSetting::initialize();
        PLDaysSetting::first()->update(['is_active' => true]);

        $this->springPDDay = PDDay::create([
            'title' => 'Spring PL Day 2026',
            'description' => 'Spring PD',
            'start_date' => Carbon::parse('2026-03-02'),
            'end_date' => Carbon::parse('2026-03-03'),
            'is_active' => true,
            'season' => 'spring',
            'academic_year' => '2025-2026',
        ]);
    }

    public function test_nts_user_can_access_nts_schedule(): void
    {
        $user = User::factory()->create([
            'division_id' => $this->ntsDivision->id,
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('spring.nts'));

        $response->assertOk();
        $response->assertSee('NTS Sessions');
    }

    public function test_non_nts_user_gets_403_on_nts_routes(): void
    {
        $esDivision = Division::where('name', 'ES')->first();
        $user = User::factory()->create([
            'division_id' => $esDivision->id,
            'is_admin' => false,
        ]);

        $response = $this->actingAs($user)->get(route('spring.nts'));

        $response->assertForbidden();
    }

    public function test_nts_user_can_join_optional_signup(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $user = User::factory()->create([
            'division_id' => $this->ntsDivision->id,
            'is_admin' => false,
        ]);

        $optItem = ScheduleItem::create([
            'title' => 'Optional Sign-up',
            'description' => 'Sign up for optional session',
            'session_type' => 'nts_optional',
            'date' => Carbon::parse('2026-03-02'),
            'start_time' => Carbon::parse('2026-03-02 09:15'),
            'end_time' => Carbon::parse('2026-03-02 10:30'),
            'is_active' => true,
            'p_d_day_id' => $this->springPDDay->id,
        ]);
        $optItem->divisions()->attach($this->ntsDivision->id);

        $response = $this->actingAs($user)->post(route('spring.nts.optional.join', $optItem));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $enrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $optItem->id)
            ->where('status', 'confirmed')
            ->first();

        $this->assertNotNull($enrollment);
    }

    public function test_optional_signup_joins_one_cancels_previous(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $user = User::factory()->create([
            'division_id' => $this->ntsDivision->id,
            'is_admin' => false,
        ]);

        $opt1 = ScheduleItem::create([
            'title' => 'Optional Sign-up',
            'session_type' => 'nts_optional',
            'date' => Carbon::parse('2026-03-02'),
            'start_time' => Carbon::parse('2026-03-02 09:15'),
            'end_time' => Carbon::parse('2026-03-02 10:30'),
            'is_active' => true,
            'p_d_day_id' => $this->springPDDay->id,
        ]);
        $opt1->divisions()->attach($this->ntsDivision->id);

        $opt2 = ScheduleItem::create([
            'title' => 'Optional Sign-up',
            'session_type' => 'nts_optional',
            'date' => Carbon::parse('2026-03-02'),
            'start_time' => Carbon::parse('2026-03-02 11:00'),
            'end_time' => Carbon::parse('2026-03-02 12:15'),
            'is_active' => true,
            'p_d_day_id' => $this->springPDDay->id,
        ]);
        $opt2->divisions()->attach($this->ntsDivision->id);

        $this->actingAs($user)->post(route('spring.nts.optional.join', $opt1));
        $this->actingAs($user)->post(route('spring.nts.optional.join', $opt2));

        $confirmed = UserSession::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('scheduleItem', fn ($q) => $q->where('session_type', 'nts_optional'))
            ->count();

        $this->assertEquals(1, $confirmed);

        $enrollment2 = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $opt2->id)
            ->where('status', 'confirmed')
            ->first();

        $this->assertNotNull($enrollment2);

        $cancelled1 = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $opt1->id)
            ->where('status', 'cancelled')
            ->first();

        $this->assertNotNull($cancelled1);
    }

    public function test_admin_can_unjoin_optional_signup(): void
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);

        $user = User::factory()->create([
            'division_id' => $this->ntsDivision->id,
            'is_admin' => true,
        ]);

        $optItem = ScheduleItem::create([
            'title' => 'Optional Sign-up',
            'session_type' => 'nts_optional',
            'date' => Carbon::parse('2026-03-02'),
            'start_time' => Carbon::parse('2026-03-02 09:15'),
            'end_time' => Carbon::parse('2026-03-02 10:30'),
            'is_active' => true,
            'p_d_day_id' => $this->springPDDay->id,
        ]);
        $optItem->divisions()->attach($this->ntsDivision->id);

        UserSession::create([
            'user_id' => $user->id,
            'schedule_item_id' => $optItem->id,
            'status' => 'confirmed',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($user)->post(route('spring.nts.optional.unjoin', $optItem));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $enrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $optItem->id)
            ->first();

        $this->assertEquals('cancelled', $enrollment->status);
    }
}
