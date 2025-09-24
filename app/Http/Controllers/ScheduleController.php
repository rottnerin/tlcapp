<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\Division;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedule items
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $divisions = Division::active()->get();
        
        // Get Professional Learning Days dates
        $eventDates = [
            Carbon::create(2025, 9, 25), // Day 1
            Carbon::create(2025, 9, 26), // Day 2
        ];
        
        // Get active tab (default to Day 1)
        $activeTab = $request->get('day', 'day1');
        $selectedDate = $activeTab === 'day2' ? $eventDates[1] : $eventDates[0];
        
        // Get division filter preference (default to all divisions for better UX)
        $selectedDivisions = $request->get('divisions', []);
        
        // Handle checkbox filter
        if (empty($selectedDivisions)) {
            $allSchoolSelected = true;
            $allDivisionsSelected = true;
        } else {
            $allSchoolSelected = false;
            $allDivisionsSelected = false;
        }
        
        // Get schedule items for the selected date
        $scheduleItems = ScheduleItem::active()
            ->whereDate('date', $selectedDate)
            ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                // If specific divisions are selected, show items that are assigned to those divisions
                return $query->whereHas('divisions', function($subQ) use ($selectedDivisions) {
                    $subQ->whereIn('divisions.id', $selectedDivisions);
                });
            })
            ->with(['divisions', 'links'])
            ->orderBy('start_time')
            ->get();
        
        // Handle wellness session replacement for Day 2
        $userWellnessSession = null;
        if ($activeTab === 'day2') {
            $userWellnessEnrollment = \App\Models\UserSession::where('user_id', $user->id)
                ->whereNotNull('wellness_session_id')
                ->where('status', '!=', 'cancelled')
                ->with('wellnessSession')
                ->first();
            
            if ($userWellnessEnrollment && $userWellnessEnrollment->wellnessSession) {
                $userWellnessSession = $userWellnessEnrollment->wellnessSession;
                
                // Remove the "Community Culture and Wellbeing" session and replace with user's wellness session
                $scheduleItems = $scheduleItems->filter(function($item) {
                    return !str_contains(strtolower($item->title), 'community culture and wellbeing');
                });
                
                // Add the user's wellness session to the schedule items
                $wellnessScheduleItem = new \App\Models\ScheduleItem([
                    'title' => $userWellnessSession->title,
                    'description' => $userWellnessSession->description,
                    'location' => $userWellnessSession->location,
                    'start_time' => $userWellnessSession->start_time,
                    'end_time' => $userWellnessSession->end_time,
                    'date' => $userWellnessSession->date,
                    'presenter_primary' => $userWellnessSession->presenter_name,
                    'presenter_bio' => $userWellnessSession->presenter_bio,
                    'presenter_email' => $userWellnessSession->presenter_email,
                    'max_participants' => $userWellnessSession->max_participants,
                    'current_enrollment' => $userWellnessSession->current_enrollment,
                    'equipment_needed' => $userWellnessSession->equipment_needed,
                    'special_requirements' => $userWellnessSession->special_requirements,
                    'is_active' => true,
                    'session_type' => 'wellness'
                ]);
                
                // Add wellness session to the collection
                $scheduleItems->push($wellnessScheduleItem);
                
                // Re-sort by start time and reset keys
                $scheduleItems = $scheduleItems->sortBy('start_time')->values();
            }
        }
        
        return view('schedule.index', compact(
            'user',
            'divisions', 
            'selectedDivisions',
            'allDivisionsSelected',
            'allSchoolSelected',
            'scheduleItems',
            'userWellnessSession',
            'eventDates',
            'activeTab',
            'selectedDate'
        ));
    }

    /**
     * Display the specified schedule item
     */
    public function show(ScheduleItem $scheduleItem)
    {
        $user = auth()->user();
        
        // Load relationships
        $scheduleItem->load(['divisions']);
        
        return view('schedule.show', compact('scheduleItem', 'user'));
    }
}
