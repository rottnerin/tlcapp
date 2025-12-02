<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WellnessSession;
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

        return view('admin.wellness.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new wellness session
     */
    public function create()
    {
        $categories = $this->getWellnessCategories();
        return view('admin.wellness.create', compact('categories'));
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
        return view('admin.wellness.edit', compact('wellness', 'categories'));
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
