<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\Division;
use App\Models\PDDay;
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
        
        // Get active PD Day
        $activePDDay = PDDay::getActive();
        
        // Generate date range from PD Day if available
        $eventDates = [];
        if ($activePDDay) {
            $start = Carbon::parse($activePDDay->start_date);
            $end = Carbon::parse($activePDDay->end_date);
            
            while ($start->lte($end)) {
                $eventDates[] = $start->copy();
                $start->addDay();
            }
        }
        
        // Get active tab (default to Day 1)
        $activeTab = $request->get('day', 'day1');
        $dayIndex = (int) str_replace('day', '', $activeTab) - 1;
        $selectedDate = $eventDates[$dayIndex] ?? null;
        
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
        $scheduleItems = collect();
        if ($selectedDate && $activePDDay) {
            $scheduleItems = ScheduleItem::active()
                ->where('pd_day_id', $activePDDay->id)
                ->whereDate('date', $selectedDate)
                ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                    // If specific divisions are selected, show items that are assigned to those divisions
                    return $query->whereHas('divisions', function($subQ) use ($selectedDivisions) {
                        $subQ->whereIn('divisions.id', $selectedDivisions);
                    });
                })
                ->with(['divisions'])
                ->orderBy('start_time')
                ->get();
        }
        
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
