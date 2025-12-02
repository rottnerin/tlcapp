<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleItemController extends Controller
{
    /**
     * Display a listing of schedule items
     */
    public function index(Request $request)
    {
        $query = ScheduleItem::with('divisions');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('presenter_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Division filter
        if ($request->filled('division_id')) {
            $query->whereHas('divisions', function($q) use ($request) {
                $q->where('divisions.id', $request->division_id);
            });
        }

        // Type filter
        if ($request->filled('session_type')) {
            $query->where('session_type', $request->session_type);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('start_time', $request->date);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $scheduleItems = $query->orderBy('start_time')
            ->paginate(15);

        // Get filter options
        $divisions = Division::orderBy('name')->get();
        $types = ScheduleItem::select('session_type')
            ->whereNotNull('session_type')
            ->distinct()
            ->pluck('session_type')
            ->sort();

        $availableDates = ScheduleItem::selectRaw('DATE(start_time) as date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date');

        return view('admin.schedule.index', compact(
            'scheduleItems', 
            'divisions', 
            'types', 
            'availableDates'
        ));
    }

    /**
     * Show the form for creating a new schedule item
     */
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        
        return view('admin.schedule.create', compact('divisions'));
    }

    /**
     * Store a newly created schedule item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'item_type' => 'required|in:session,break,meal,assembly,transition,meeting,other',
            'division_id' => 'required|exists:divisions,id',
            'presenter_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'notes' => 'nullable|string',
            'link_title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:500',
            'link_description' => 'nullable|string|max:1000',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Check for time conflicts within the same division
        $conflict = ScheduleItem::where('division_id', $validated['division_id'])
            ->where('is_active', true)
            ->where(function($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'This time conflicts with an existing schedule item for this division.']);
        }

        $validated['is_required'] = $request->has('is_required');
        $validated['is_active'] = $request->has('is_active') ? true : false;

        ScheduleItem::create($validated);

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule item created successfully!');
    }

    /**
     * Display the specified schedule item
     */
    public function show(ScheduleItem $schedule)
    {
        $schedule->load('divisions');
        
        return view('admin.schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule item
     */
    public function edit(ScheduleItem $schedule)
    {
        $schedule->load('divisions');
        $divisions = Division::orderBy('name')->get();
        
        return view('admin.schedule.edit', compact('schedule', 'divisions'));
    }

    /**
     * Update the specified schedule item
     */
    public function update(Request $request, ScheduleItem $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_type' => 'required|in:fixed,wellness,keynote,break,lunch,transition,regular',
            'presenter_primary' => 'nullable|string|max:255',
            'presenter_secondary' => 'nullable|string|max:255',
            'presenter_bio' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_participants' => 'nullable|integer|min:1|max:500',
            'equipment_needed' => 'nullable|string',
            'special_requirements' => 'nullable|string',
            'link_title' => 'nullable|string|max:255',
            'link_url' => 'nullable|url|max:500',
            'link_description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'divisions' => 'nullable|array',
            'divisions.*' => 'exists:divisions,id',
        ]);

        // Combine date and times for datetime fields
        $validated['start_time'] = $validated['date'] . ' ' . $validated['start_time'];
        $validated['end_time'] = $validated['date'] . ' ' . $validated['end_time'];

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $schedule->update($validated);

        // Sync divisions
        if ($request->has('divisions')) {
            $schedule->divisions()->sync($request->divisions);
        } else {
            $schedule->divisions()->detach();
        }

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule item updated successfully!');
    }

    /**
     * Remove the specified schedule item
     */
    public function destroy(ScheduleItem $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.schedule.index')
            ->with('success', 'Schedule item deleted successfully!');
    }

    /**
     * Toggle schedule item active status
     */
    public function toggleStatus(ScheduleItem $schedule)
    {
        $schedule->update(['is_active' => !$schedule->is_active]);
        
        $status = $schedule->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Schedule item {$status} successfully!");
    }

    /**
     * Bulk update schedule items
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'items' => 'required|array|min:1',
            'items.*' => 'exists:schedule_items,id'
        ]);

        $items = ScheduleItem::whereIn('id', $validated['items']);

        switch ($validated['action']) {
            case 'activate':
                $items->update(['is_active' => true]);
                $message = 'Selected items activated successfully!';
                break;
            case 'deactivate':
                $items->update(['is_active' => false]);
                $message = 'Selected items deactivated successfully!';
                break;
            case 'delete':
                $items->delete();
                $message = 'Selected items deleted successfully!';
                break;
        }

        return back()->with('success', $message);
    }
}
