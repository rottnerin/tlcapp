<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WellnessSession;
use App\Models\PDDay;
use Illuminate\Http\Request;
use Carbon\Carbon;

class WellnessSessionController extends Controller
{
    /**
     * Display a listing of wellness sessions
     */
    public function index(Request $request)
    {
        $query = WellnessSession::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('presenter_name', 'like', "%{$search}%")
                  ->orWhere('co_presenter_name', 'like', "%{$search}%")
                  ->orWhereJsonContains('category', $search);
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereJsonContains('category', $request->category);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $sessions = $query->withCount(['userSessions' => function($q) {
            $q->where('status', 'confirmed');
        }])
        ->orderBy('date')
        ->orderBy('title')
        ->paginate(20);

        $categories = $this->getWellnessCategories();
        $availableDates = WellnessSession::select('date')->distinct()->orderBy('date')->pluck('date');

        return view('admin.wellness.index', compact('sessions', 'categories', 'availableDates'));
    }

    /**
     * Show the form for creating a new wellness session
     */
    public function create()
    {
        $categories = $this->getWellnessCategories();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        return view('admin.wellness.create', compact('categories', 'pdDays'));
    }

    /**
     * Store a newly created wellness session
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presenter_name' => 'nullable|string|max:255',
            'presenter_bio' => 'nullable|string',
            'presenter_email' => 'nullable|email|max:255',
            'co_presenter_name' => 'nullable|string|max:255',
            'co_presenter_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'max_participants' => 'required|integer|min:1|max:200',
            'category' => 'nullable|array',
            'category.*' => 'string|max:100',
            'equipment_needed' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        WellnessSession::create($validated);

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Wellness session created successfully!');
    }

    /**
     * Display the specified wellness session
     */
    public function show(WellnessSession $wellness)
    {
        $wellness->load(['userSessions' => function($query) {
            $query->with('user')->orderBy('enrolled_at');
        }]);

        $confirmedParticipants = $wellness->userSessions->where('status', 'confirmed');

        return view('admin.wellness.show', compact(
            'wellness', 
            'confirmedParticipants'
        ));
    }

    /**
     * Show the form for editing the specified wellness session
     */
    public function edit(WellnessSession $wellness)
    {
        $categories = $this->getWellnessCategories();
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        return view('admin.wellness.edit', compact('wellness', 'categories', 'pdDays'));
    }

    /**
     * Update the specified wellness session
     */
    public function update(Request $request, WellnessSession $wellness)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'presenter_name' => 'nullable|string|max:255',
            'presenter_bio' => 'nullable|string',
            'presenter_email' => 'nullable|email|max:255',
            'co_presenter_name' => 'nullable|string|max:255',
            'co_presenter_email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'max_participants' => 'required|integer|min:1|max:200',
            'category' => 'nullable|array',
            'category.*' => 'string|max:100',
            'equipment_needed' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $wellness->update($validated);

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Wellness session updated successfully!');
    }

    /**
     * Remove the specified wellness session
     */
    public function destroy(WellnessSession $wellness)
    {
        $wellness->delete();

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Wellness session deleted successfully!');
    }

    /**
     * Toggle wellness session active status
     */
    public function toggleStatus(WellnessSession $wellness)
    {
        $wellness->update(['is_active' => !$wellness->is_active]);
        
        $status = $wellness->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Wellness session {$status} successfully!");
    }

    /**
     * Remove user enrollment from wellness session
     */
    public function removeEnrollment(Request $request, WellnessSession $wellness)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        
        // Find the user's enrollment in this session
        $enrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $wellness->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'User is not enrolled in this session.');
        }

        $wasConfirmed = $enrollment->status === 'confirmed';
        
        // Update enrollment status to cancelled
        $enrollment->update(['status' => 'cancelled']);

        // If was confirmed, decrease enrollment count
        if ($wasConfirmed) {
            $wellness->decrement('current_enrollment');
        }

        return back()->with('success', "Successfully removed {$user->name} from the session.");
    }

    /**
     * Show transfer form for moving users between sessions
     */
    public function showTransfer(WellnessSession $wellness)
    {
        $wellness->load(['userSessions' => function($query) {
            $query->with('user')->where('status', 'confirmed')->orderBy('enrolled_at');
        }]);

        // Get other active sessions on the same date for transfer options
        $otherSessions = WellnessSession::where('id', '!=', $wellness->id)
            ->where('date', $wellness->date)
            ->where('is_active', true)
            ->withCount(['userSessions' => function($q) {
                $q->where('status', 'confirmed');
            }])
            ->orderBy('title')
            ->get();

        return view('admin.wellness.transfer', compact('wellness', 'otherSessions'));
    }

    /**
     * Transfer user from one session to another
     */
    public function transferUser(Request $request, WellnessSession $fromSession)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'to_session_id' => 'required|exists:wellness_sessions,id',
            'reason' => 'nullable|string|max:500'
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);
        $toSession = WellnessSession::findOrFail($request->to_session_id);

        // Validate that sessions are on the same date
        if ($fromSession->date != $toSession->date) {
            return back()->with('error', 'Cannot transfer users between sessions on different dates.');
        }

        // Check if target session has capacity
        if (!$toSession->hasAvailableCapacity()) {
            return back()->with('error', 'Target session is full. Cannot transfer user.');
        }

        // Find the user's enrollment in the source session
        $enrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $fromSession->id)
            ->where('status', 'confirmed')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'User is not enrolled in the source session.');
        }

        // Check if user is already enrolled in target session
        $existingEnrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $toSession->id)
            ->where('status', 'confirmed')
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'User is already enrolled in the target session.');
        }

        // Start database transaction
        \DB::beginTransaction();

        try {
            // Remove from source session
            $enrollment->update(['status' => 'cancelled']);
            $fromSession->decrement('current_enrollment');

            // Add to target session
            \App\Models\UserSession::create([
                'user_id' => $user->id,
                'wellness_session_id' => $toSession->id,
                'status' => 'confirmed',
                'enrolled_at' => now(),
                'notes' => $request->reason ? "Transferred from '{$fromSession->title}': {$request->reason}" : "Transferred from '{$fromSession->title}'"
            ]);

            $toSession->increment('current_enrollment');

            \DB::commit();

            return back()->with('success', "Successfully transferred {$user->name} from '{$fromSession->title}' to '{$toSession->title}'.");

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->with('error', 'Transfer failed. Please try again.');
        }
    }

    /**
     * Get available wellness categories
     */
    private function getWellnessCategories(): array
    {
        return [
            'The Arts (Visual or Performing)',
            'Sports and Exercise',
            'Dance and Movement',
            'Language and Culture',
            'Crafts',
            'Yoga / Meditation',
            'A general opportunity for joy and connection',
            'Health and Well-being',
            'Other'
        ];
    }
}
