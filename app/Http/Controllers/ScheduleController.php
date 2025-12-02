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
            Carbon::create(2025, 9, 25),
            Carbon::create(2025, 9, 26),
        ];
        
        // Get division filter preference
        $selectedDivisions = $request->get('divisions', $user->division_id ? [$user->division_id] : []);
        
        // Get schedule items for the event dates
        $scheduleItems = ScheduleItem::active()
            ->whereIn('date', $eventDates)
            ->when($selectedDivisions, function($query) use ($selectedDivisions) {
                return $query->forDivisions($selectedDivisions);
            })
            ->with(['divisions'])
            ->orderBy('date')
            ->orderBy('start_time')
            ->get()
            ->groupBy('date');
        
        return view('schedule.index', compact(
            'user',
            'divisions', 
            'selectedDivisions',
            'scheduleItems',
            'eventDates'
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
