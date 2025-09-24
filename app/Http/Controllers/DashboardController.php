<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleItem;
use App\Models\WellnessSession;
use App\Models\Division;
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
        
        // Get Professional Learning Days dates
        $eventDates = [
            Carbon::create(2025, 9, 25),
            Carbon::create(2025, 9, 26),
        ];
        
        // Get user's division filter preference
        $selectedDivisions = $request->get('divisions', $user->division_id ? [$user->division_id] : []);
        
        // Get schedule items for the event dates
        $scheduleItems = ScheduleItem::active()
            ->whereIn('date', $eventDates)
            ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                return $query->forDivisions($selectedDivisions);
            })
            ->with(['divisions', 'links'])
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
            ->whereIn('date', $eventDates)
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
            'eventDates'
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
            ->with(['divisions', 'links'])
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
