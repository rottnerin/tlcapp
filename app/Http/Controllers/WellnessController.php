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
        
        // Get filter parameters
        $category = $request->get('category');
        $date = $request->get('date');
        $available_only = $request->boolean('available_only');
        
        // Build query
        $sessions = WellnessSession::active()
            ->when($category, function($query) use ($category) {
                return $query->byCategory($category);
            })
            ->when($date, function($query) use ($date) {
                return $query->onDate($date);
            })
            ->when($available_only, function($query) {
                return $query->withCapacity();
            })
            ->with(['userSessions' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('date')
            ->orderBy('start_time')
            ->paginate(12);
        
        // Get available categories for filter
        $categories = WellnessSession::active()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort();
        
        // Get available dates for filter
        $availableDates = WellnessSession::active()
            ->distinct()
            ->pluck('date')
            ->sort();
        
        return view('wellness.index', compact(
            'sessions',
            'categories',
            'availableDates',
            'category',
            'date',
            'available_only'
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
        
        // Check if user is already enrolled
        $existingEnrollment = UserSession::where('user_id', $user->id)
            ->where('wellness_session_id', $session->id)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this session.');
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
                $canWaitlist = $lockedSession->allow_waitlist;
                
                // Determine enrollment status
                $status = 'confirmed';
                if (!$hasCapacity) {
                    if ($canWaitlist) {
                        $status = 'waitlisted';
                    } else {
                        throw new \Exception('This session is full and does not allow waitlist.');
                    }
                }
                
                // Create enrollment record
                $enrollment = UserSession::create([
                    'user_id' => $user->id,
                    'wellness_session_id' => $session->id,
                    'status' => $status,
                    'enrolled_at' => now(),
                ]);
                
                // Update session enrollment count if confirmed
                if ($status === 'confirmed') {
                    $lockedSession->increment('current_enrollment');
                }
                
                return [
                    'status' => $status,
                    'enrollment' => $enrollment,
                    'session' => $lockedSession->fresh()
                ];
            });
            
            $message = $result['status'] === 'confirmed' 
                ? 'Successfully enrolled in the session!' 
                : 'Added to the waitlist for this session.';
            
            return back()->with('success', $message);
            
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
        $enrollment->update(['status' => 'cancelled']);
        
        // If was confirmed, decrease enrollment count and promote from waitlist
        if ($enrollment->status === 'confirmed') {
            $session->decrement('current_enrollment');
            
            // Promote first waitlisted user
            $waitlistedUser = UserSession::where('wellness_session_id', $session->id)
                ->where('status', 'waitlisted')
                ->orderBy('enrolled_at')
                ->first();
            
            if ($waitlistedUser) {
                $waitlistedUser->update(['status' => 'confirmed']);
                $session->increment('current_enrollment');
                
                // TODO: Send notification to promoted user
            }
        }
        
        return back()->with('success', 'Successfully cancelled your enrollment.');
    }
}
