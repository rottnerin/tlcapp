<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\WellnessSession;
use App\Models\Division;
use App\Models\PDDay;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
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
        
        // Get user's division filter preference
        $selectedDivisions = $request->get('divisions', $user->division_id ? [$user->division_id] : []);
        
        // Get schedule items for the event dates
        $scheduleItems = ScheduleItem::active()
            ->when($activePDDay, function($query) use ($activePDDay) {
                return $query->where('pd_day_id', $activePDDay->id);
            })
            ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                return $query->forDivisions($selectedDivisions);
            })
            ->with(['divisions'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');
        
        // Get user's enrolled sessions
        $userEnrollments = $user->userSessions()
            ->with(['wellnessSession', 'scheduleItem'])
            ->where('status', 'confirmed')
            ->get();
        
        // Get upcoming wellness sessions
        $upcomingWellness = WellnessSession::active()
            ->when($activePDDay, function($query) use ($activePDDay) {
                return $query->where('pd_day_id', $activePDDay->id);
            })
            ->withCapacity()
            ->orderBy('date')
            ->orderBy('start_time')
            ->take(6)
            ->get();
        
        return view('dashboard.index', compact(
            'user',
            'divisions', 
            'selectedDivisions',
            'scheduleItems',
            'userEnrollments',
            'upcomingWellness',
            'eventDates',
            'activePDDay'
        ));
    }

    /**
     * Get personalized schedule for user
     */
    public function mySchedule()
    {
        $user = auth()->user();
        
        // Get user's enrolled sessions
        $enrolledSessions = $user->userSessions()
            ->with(['wellnessSession', 'scheduleItem'])
            ->where('status', 'confirmed')
            ->get();
        
        // Get division-specific schedule items
        $scheduleItems = ScheduleItem::active()
            ->when($user->division_id, function($query) use ($user) {
                return $query->forDivisions([$user->division_id]);
            })
            ->with(['divisions'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();
        
        return view('dashboard.my-schedule', compact(
            'user',
            'enrolledSessions',
            'scheduleItems'
        ));
    }
}
