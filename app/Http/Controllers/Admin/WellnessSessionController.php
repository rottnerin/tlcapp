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
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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
        ->orderBy('start_time')
        ->paginate(15);

        // Get filter options
        $categories = WellnessSession::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort();

        $availableDates = WellnessSession::distinct()
            ->pluck('date')
            ->sort();

        // Calculate statistics
        $totalSessions = WellnessSession::count();
        $activeSessions = WellnessSession::where('is_active', true)->count();
        $totalEnrollments = \DB::table('user_sessions')
            ->where('status', 'confirmed')
            ->count();
        
        $avgEnrollmentRate = '0%';
        if ($totalSessions > 0) {
            $totalCapacity = WellnessSession::sum('max_participants');
            $avgEnrollmentRate = $totalCapacity > 0 ? round(($totalEnrollments / $totalCapacity) * 100) . '%' : '0%';
        }

        return view('admin.wellness.index', compact(
            'sessions', 
            'categories', 
            'availableDates',
            'totalSessions',
            'activeSessions', 
            'totalEnrollments'
        ))->with('avgEnrollment', $avgEnrollmentRate);
    }

    /**
     * Show the form for creating a new wellness session
     */
    public function create()
    {
        return view('admin.wellness.create');
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
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_participants' => 'required|integer|min:1|max:200',
            'category' => 'nullable|string|max:100',
            'equipment_needed' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'allow_waitlist' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Combine date and time
        $validated['start_time'] = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $validated['end_time'] = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        $validated['allow_waitlist'] = $request->has('allow_waitlist');
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
        $waitlistedParticipants = $wellness->userSessions->where('status', 'waitlisted');

        return view('admin.wellness.show', compact(
            'wellness', 
            'confirmedParticipants', 
            'waitlistedParticipants'
        ));
    }

    /**
     * Show the form for editing the specified wellness session
     */
    public function edit(WellnessSession $wellness)
    {
        return view('admin.wellness.edit', compact('wellness'));
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
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_participants' => 'required|integer|min:1|max:200',
            'category' => 'nullable|string|max:100',
            'equipment_needed' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'preparation_notes' => 'nullable|string',
            'allow_waitlist' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Combine date and time
        $validated['start_time'] = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $validated['end_time'] = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        $validated['allow_waitlist'] = $request->has('allow_waitlist');
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
        // Check if there are any enrollments
        $enrollmentCount = $wellness->userSessions()->count();
        
        if ($enrollmentCount > 0) {
            return back()->with('error', 
                "Cannot delete session with {$enrollmentCount} enrollments. Please deactivate instead.");
        }

        $wellness->delete();

        return redirect()->route('admin.wellness.index')
            ->with('success', 'Wellness session deleted successfully!');
    }

    /**
     * Toggle session active status
     */
    public function toggleStatus(WellnessSession $wellness)
    {
        $wellness->update(['is_active' => !$wellness->is_active]);
        
        $status = $wellness->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Session {$status} successfully!");
    }
}
