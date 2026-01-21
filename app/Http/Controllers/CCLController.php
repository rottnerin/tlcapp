<?php

namespace App\Http\Controllers;

use App\Models\CCLSession;
use App\Models\CCLSetting;
use App\Models\PDDay;
use Illuminate\Http\Request;

class CCLController extends Controller
{
    /**
     * Display CCL sessions for users
     */
    public function index(Request $request)
    {
        // Check if CCL feature is active
        if (!CCLSetting::isActive()) {
            return redirect()->route('dashboard')
                ->with('error', 'Collaborative Community Learning Sessions is not currently available.');
        }

        // Get active spring PD day
        $pdDay = PDDay::spring()->active()->first();

        $sessions = CCLSession::active()
            ->when($pdDay, function($query) use ($pdDay) {
                return $query->where('p_d_day_id', $pdDay->id);
            })
            ->with(['division', 'links'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $settings = CCLSetting::getSettings();
        
        // Check user enrollments for each session
        $user = auth()->user();
        $userEnrollments = [];
        if ($user && $pdDay) {
            // Get all schedule items for CCL sessions
            $scheduleItems = \App\Models\ScheduleItem::where('p_d_day_id', $pdDay->id)
                ->where('session_type', 'ccl')
                ->get()
                ->keyBy(function($item) {
                    return $item->date->format('Y-m-d') . '_' . $item->start_time->format('H:i:s') . '_' . $item->title;
                });
            
            // Get user's enrollments
            $enrollments = \App\Models\UserSession::where('user_id', $user->id)
                ->whereNotNull('schedule_item_id')
                ->where('status', '!=', 'cancelled')
                ->with('scheduleItem')
                ->get();
            
            foreach ($sessions as $session) {
                $key = $session->date->format('Y-m-d') . '_' . $session->start_time->format('H:i:s') . '_' . $session->title;
                $scheduleItem = $scheduleItems->get($key);
                
                if ($scheduleItem) {
                    $enrollment = $enrollments->firstWhere('schedule_item_id', $scheduleItem->id);
                    $userEnrollments[$session->id] = $enrollment ? true : false;
                } else {
                    $userEnrollments[$session->id] = false;
                }
            }
        }

        return view('ccl.index', compact('sessions', 'settings', 'pdDay', 'userEnrollments'));
    }

    /**
     * Display a specific CCL session
     */
    public function show(CCLSession $session)
    {
        if (!CCLSetting::isActive() || !$session->is_active) {
            return redirect()->route('spring.ccl')
                ->with('error', 'This session is not currently available.');
        }

        $session->load(['division', 'links', 'pdDay']);
        
        // Check if user is already enrolled
        $user = auth()->user();
        $userEnrollment = null;
        if ($user) {
            // Find the corresponding ScheduleItem for this CCL session
            $scheduleItem = \App\Models\ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                ->where('date', $session->date)
                ->where('start_time', $session->start_time)
                ->where('title', $session->title)
                ->where('session_type', 'ccl')
                ->first();
            
            if ($scheduleItem) {
                $userEnrollment = \App\Models\UserSession::where('user_id', $user->id)
                    ->where('schedule_item_id', $scheduleItem->id)
                    ->where('status', '!=', 'cancelled')
                    ->first();
            }
        }

        return view('ccl.show', compact('session', 'userEnrollment'));
    }

    /**
     * Join a CCL session (enroll user)
     */
    public function join(Request $request, CCLSession $session)
    {
        // Check if CCL feature is active
        if (!CCLSetting::isActive() || !$session->is_active) {
            return back()->with('error', 'This session is not currently available.');
        }

        $user = auth()->user();
        
        // Find the corresponding ScheduleItem for this CCL session
        $scheduleItem = \App\Models\ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
            ->where('date', $session->date)
            ->where('start_time', $session->start_time)
            ->where('title', $session->title)
            ->where('session_type', 'ccl')
            ->first();
        
        if (!$scheduleItem) {
            return back()->with('error', 'Schedule item not found for this session.');
        }
        
        // Check if user is already enrolled in this session
        $existingEnrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $scheduleItem->id)
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if ($existingEnrollment) {
            return back()->with('error', 'You are already enrolled in this session.');
        }
        
        // Check if user is already enrolled in any CCL session
        $existingCCLEnrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->whereHas('scheduleItem', function($query) {
                $query->where('session_type', 'ccl');
            })
            ->where('status', '!=', 'cancelled')
            ->first();
        
        if ($existingCCLEnrollment) {
            return back()->with('error', 'You can only join one CCL session. You are already enrolled in another CCL session.');
        }
        
        // Check for time conflicts with wellness session
        $userWellnessEnrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->whereNotNull('wellness_session_id')
            ->where('status', '!=', 'cancelled')
            ->with('wellnessSession')
            ->first();
        
        if ($userWellnessEnrollment && $userWellnessEnrollment->wellnessSession) {
            $wellnessSession = $userWellnessEnrollment->wellnessSession;
            // Check if times overlap
            $tttStart = $session->start_time;
            $tttEnd = $session->end_time;
            $wellnessStart = $wellnessSession->start_time;
            $wellnessEnd = $wellnessSession->end_time;
            
            if ($tttStart < $wellnessEnd && $tttEnd > $wellnessStart) {
                return back()->with('error', 'This CCL session conflicts with your selected wellness session time. Please select a CCL session at a different time.');
            }
        }
        
        // Use database transaction with locking to prevent race conditions
        try {
            $result = \DB::transaction(function() use ($user, $scheduleItem) {
                // Lock the schedule item record for update
                $lockedItem = \App\Models\ScheduleItem::where('id', $scheduleItem->id)
                    ->lockForUpdate()
                    ->first();
                
                if (!$lockedItem) {
                    throw new \Exception('Schedule item not found.');
                }
                
                // Check capacity if max_participants is set
                if ($lockedItem->max_participants !== null) {
                    $hasCapacity = $lockedItem->current_enrollment < $lockedItem->max_participants;
                    
                    if (!$hasCapacity) {
                        throw new \Exception('This session is full.');
                    }
                }
                
                // Create enrollment record
                $enrollment = \App\Models\UserSession::create([
                    'user_id' => $user->id,
                    'schedule_item_id' => $lockedItem->id,
                    'status' => 'confirmed',
                    'enrolled_at' => now(),
                ]);
                
                // Update enrollment count if max_participants is set
                if ($lockedItem->max_participants !== null) {
                    $lockedItem->increment('current_enrollment');
                }
                
                return [
                    'enrollment' => $enrollment,
                    'scheduleItem' => $lockedItem->fresh()
                ];
            });
            
            return back()->with('success', 'Successfully joined the session!');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
