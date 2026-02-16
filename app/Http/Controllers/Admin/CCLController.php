<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CCLSession;
use App\Models\CCLSetting;
use App\Models\CCLLink;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\ScheduleItem;
use App\Models\UserSession;
use Illuminate\Http\Request;

class CCLController extends Controller
{
    /**
     * Display a listing of CCL sessions
     */
    public function index()
    {
        $sessions = CCLSession::with(['division', 'pdDay'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->paginate(15);

        $settings = CCLSetting::getSettings();

        // Build enrollment data for each session (enrollments are tracked via ScheduleItem)
        $enrollmentData = [];
        $pdDayIds = $sessions->pluck('p_d_day_id')->filter()->unique()->values()->all();
        $cclScheduleItems = collect();

        if (!empty($pdDayIds)) {
            $cclScheduleItems = ScheduleItem::where('session_type', 'ccl')
                ->whereIn('p_d_day_id', $pdDayIds)
                ->get();
        }

        $scheduleItemBySession = [];
        foreach ($sessions as $session) {
            $match = $cclScheduleItems->first(function ($si) use ($session) {
                return $si->p_d_day_id == $session->p_d_day_id
                    && $si->date && $session->date && $si->date->format('Y-m-d') === $session->date->format('Y-m-d')
                    && $si->start_time && $session->start_time
                    && $si->start_time->format('H:i') === $session->start_time->format('H:i')
                    && $si->title === $session->title;
            });
            if ($match) {
                $scheduleItemBySession[$session->id] = $match;
            } else {
                $enrollmentData[$session->id] = ['count' => 0, 'max' => null];
            }
        }

        if (!empty($scheduleItemBySession)) {
            $ids = array_values(array_map(fn ($si) => $si->id, $scheduleItemBySession));
            $counts = UserSession::whereIn('schedule_item_id', $ids)
                ->where('status', 'confirmed')
                ->selectRaw('schedule_item_id, count(*) as cnt')
                ->groupBy('schedule_item_id')
                ->pluck('cnt', 'schedule_item_id');

            foreach ($scheduleItemBySession as $sessionId => $scheduleItem) {
                $enrollmentData[$sessionId] = [
                    'count' => (int) ($counts[$scheduleItem->id] ?? 0),
                    'max' => $scheduleItem->max_participants,
                ];
            }
        }

        return view('admin.ccl.index', compact('sessions', 'settings', 'enrollmentData'));
    }

    /**
     * Show the form for creating a new CCL session
     */
    public function create()
    {
        $divisions = Division::all();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();

        return view('admin.ccl.create', compact('divisions', 'pdDays'));
    }

    /**
     * Store a newly created CCL session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presenter_name' => 'required|string|max:255',
            'presenter_email' => 'nullable|email|max:255',
            'presenter_bio' => 'nullable|string',
            'co_presenter_name' => 'nullable|string|max:255',
            'co_presenter_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'contact_hours' => 'nullable|numeric|min:0|max:24',
            'p_d_day_id' => 'nullable|exists:p_d_days,id',
            'division_id' => 'nullable|exists:divisions,id',
            'category' => 'nullable|array',
            'is_active' => 'boolean',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links|string|max:255',
            'links.*.url' => 'required_with:links|url|max:500',
            'links.*.type' => 'nullable|string|max:50',
        ]);

        $session = CCLSession::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'presenter_name' => $validated['presenter_name'],
            'presenter_email' => $validated['presenter_email'] ?? null,
            'presenter_bio' => $validated['presenter_bio'] ?? null,
            'co_presenter_name' => $validated['co_presenter_name'] ?? null,
            'co_presenter_email' => $validated['co_presenter_email'] ?? null,
            'location' => $validated['location'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'contact_hours' => $validated['contact_hours'] ?? null,
            'p_d_day_id' => $validated['p_d_day_id'] ?? null,
            'division_id' => $validated['division_id'] ?? null,
            'category' => $validated['category'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Create corresponding ScheduleItem for enrollment tracking
        $this->syncScheduleItem($session);

        // Create links if provided
        if (!empty($validated['links'])) {
            foreach ($validated['links'] as $index => $linkData) {
                $session->links()->create([
                    'title' => $linkData['title'],
                    'url' => $linkData['url'],
                    'type' => $linkData['type'] ?? 'resource',
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.ccl.index')
            ->with('success', 'CCL Session created successfully.');
    }

    /**
     * Display the specified CCL session
     */
    public function show(CCLSession $ccl)
    {
        $ccl->load(['division', 'pdDay', 'links']);

        // Find the corresponding ScheduleItem for this CCL session (use combined datetime like user CCLController)
        $scheduleItemStartTime = $ccl->date && $ccl->start_time
            ? $ccl->date->format('Y-m-d') . ' ' . $ccl->start_time->format('H:i:s')
            : null;
        $scheduleItem = $scheduleItemStartTime
            ? ScheduleItem::where('p_d_day_id', $ccl->p_d_day_id)
                ->where('date', $ccl->date->format('Y-m-d'))
                ->where('start_time', $scheduleItemStartTime)
                ->where('title', $ccl->title)
                ->where('session_type', 'ccl')
                ->first()
            : null;

        $confirmedParticipants = collect();
        if ($scheduleItem) {
            $confirmedParticipants = UserSession::where('schedule_item_id', $scheduleItem->id)
                ->where('status', 'confirmed')
                ->with('user.division')
                ->orderBy('enrolled_at')
                ->get();
        }

        return view('admin.ccl.show', compact('ccl', 'confirmedParticipants', 'scheduleItem'));
    }

    /**
     * Show the form for editing the specified CCL session
     */
    public function edit(CCLSession $ccl)
    {
        $divisions = Division::all();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $ccl->load('links');

        // Find the corresponding ScheduleItem for this CCL session (use combined datetime like user CCLController)
        $scheduleItemStartTime = $ccl->date && $ccl->start_time
            ? $ccl->date->format('Y-m-d') . ' ' . $ccl->start_time->format('H:i:s')
            : null;
        $scheduleItem = $scheduleItemStartTime
            ? ScheduleItem::where('p_d_day_id', $ccl->p_d_day_id)
                ->where('date', $ccl->date->format('Y-m-d'))
                ->where('start_time', $scheduleItemStartTime)
                ->where('title', $ccl->title)
                ->where('session_type', 'ccl')
                ->first()
            : null;

        $confirmedParticipants = collect();
        if ($scheduleItem) {
            $confirmedParticipants = UserSession::where('schedule_item_id', $scheduleItem->id)
                ->where('status', 'confirmed')
                ->with('user.division')
                ->orderBy('enrolled_at')
                ->get();
        }

        $categories = $this->getCCLCategories();

        return view('admin.ccl.edit', compact('ccl', 'divisions', 'pdDays', 'categories', 'confirmedParticipants', 'scheduleItem'));
    }

    /**
     * Get available CCL categories for the edit form
     */
    private function getCCLCategories(): array
    {
        return [
            'SEL',
            'Wellness',
            'Collaborative Learning',
            'Teaching Strategies',
            'Technology',
            'Assessment',
            'Differentiation',
            'Project-Based Learning',
            'Other',
        ];
    }

    /**
     * Update the specified CCL session
     */
    public function update(Request $request, CCLSession $ccl)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presenter_name' => 'required|string|max:255',
            'presenter_email' => 'nullable|email|max:255',
            'presenter_bio' => 'nullable|string',
            'co_presenter_name' => 'nullable|string|max:255',
            'co_presenter_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'contact_hours' => 'nullable|numeric|min:0|max:24',
            'p_d_day_id' => 'nullable|exists:p_d_days,id',
            'division_id' => 'nullable|exists:divisions,id',
            'category' => 'nullable|array',
            'is_active' => 'boolean',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links|string|max:255',
            'links.*.url' => 'required_with:links|url|max:500',
            'links.*.type' => 'nullable|string|max:50',
        ]);

