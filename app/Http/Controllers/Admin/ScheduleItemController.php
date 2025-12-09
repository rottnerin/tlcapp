<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduleItem;
use App\Models\ScheduleItemLink;
use App\Models\Division;
use App\Models\PDDay;
use App\Models\WellnessSession;
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

        // Division filter - include items with the selected division OR items with no divisions (All Divisions)
        if ($request->filled('division_id')) {
            $divisionId = $request->division_id;
            $query->where(function($q) use ($divisionId) {
                $q->whereHas('divisions', function($subQ) use ($divisionId) {
                    $subQ->where('divisions.id', $divisionId);
                })->orWhereDoesntHave('divisions'); // Include "All Divisions" items
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
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $wellnessSessions = WellnessSession::orderBy('created_at', 'desc')->get();
        
        return view('admin.schedule.create', compact('divisions', 'pdDays', 'wellnessSessions'));
    }

    /**
     * Store a newly created schedule item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'division_id' => 'required|exists:divisions,id',
            'pd_day_id' => 'nullable|exists:p_d_days,id',
            'presenter_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'links' => 'nullable|array',
            'links.*.title' => 'nullable|string|max:255',
            'links.*.url' => 'nullable|url|max:500',
            'links.*.description' => 'nullable|string|max:1000',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['is_active'] = $request->has('is_active') ? true : false;
        
        // Extract date from start_time for the date field
        $validated['date'] = Carbon::parse($validated['start_time'])->format('Y-m-d');
        
        // Map pd_day_id to p_d_day_id for the database column
        if (isset($validated['pd_day_id'])) {
            $validated['p_d_day_id'] = $validated['pd_day_id'];
            unset($validated['pd_day_id']);
        }
        
        // Remove links from validated data (will be handled separately)
        $links = $validated['links'] ?? [];
        unset($validated['links']);

        $scheduleItem = ScheduleItem::create($validated);
        
        // Create links
        $order = 0;
        foreach ($links as $linkData) {
            // Only create link if both title and url are provided
            if (!empty($linkData['title']) && !empty($linkData['url'])) {
                ScheduleItemLink::create([
                    'schedule_item_id' => $scheduleItem->id,
                    'title' => $linkData['title'],
                    'url' => $linkData['url'],
                    'description' => $linkData['description'] ?? null,
                    'order' => $order++,
                ]);
            }
        }

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
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $wellnessSessions = WellnessSession::orderBy('created_at', 'desc')->get();
        
        return view('admin.schedule.edit', compact('schedule', 'divisions', 'pdDays', 'wellnessSessions'));
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
        
        $status = $schedule->is_active ? 'made visible' : 'hidden';
        
        return back()->with('success', "Schedule item '{$schedule->title}' {$status} successfully!");
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

    /**
     * Show schedule items grouped by PD days
     */
    public function byPdDay()
    {
        $pdDays = PDDay::orderBy('start_date', 'desc')->get();
        $pdDaysWithCounts = $pdDays->map(function($pdDay) {
            return [
                'pdDay' => $pdDay,
                'scheduleCount' => $pdDay->scheduleItems()->count(),
                'scheduleItems' => $pdDay->scheduleItems()->get()
            ];
        });

        return view('admin.schedule.by-pdday', compact('pdDaysWithCounts', 'pdDays'));
    }

    /**
     * Show copy schedule form
     */
    public function showCopyForm(PDDay $pdDay)
    {
        $sourcePdDays = PDDay::where('id', '!=', $pdDay->id)
            ->whereHas('scheduleItems')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('admin.schedule.copy-form', compact('pdDay', 'sourcePdDays'));
    }

    /**
     * Copy schedule from one PD day to another
     */
    public function copySchedule(Request $request, PDDay $pdDay)
    {
        $validated = $request->validate([
            'source_pd_day_id' => 'required|exists:p_d_days,id'
        ]);

        $sourcePdDay = PDDay::findOrFail($validated['source_pd_day_id']);
        $sourceSchedules = ScheduleItem::where('p_d_day_id', $sourcePdDay->id)->get();

        if ($sourceSchedules->isEmpty()) {
            return back()->with('error', "Source PD day has no schedule items to copy.");
        }

        $copied = 0;
        foreach ($sourceSchedules as $sourceSchedule) {
            $newSchedule = $sourceSchedule->replicate();
            $newSchedule->p_d_day_id = $pdDay->id;
            $newSchedule->save();
            
            // Copy divisions
            foreach ($sourceSchedule->divisions as $division) {
                $newSchedule->divisions()->attach($division->id);
            }
            $copied++;
        }

        return back()->with('success', "Copied {$copied} schedule items from {$sourcePdDay->title}");
    }

    /**
     * Upload schedule items via CSV
     */
    public function uploadCsv(Request $request, PDDay $pdDay)
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            $imported = 0;
            $errors = [];
            
            if (($handle = fopen($path, 'r')) !== false) {
                $headers = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (empty(array_filter($row))) continue; // Skip empty rows
                    
                    try {
                        $data = array_combine($headers, $row);
                        
                        // Validate required fields
                        if (empty($data['title'])) {
                            throw new \Exception("Title is required");
                        }
                        
                        // Validate session_type if provided
                        $sessionType = $data['session_type'] ?? 'Fixed';
                        if (!in_array($sessionType, ['Fixed', 'Wellness'])) {
                            throw new \Exception("Session type must be 'Fixed' or 'Wellness'");
                        }
                        
                        $schedule = new ScheduleItem();
                        $schedule->title = $data['title'] ?? null;
                        $schedule->description = $data['description'] ?? null;
                        $schedule->location = $data['location'] ?? null;
                        $schedule->presenter_primary = $data['presenter_primary'] ?? null;
                        $schedule->presenter_secondary = $data['presenter_secondary'] ?? null;
                        $schedule->presenter_bio = $data['presenter_bio'] ?? null;
                        $schedule->session_type = $sessionType;
                        $schedule->equipment_needed = $data['equipment_needed'] ?? null;
                        $schedule->special_requirements = $data['special_requirements'] ?? null;
                        $schedule->p_d_day_id = $pdDay->id;
                        $schedule->is_active = true;
                        
                        // Parse dates if provided
                        if (!empty($data['date'])) {
                            $schedule->date = \Carbon\Carbon::createFromFormat('Y-m-d', $data['date']);
                        }
                        
                        // Parse times if provided
                        if (!empty($data['start_time'])) {
                            $schedule->start_time = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
                        }
                        
                        if (!empty($data['end_time'])) {
                            $schedule->end_time = \Carbon\Carbon::createFromFormat('H:i', $data['end_time']);
                        }
                        
                        $schedule->save();
                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Row error: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }

            $message = "Imported {$imported} schedule items successfully!";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " errors)";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', "CSV import failed: " . $e->getMessage());
        }
    }
}
