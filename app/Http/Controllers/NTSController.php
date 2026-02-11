<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\PDDay;
use App\Models\PLDaysSetting;
use App\Models\ScheduleItem;
use App\Models\UserSession;
use Illuminate\Http\Request;

class NTSController extends Controller
{
    /**
     * Ensure the current user is NTS (Non-Teaching Staff)
     */
    protected function ensureNTS(): void
    {
        $user = auth()->user();
        if (! $user || ! $user->isNTS()) {
            abort(403, 'This page is only available to Non-Teaching Staff.');
        }
    }

    /**
     * Display NTS schedule index
     */
    public function index(Request $request)
    {
        $this->ensureNTS();

        PLDaysSetting::initialize();
        if (! PLDaysSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();
        $activePDDay = PDDay::spring()->active()->first();

        if (! $activePDDay) {
            return redirect()->route('dashboard')
                ->with('error', 'No active Spring PL Day found.');
        }

        $ntsDivision = Division::where('name', 'NTS')->first();
        if (! $ntsDivision) {
            return redirect()->route('dashboard')
                ->with('error', 'NTS division not configured.');
        }

        $optionalSignupItems = ScheduleItem::active()
            ->where('p_d_day_id', $activePDDay->id)
            ->where('session_type', 'nts_optional')
            ->whereHas('divisions', fn ($q) => $q->where('divisions.name', 'NTS'))
            ->with(['divisions', 'links'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $userOptionalEnrollment = UserSession::where('user_id', $user->id)
            ->whereNotNull('schedule_item_id')
            ->whereHas('scheduleItem', fn ($q) => $q->where('session_type', 'nts_optional'))
            ->where('status', '!=', 'cancelled')
            ->with('scheduleItem')
            ->first();

        return view('nts.index', compact(
            'user',
            'activePDDay',
            'optionalSignupItems',
            'userOptionalEnrollment'
        ));
    }

    /**
     * Display a schedule item detail (reuse existing pattern)
     */
    public function show(ScheduleItem $scheduleItem)
    {
        $this->ensureNTS();

        PLDaysSetting::initialize();
        if (! PLDaysSetting::isActive()) {
            abort(404);
        }

        $scheduleItem->load(['divisions', 'links']);

        if (! $scheduleItem->divisions->contains('name', 'NTS')) {
            abort(404, 'Schedule item not found.');
        }

        $user = auth()->user();

        return view('nts.show', compact('scheduleItem', 'user'));
    }

    /**
     * Join Optional Sign-up (one workshop, 5 time slots; user picks one)
     */
    public function joinOptionalSignup(Request $request, ScheduleItem $scheduleItem)
    {
        $this->ensureNTS();

        if ($scheduleItem->session_type !== 'nts_optional' || ! $scheduleItem->is_active) {
            return back()->with('error', 'This session is not available.');
        }

        $user = auth()->user();

        $existingEnrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingEnrollment) {
            return back()->with('info', 'You are already enrolled in this session.');
        }

        try {
            \DB::transaction(function () use ($user, $scheduleItem) {
                $previousNtsOptionalEnrollments = UserSession::where('user_id', $user->id)
                    ->whereNotNull('schedule_item_id')
                    ->whereHas('scheduleItem', fn ($q) => $q->where('session_type', 'nts_optional'))
                    ->where('status', '!=', 'cancelled')
                    ->with('scheduleItem')
                    ->get();

                foreach ($previousNtsOptionalEnrollments as $prev) {
                    $prev->update(['status' => 'cancelled']);
                    if ($prev->scheduleItem && $prev->scheduleItem->max_participants !== null) {
                        $prev->scheduleItem->decrement('current_enrollment');
                    }
                }

                $lockedItem = ScheduleItem::where('id', $scheduleItem->id)
                    ->lockForUpdate()
                    ->first();

                if (! $lockedItem) {
                    throw new \Exception('Schedule item not found.');
                }

                if ($lockedItem->max_participants !== null
                    && $lockedItem->current_enrollment >= $lockedItem->max_participants) {
                    throw new \Exception('This session is full.');
                }

                UserSession::create([
                    'user_id' => $user->id,
                    'schedule_item_id' => $lockedItem->id,
                    'status' => 'confirmed',
                    'enrolled_at' => now(),
                ]);

                if ($lockedItem->max_participants !== null) {
                    $lockedItem->increment('current_enrollment');
                }
            });

            return back()->with('success', 'Successfully enrolled in Optional Sign-up!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Unjoin Optional Sign-up (admin only)
     */
    public function unjoinOptionalSignup(Request $request, ScheduleItem $scheduleItem)
    {
        $this->ensureNTS();

        $user = auth()->user();
        if (! $user->isAdmin()) {
            return back()->with('error', 'You do not have permission to perform this action.');
        }

        if ($scheduleItem->session_type !== 'nts_optional') {
            return back()->with('error', 'Invalid session.');
        }

        $enrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (! $enrollment) {
            return back()->with('error', 'You are not enrolled in this session.');
        }

        $enrollment->update(['status' => 'cancelled']);
        if ($scheduleItem->max_participants !== null) {
            $scheduleItem->decrement('current_enrollment');
        }

        return back()->with('success', 'Successfully left the session.');
    }
}
