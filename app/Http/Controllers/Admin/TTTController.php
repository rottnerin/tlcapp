<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TTTSession;
use App\Models\TTTSetting;
use App\Models\TTTLink;
use App\Models\PDDay;
use App\Models\Division;
use App\Models\ScheduleItem;
use App\Models\UserSession;
use Illuminate\Http\Request;

class TTTController extends Controller
{
    /**
     * Display a listing of TTT sessions
     */
    public function index()
    {
        $sessions = TTTSession::with(['division', 'pdDay'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time')
            ->paginate(15);

        $settings = TTTSetting::getSettings();

        return view('admin.ttt.index', compact('sessions', 'settings'));
    }

    /**
     * Show the form for creating a new TTT session
     */
    public function create()
    {
        $divisions = Division::all();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();

        return view('admin.ttt.create', compact('divisions', 'pdDays'));
    }

    /**
     * Store a newly created TTT session
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

        $session = TTTSession::create([
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

        return redirect()->route('admin.ttt.index')
            ->with('success', 'TTT Session created successfully.');
    }

    /**
     * Display the specified TTT session
     */
    public function show(TTTSession $ttt)
    {
        $ttt->load(['division', 'pdDay', 'links']);

        // Find the corresponding ScheduleItem for this TTT session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ttt->p_d_day_id)
            ->where('date', $ttt->date)
            ->where('start_time', $ttt->start_time)
            ->where('title', $ttt->title)
            ->where('session_type', 'ttt')
            ->first();

        $confirmedParticipants = collect();
        if ($scheduleItem) {
            $confirmedParticipants = UserSession::where('schedule_item_id', $scheduleItem->id)
                ->where('status', 'confirmed')
                ->with('user.division')
                ->orderBy('enrolled_at')
                ->get();
        }

        return view('admin.ttt.show', compact('ttt', 'confirmedParticipants', 'scheduleItem'));
    }

    /**
     * Show the form for editing the specified TTT session
     */
    public function edit(TTTSession $ttt)
    {
        $divisions = Division::all();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $ttt->load('links');

        // Find the corresponding ScheduleItem for this TTT session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ttt->p_d_day_id)
            ->where('date', $ttt->date)
            ->where('start_time', $ttt->start_time)
            ->where('title', $ttt->title)
            ->where('session_type', 'ttt')
            ->first();

        $confirmedParticipants = collect();
        if ($scheduleItem) {
            $confirmedParticipants = UserSession::where('schedule_item_id', $scheduleItem->id)
                ->where('status', 'confirmed')
                ->with('user.division')
                ->orderBy('enrolled_at')
                ->get();
        }

        return view('admin.ttt.edit', compact('ttt', 'divisions', 'pdDays', 'confirmedParticipants', 'scheduleItem'));
    }

    /**
     * Update the specified TTT session
     */
    public function update(Request $request, TTTSession $ttt)
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

        return redirect()->route('admin.ttt.index')
            ->with('success', 'TTT Session updated successfully.');
    }

    /**
     * Remove the specified TTT session
     */
    public function destroy(TTTSession $ttt)
    {
        $ttt->delete();

        return redirect()->route('admin.ttt.index')
            ->with('success', 'TTT Session deleted successfully.');
    }

    /**
     * Toggle TTT feature active status
     */
    public function toggleActive()
    {
        $isActive = TTTSetting::toggle();

        return response()->json([
            'success' => true,
            'is_active' => $isActive,
            'message' => $isActive ? 'TTT feature enabled' : 'TTT feature disabled',
        ]);
    }

    /**
     * Toggle session status
     */
    public function toggleSessionStatus(TTTSession $ttt)
    {
        $ttt->is_active = !$ttt->is_active;
        $ttt->save();

        return redirect()->back()
            ->with('success', 'Session status updated.');
    }

    /**
     * Remove user enrollment from TTT session
     */
    public function removeEnrollment(Request $request, TTTSession $ttt)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        
        // Find the corresponding ScheduleItem for this TTT session
        $scheduleItem = ScheduleItem::where('p_d_day_id', $ttt->p_d_day_id)
            ->where('date', $ttt->date)
            ->where('start_time', $ttt->start_time)
            ->where('title', $ttt->title)
            ->where('session_type', 'ttt')
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
