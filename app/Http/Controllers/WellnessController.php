<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WellnessSession;
use App\Models\UserSession;
use Carbon\Carbon;

class WellnessController extends Controller
{
    /**
     * Display all wellness sessions
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get all active wellness sessions - no filtering
        $sessions = WellnessSession::active()
            ->with(['userSessions' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(12);
        
        return view('wellness.index', compact(
            'user',
            'sessions'
        ));
    }

    /**
     * Show details of a specific wellness session
     */
    public function show(WellnessSession $session)
    {
        $user = auth()->user();
        
        // Load relationships
        $session->load([
            'userSessions' => function($query) {
                $query->where('status', 'confirmed')->with('user');
            }
        ]);
        
        // Check if user is enrolled
        $userEnrollment = $session->userSessions()
            ->where('user_id', $user->id)
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
            ->first();
        
        if ($existingWellnessEnrollment) {
            return back()->with('error', 'You can only enroll in one wellness session. Please cancel your current enrollment before enrolling in a new session.');
        }
        
        // Check for time conflicts
        $conflictingSessions = UserSession::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('wellnessSession', function($query) use ($session) {
                $query->where('date', $session->date)
                    ->where(function($q) use ($session) {
                        $q->whereBetween('start_time', [$session->start_time, $session->end_time])
                          ->orWhereBetween('end_time', [$session->start_time, $session->end_time])
                          ->orWhere(function($q2) use ($session) {
                              $q2->where('start_time', '<=', $session->start_time)
                                 ->where('end_time', '>=', $session->end_time);
                          });
                    });
            })
            ->exists();
        
        if ($conflictingSessions) {
            return back()->with('error', 'You have a scheduling conflict with another session.');
        }
        
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
     * Cancel user enrollment
     */
    public function cancel(Request $request, WellnessSession $session)
    {
        $user = auth()->user();
        
        $enrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if (!$enrollment) {
            return back()->with('error', 'You are not enrolled in this session.');
        }
        
        // Update enrollment status
        $wasConfirmed = $enrollment->status === 'confirmed';
        $enrollment->update(['status' => 'cancelled']);
        
        // If was confirmed, decrease enrollment count and promote from waitlist
        if ($wasConfirmed) {
            $session->decrement('current_enrollment');
        }
        
        return back()->with('success', 'Successfully cancelled your enrollment.');
    }
}