        // Capture old values for ScheduleItem lookup before the update
        $oldTitle = $ccl->title;
        $oldDate = $ccl->date;
        $oldStartTime = $ccl->start_time;

        $ccl->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'presenter_name' => $validated['presenter_name'],
            'presenter_email' => $validated['presenter_email'] ?? null,
            'presenter_bio' => $validated['presenter_bio'] ?? null,
            'co_presenter_name' => $validated['co_presenter_name'] ?? null,
            'co_presenter_email' => $validated['co_presenter_email'] ?? null,
            'location' => $validated['location'] ?? null,
            'date' => $validated['date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'contact_hours' => $validated['contact_hours'] ?? null,
            'p_d_day_id' => $validated['p_d_day_id'] ?? null,
            'division_id' => $validated['division_id'] ?? null,
            'category' => $validated['category'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Sync the corresponding ScheduleItem (uses old values to find existing, then updates)
        $this->syncScheduleItem($ccl, $oldTitle, $oldDate, $oldStartTime);

        // Update links
        $ccl->links()->delete();
        if (!empty($validated['links'])) {
            foreach ($validated['links'] as $index => $linkData) {
                $ccl->links()->create([
                    'title' => $linkData['title'],
                    'url' => $linkData['url'],
                    'type' => $linkData['type'] ?? 'resource',
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.ccl.index')
            ->with('success', 'CCL Session updated successfully.');
    }

    /**
     * Remove the specified CCL session
     */
    public function destroy(CCLSession $ccl)
    {
        $ccl->delete();

        return redirect()->route('admin.ccl.index')
            ->with('success', 'CCL Session deleted successfully.');
    }

    /**
     * Toggle CCL feature active status
     */
    public function toggleActive()
    {
        $isActive = CCLSetting::toggle();

        return response()->json([
            'success' => true,
            'is_active' => $isActive,
            'message' => $isActive ? 'CCL feature enabled' : 'CCL feature disabled',
        ]);
    }

    /**
     * Toggle session status
     */
    public function toggleSessionStatus(CCLSession $ccl)
    {
        $ccl->is_active = !$ccl->is_active;
        $ccl->save();

        return redirect()->back()
            ->with('success', 'Session status updated.');
    }

    /**
     * Create or update the corresponding ScheduleItem for a CCL session.
     *
     * The enrollment system uses ScheduleItem records (session_type=ccl) to track
     * user enrollments. This method ensures one exists for every CCL session.
     *
     * @param CCLSession $session       The CCL session to sync
     * @param string|null $oldTitle     Previous title (for finding existing item after edits)
     * @param mixed       $oldDate      Previous date
     * @param mixed       $oldStartTime Previous start_time
     */
    private function syncScheduleItem(CCLSession $session, ?string $oldTitle = null, $oldDate = null, $oldStartTime = null): void
    {
        $date = $session->date ? $session->date->format('Y-m-d') : null;
        if (!$date) {
            return;
        }

        // Build the datetime values that ScheduleItem expects
        $startDateTime = $date . ' ' . ($session->getRawOriginal('start_time') ?? $session->start_time->format('H:i:s'));
        $endDateTime = $date . ' ' . ($session->getRawOriginal('end_time') ?? $session->end_time->format('H:i:s'));

        // Try to find existing ScheduleItem: first by old values (for updates), then by current values
        $scheduleItem = null;

        if ($oldTitle !== null && $oldDate !== null && $oldStartTime !== null) {
            $oldDateStr = $oldDate instanceof \Carbon\Carbon ? $oldDate->format('Y-m-d') : $oldDate;
            $oldStartStr = $oldStartTime instanceof \Carbon\Carbon
                ? $oldStartTime->format('Y-m-d H:i:s')
                : $oldDateStr . ' ' . $oldStartTime;

            $scheduleItem = ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                ->where('title', $oldTitle)
                ->where('session_type', 'ccl')
                ->where('date', $oldDateStr)
                ->where('start_time', $oldStartStr)
                ->first();
        }

        if (!$scheduleItem) {
            $scheduleItem = ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                ->where('title', $session->title)
                ->where('session_type', 'ccl')
                ->where('date', $date)
                ->where('start_time', $startDateTime)
                ->first();
        }

        $attributes = [
            'title' => $session->title,
            'description' => $session->description,
            'location' => $session->location,
            'presenter_primary' => $session->presenter_name,
            'date' => $date,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'p_d_day_id' => $session->p_d_day_id,
            'session_type' => 'ccl',
            'is_active' => $session->is_active,
        ];

        if ($scheduleItem) {
            $scheduleItem->update($attributes);
        } else {
            ScheduleItem::create($attributes);
        }
    }

    /**
     * Remove user enrollment from CCL session
     */
    public function removeEnrollment(Request $request, CCLSession $ccl)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_item_id' => 'nullable|exists:schedule_items,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        // Use schedule_item_id from form when available (avoids match failure after CCL edits)
        if ($request->filled('schedule_item_id')) {
            $scheduleItem = ScheduleItem::where('id', $request->schedule_item_id)
                ->where('session_type', 'ccl')
                ->first();
            if (!$scheduleItem) {
                return back()->with('error', 'Schedule item not found for this session.');
            }
        } else {
            // Fallback: use CCL session's matching ScheduleItem (handles null date/start_time)
            $scheduleItem = $ccl->matchingScheduleItem();

            if (!$scheduleItem) {
                return back()->with('error', 'Schedule item not found for this session.');
            }
        }

        // Find the user's enrollment in this session
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'User is not enrolled in this session.');
        }

        $wasConfirmed = $enrollment->status === 'confirmed';
        
        // Update enrollment status to cancelled
        $enrollment->update(['status' => 'cancelled']);

        // If was confirmed, decrease enrollment count
        if ($wasConfirmed && $scheduleItem->max_participants !== null) {
            $scheduleItem->decrement('current_enrollment');
        }

        return back()->with('success', "Successfully removed {$user->name} from the session.");
    }
}
