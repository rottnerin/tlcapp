<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\Division;
use App\Models\PDDay;
use App\Models\PLWednesdaySetting;
use App\Models\PLDaysSetting;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedule items
     */
    public function index(Request $request)
    {
        // Initialize settings
        PLDaysSetting::initialize();
        PLWednesdaySetting::initialize();
        
        // Check if PL Days feature is enabled
        if (!PLDaysSetting::isActive()) {
            // Redirect to PL Wednesday as fallback
            return redirect()->route('pl-wednesday.index');
        }

        $user = auth()->user(); 
        $divisions = Division::active()->get();
        
        // Get active PD Day
        $activePDDay = PDDay::getActive();
        
        // Check if there are any active schedule items in PL Days
        $hasActivePLDaysSessions = false;
        if ($activePDDay) {
            $hasActivePLDaysSessions = ScheduleItem::active()
                ->where('p_d_day_id', $activePDDay->id)
                ->exists();
        }
        
        // If no active PD Day or no active sessions, redirect to PL Wednesday
        if (!$activePDDay || !$hasActivePLDaysSessions) {
            return redirect()->route('pl-wednesday.index');
        }
        
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
                ->where('p_d_day_id', $activePDDay->id)
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
                
                // Create wellness schedule item with proper Carbon datetime instances
                $wellnessStartTime = Carbon::parse($userWellnessSession->start_time);
                $wellnessEndTime = Carbon::parse($userWellnessSession->end_time);
                
                // Add the user's wellness session to the schedule items
                $wellnessScheduleItem = new \App\Models\ScheduleItem([
                    'title' => $userWellnessSession->title,
                    'description' => $userWellnessSession->description,
                    'location' => $userWellnessSession->location,
                    'start_time' => $wellnessStartTime,
                    'end_time' => $wellnessEndTime,
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
                
                // Re-sort by start_time chronologically (ensuring proper Carbon comparison)
                $scheduleItems = $scheduleItems->sortBy(function($item) {
                    return $item->start_time ? $item->start_time->timestamp : 0;
                })->values();
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
        // Check if PL Days feature is enabled
        PLDaysSetting::initialize();
        if (!PLDaysSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();
        
        // Load relationships
        $scheduleItem->load(['divisions']);
        
        return view('schedule.show', compact('scheduleItem', 'user'));
    }

    /**
     * Display Fall PL Day schedule
     */
    public function fallIndex(Request $request)
    {
        return $this->seasonIndex($request, 'fall');
    }

    /**
     * Display Spring PL Days schedule
     */
    public function springIndex(Request $request)
    {
        return $this->seasonIndex($request, 'spring');
    }

    /**
     * Display schedule for a specific season
     */
    protected function seasonIndex(Request $request, string $season)
    {
        PLDaysSetting::initialize();
        PLWednesdaySetting::initialize();
        
        if (!PLDaysSetting::isActive()) {
            return redirect()->route('pl-wednesday.index');
        }

        $user = auth()->user(); 
        $divisions = Division::active()->get();
        
        // Get active PD Day for this season
        $activePDDay = PDDay::where('is_active', true)
            ->where('season', $season)
            ->first();
        
        // Fallback to any active PD day if season-specific not found
        if (!$activePDDay) {
            $activePDDay = PDDay::getActive();
        }
        
        $hasActivePLDaysSessions = false;
        if ($activePDDay) {
            $hasActivePLDaysSessions = ScheduleItem::active()
                ->where('p_d_day_id', $activePDDay->id)
                ->exists();
        }
        
        // Generate date range
        $eventDates = [];
        if ($activePDDay) {
            $start = Carbon::parse($activePDDay->start_date);
            $end = Carbon::parse($activePDDay->end_date);
            
            while ($start->lte($end)) {
                $eventDates[] = $start->copy();
                $start->addDay();
            }
        }
        
        $activeTab = $request->get('day', 'day1');
        $dayIndex = (int) str_replace('day', '', $activeTab) - 1;
        $selectedDate = $eventDates[$dayIndex] ?? null;
        
        $selectedDivisions = $request->get('divisions', []);
        
        if (empty($selectedDivisions)) {
            $allSchoolSelected = true;
            $allDivisionsSelected = true;
        } else {
            $allSchoolSelected = false;
            $allDivisionsSelected = false;
        }
        
        $scheduleItems = collect();
        if ($selectedDate && $activePDDay) {
            $scheduleItems = ScheduleItem::active()
                ->where('p_d_day_id', $activePDDay->id)
                ->whereDate('date', $selectedDate)
                ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                    return $query->whereHas('divisions', function($subQ) use ($selectedDivisions) {
                        $subQ->whereIn('divisions.id', $selectedDivisions);
                    });
                })
                ->with(['divisions'])
                ->orderBy('start_time')
                ->get();
        }
        
        // Handle wellness session replacement
        $userWellnessSession = null;
        if ($activeTab === 'day2') {
            $userWellnessEnrollment = \App\Models\UserSession::where('user_id', $user->id)
                ->whereNotNull('wellness_session_id')
                ->where('status', '!=', 'cancelled')
                ->with('wellnessSession')
                ->first();
            
            if ($userWellnessEnrollment && $userWellnessEnrollment->wellnessSession) {
                $userWellnessSession = $userWellnessEnrollment->wellnessSession;
                
                $scheduleItems = $scheduleItems->filter(function($item) {
                    return !str_contains(strtolower($item->title), 'community culture and wellbeing');
                });
                
                // Create wellness schedule item with proper Carbon datetime instances
                $wellnessStartTime = Carbon::parse($userWellnessSession->start_time);
                $wellnessEndTime = Carbon::parse($userWellnessSession->end_time);
                
                $wellnessScheduleItem = new ScheduleItem([
                    'title' => $userWellnessSession->title,
                    'description' => $userWellnessSession->description,
                    'location' => $userWellnessSession->location,
                    'start_time' => $wellnessStartTime,
                    'end_time' => $wellnessEndTime,
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
                
                // Sort by start_time chronologically (ensuring proper Carbon comparison)
                $scheduleItems = $scheduleItems->sortBy(function($item) {
                    return $item->start_time ? $item->start_time->timestamp : 0;
                })->values();
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
        ))->with('season', $season);
    }

    /**
     * Display archived PL content by academic year
     */
    public function archive(Request $request)
    {
        $user = auth()->user();
        $academicYear = $request->get('year', PDDay::getCurrentAcademicYear());
        
        // Get all PD days for the selected academic year
        $pdDays = PDDay::where('academic_year', $academicYear)
            ->orderBy('start_date')
            ->get();

        // Get available academic years
        $academicYears = PDDay::select('academic_year')
            ->distinct()
            ->whereNotNull('academic_year')
            ->pluck('academic_year')
            ->filter()
            ->values();

        return view('archive.index', compact('user', 'pdDays', 'academicYear', 'academicYears'));
    }
}
