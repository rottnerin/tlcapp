<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WellnessSession;
use App\Models\UserSession;
use App\Models\PDDay;
use App\Models\WellnessSetting;
use Carbon\Carbon;

class WellnessController extends Controller
{
    /**
     * Display Fall wellness sessions
     */
    public function fallIndex(Request $request)
    {
        return $this->seasonIndex($request, 'fall');
    }

    /**
     * Display Spring wellness sessions
     */
    public function springIndex(Request $request)
    {
        return $this->seasonIndex($request, 'spring');
    }

    /**
     * Display wellness sessions for a specific season
     */
    protected function seasonIndex(Request $request, string $season)
    {
        WellnessSetting::initialize();
        if (!WellnessSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();
        
        // Get active PD Day for this season
        $activePDDay = PDDay::where('is_active', true)
            ->where('season', $season)
            ->first();
        
        // Fallback to any active PD day if season-specific not found
        if (!$activePDDay) {
            $activePDDay = PDDay::getActive();
        }
        
        return $this->buildWellnessView($request, $user, $activePDDay, $season);
    }

    /**
     * Display all wellness sessions
     */
    public function index(Request $request)
    {
        // Check if Wellness feature is enabled
        WellnessSetting::initialize();
        if (!WellnessSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();
        
        // Get active PD Day
        $activePDDay = PDDay::getActive();
        
        // Check if user has any active wellness enrollment
        $userWellnessEnrollment = UserSession::where('user_id', $user->id)
            ->whereNotNull('wellness_session_id')
            ->where('status', '!=', 'cancelled')
            ->with('wellnessSession')
            ->first();
        
        // Build query for wellness sessions
        $query = WellnessSession::active()
            ->when($activePDDay, function($query) use ($activePDDay) {
                return $query->where('p_d_day_id', $activePDDay->id);
            })
            ->with(['userSessions' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', '!=', 'cancelled');
            }]);
        
        // Apply category filter if provided
        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereJsonContains('category', $category);
        }
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('presenter_name', 'like', "%{$search}%")
                  ->orWhere('co_presenter_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $sessions = $query->orderBy('date')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString(); // Preserve query parameters in pagination
        
        // Get all unique categories for filter dropdown
        $categories = WellnessSession::whereNotNull('category')
            ->get()
            ->pluck('category')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();
        
        $routePrefix = 'wellness';

        return view('wellness.index', compact(
            'user',
            'sessions',
            'userWellnessEnrollment',
            'categories',
            'routePrefix'
        ));
    }

    /**
     * Build the wellness view with common logic
     */
    protected function buildWellnessView(Request $request, $user, $activePDDay, ?string $season = null)
    {
        // Check if user has any active wellness enrollment
        $userWellnessEnrollment = UserSession::where('user_id', $user->id)
            ->whereNotNull('wellness_session_id')
            ->where('status', '!=', 'cancelled')
            ->with('wellnessSession')
            ->first();
        
        // Build query for wellness sessions
        $query = WellnessSession::active()
            ->when($activePDDay, function($query) use ($activePDDay) {
                return $query->where('p_d_day_id', $activePDDay->id);
            })
            ->with(['userSessions' => function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('status', '!=', 'cancelled');
            }]);
        
        // Apply category filter if provided
        if ($request->filled('category')) {
            $category = $request->category;
            $query->whereJsonContains('category', $category);
        }
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('presenter_name', 'like', "%{$search}%")
                  ->orWhere('co_presenter_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $sessions = $query->orderBy('date')
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();
        
        // Get all unique categories for filter dropdown
        $categories = WellnessSession::whereNotNull('category')
            ->get()
            ->pluck('category')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();
        
        $routePrefix = $season ? ($season . '.wellness') : 'wellness';

        return view('wellness.index', compact(
            'user',
            'sessions',
            'userWellnessEnrollment',
            'categories',
            'routePrefix'
        ))->with('season', $season);
    }

    /**
     * Show details of a specific wellness session
     */
    public function show(WellnessSession $session)
    {
        // Check if Wellness feature is enabled
        WellnessSetting::initialize();
        if (!WellnessSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();
        
        // Load relationships
        $session->load([
            'userSessions' => function($query) {
                $query->where('status', 'confirmed')->with('user');
            }
        ]);
        
        // Check if user is enrolled (exclude cancelled so they can re-enroll)
        $userEnrollment = $session->userSessions()
            ->where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        // Get other participants
        $participants = $session->userSessions()
            ->confirmed()
            ->with('user')
            ->get()
            ->pluck('user');
        
        return view('wellness.show', compact(
            'user',
            'session',
            'userEnrollment',
            'participants'
        ));
    }

    /**
     * Enroll user in a wellness session
     */
    public function enroll(Request $request, WellnessSession $session)
    {
        // Check if Wellness feature is enabled
        WellnessSetting::initialize();
        if (!WellnessSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        // Check if user is already enrolled in this specific session
        $existingEnrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this session.');
        }

        // Check if user is already enrolled in any wellness session
        $existingWellnessEnrollment = UserSession::where('user_id', $user->id)
            ->whereNotNull('wellness_session_id')
            ->where('status', '!=', 'cancelled')
            ->with('wellnessSession')
            ->first();

        if ($existingWellnessEnrollment) {
            // Automatically cancel previous wellness enrollment (user is switching sessions)
            $previousSession = $existingWellnessEnrollment->wellnessSession;
            $existingWellnessEnrollment->update(['status' => 'cancelled']);
            if ($previousSession) {
                $previousSession->decrement('current_enrollment');
            }
        }

        // Note: Time conflict check removed since all wellness sessions are at the same time (14:30-15:30).
        // Users can only be in one wellness session at a time; previous enrollment is cancelled above when switching.

        // Use database transaction with locking to prevent race conditions
        try {
            $result = \DB::transaction(function() use ($user, $session) {
                // Lock the session record for update to prevent race conditions
                $lockedSession = WellnessSession::where('id', $session->id)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedSession) {
                    throw new \Exception('Session not found.');
                }

                // Check capacity again after acquiring lock
                $hasCapacity = $lockedSession->current_enrollment < $lockedSession->max_participants;

                // If no capacity, abort
                if (!$hasCapacity) {
                    throw new \Exception('This session is full.');
                }

                // Create enrollment record
                $enrollment = UserSession::create([
                    'user_id' => $user->id,
                    'wellness_session_id' => $session->id,
                    'status' => 'confirmed',
                    'enrolled_at' => now(),
                ]);

                // Update session enrollment count if confirmed
                $lockedSession->increment('current_enrollment');

                return [
                    'enrollment' => $enrollment,
                    'session' => $lockedSession->fresh()
                ];
            });

            return back()->with('success', 'Successfully enrolled in the session!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Unjoin user from a wellness session (admin only)
     */
    public function unjoin(Request $request, WellnessSession $session)
    {
        // Check if Wellness feature is enabled
        WellnessSetting::initialize();
        if (!WellnessSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        // Only allow admins to unjoin
        if (!$user->isAdmin()) {
            return back()->with('error', 'You do not have permission to perform this action.');
        }

        // Find the user's enrollment in this session
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this session.');
        }

        $wasConfirmed = $enrollment->status === 'confirmed';

        // Update enrollment status to cancelled
        $enrollment->update(['status' => 'cancelled']);

        // If was confirmed, decrease enrollment count
        if ($wasConfirmed) {
            $session->decrement('current_enrollment');
        }

        return back()->with('success', 'Successfully left the session.');
    }

}
