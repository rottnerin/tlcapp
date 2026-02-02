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
    public function show(CCLSession $ttt)
    {
        $ttt->load(['division', 'pdDay', 'links']);

        // Find the corresponding ScheduleItem for this CCL session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ttt->p_d_day_id)
            ->where('date', $ttt->date)
            ->where('start_time', $ttt->start_time)
            ->where('title', $ttt->title)
            ->where('session_type', 'ccl')
            ->first();

        $confirmedParticipants = collect();
        if ($scheduleItem) {
            $confirmedParticipants = UserSession::where('schedule_item_id', $scheduleItem->id)
                ->where('status', 'confirmed')
                ->with('user.division')
                ->orderBy('enrolled_at')
                ->get();
        }

        return view('admin.ccl.show', compact('ttt', 'confirmedParticipants', 'scheduleItem'));
    }

    /**
     * Show the form for editing the specified CCL session
     */
    public function edit(CCLSession $ccl)
    {
        $divisions = Division::all();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $ccl->load('links');

        // Find the corresponding ScheduleItem for this CCL session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ccl->p_d_day_id)
            ->where('date', $ccl->date)
            ->where('start_time', $ccl->start_time)
            ->where('title', $ccl->title)
            ->where('session_type', 'ccl')
            ->first();

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
    public function update(Request $request, CCLSession $ttt)
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

        $ttt->update([
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

        // Update links
        $ttt->links()->delete();
        if (!empty($validated['links'])) {
            foreach ($validated['links'] as $index => $linkData) {
                $ttt->links()->create([
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
    public function destroy(CCLSession $ttt)
    {
        $ttt->delete();

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
    public function toggleSessionStatus(CCLSession $ttt)
    {
        $ttt->is_active = !$ttt->is_active;
        $ttt->save();

        return redirect()->back()
            ->with('success', 'Session status updated.');
    }

    /**
     * Remove user enrollment from CCL session
     */
    public function removeEnrollment(Request $request, CCLSession $ttt)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        
        // Find the corresponding ScheduleItem for this CCL session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ttt->p_d_day_id)
            ->where('date', $ttt->date)
            ->where('start_time', $ttt->start_time)
            ->where('title', $ttt->title)
            ->where('session_type', 'ccl')
            ->first();

        if (!$scheduleItem) {
            return back()->with('error', 'Schedule item not found for this session.');
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
