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
        $query = ScheduleItem::with('divisions')->divisionOnly();

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

        // Get filter options (scoped to division-only items)
        $divisions = Division::orderBy('name')->get();
        $types = ScheduleItem::divisionOnly()
            ->select('session_type')
            ->whereNotNull('session_type')
            ->distinct()
            ->pluck('session_type')
            ->sort();

        $availableDates = ScheduleItem::divisionOnly()
            ->selectRaw('DATE(start_time) as date')
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

        // Divisions are stored in pivot table, not on schedule_items
        $divisionId = $validated['division_id'] ?? null;
        unset($validated['division_id']);

        $scheduleItem = ScheduleItem::create($validated);

        if ($divisionId) {
            $scheduleItem->divisions()->attach($divisionId);
        }

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

        // Load confirmed participants
        $confirmedParticipants = \App\Models\UserSession::where('schedule_item_id', $schedule->id)
            ->where('status', 'confirmed')
            ->with('user.division')
            ->orderBy('enrolled_at')
            ->get();

        return view('admin.schedule.edit', compact('schedule', 'divisions', 'pdDays', 'wellnessSessions', 'confirmedParticipants'));
    }

    /**
     * Update the specified schedule item
     */
    public function update(Request $request, ScheduleItem $schedule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'session_type' => 'nullable|in:fixed,wellness,keynote,break,lunch,transition,regular,nts_optional',
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

        // Keep existing session_type if none selected (field is optional)
        if (empty($validated['session_type'])) {
            $validated['session_type'] = $schedule->session_type ?? 'regular';
        }

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
     * Remove a user's enrollment from this schedule item (e.g. NTS Optional Sign-up, CCL).
     */
    public function removeEnrollment(Request $request, ScheduleItem $schedule)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = \App\Models\User::findOrFail($request->user_id);

        $enrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->where('schedule_item_id', $schedule->id)
            ->where('status', '!=', 'cancelled')
            ->first();

        if (! $enrollment) {
            return back()->with('error', 'User is not enrolled in this session.');
        }

        $wasConfirmed = $enrollment->status === 'confirmed';
        $enrollment->update(['status' => 'cancelled']);

        if ($wasConfirmed && $schedule->max_participants !== null) {
            $schedule->decrement('current_enrollment');
        }

        return back()->with('success', "Removed {$user->name} from this session.");
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
            $items = $pdDay->scheduleItems()->divisionOnly()->get();
            return [
                'pdDay' => $pdDay,
                'scheduleCount' => $items->count(),
                'scheduleItems' => $items
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
            ->whereHas('scheduleItems', function($q) {
                $q->divisionOnly();
            })
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
        $sourceSchedules = ScheduleItem::where('p_d_day_id', $sourcePdDay->id)->divisionOnly()->get();

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
     * Upload schedule items via CSV.
     *
     * This method only ADDS new items — it never deletes or overwrites existing
     * schedule items (including "All School" items that have no division).
     * If a CSV row includes a "divisions" column (pipe-separated, e.g. "ES|MS|HS"),
     * the imported item will be linked to those divisions. Rows without a divisions
     * value are treated as "All School" (no division attached).
     */
    public function uploadCsv(Request $request, PDDay $pdDay)
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        try {
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            // Pre-load divisions keyed by name (case-insensitive) for fast lookup
            $allDivisions = Division::all()->keyBy(function ($div) {
                return strtoupper(trim($div->name));
            });
            
            $imported = 0;
            $errors = [];
            
            if (($handle = fopen($path, 'r')) !== false) {
                // Read and clean headers (trim BOM and whitespace)
                $headers = fgetcsv($handle);
                $headers = array_map(function ($h) {
                    return trim(preg_replace('/^\x{FEFF}/u', '', $h));
                }, $headers);
                
                $rowNumber = 1;
                while (($row = fgetcsv($handle)) !== false) {
                    $rowNumber++;
                    if (empty(array_filter($row))) continue; // Skip empty rows
                    
                    try {
                        // Guard against column count mismatch
                        if (count($row) !== count($headers)) {
                            throw new \Exception("Column count mismatch (expected " . count($headers) . ", got " . count($row) . ")");
                        }

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
                        $schedule->is_active = isset($data['is_active']) && $data['is_active'] !== '' ? (bool) $data['is_active'] : true;

                        // Handle max_participants / capacity if present
                        if (!empty($data['max_participants'])) {
                            $schedule->max_participants = (int) $data['max_participants'];
                        }
                        
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

                        // Handle link fields
                        if (!empty($data['link_title']) && !empty($data['link_url'])) {
                            $schedule->link_title = $data['link_title'];
                            $schedule->link_url = $data['link_url'];
                            $schedule->link_description = $data['link_description'] ?? null;
                        }
                        
                        $schedule->save();

                        // Attach divisions if the column exists and has a value
                        if (isset($data['divisions']) && trim($data['divisions']) !== '') {
                            $divisionNames = array_map('trim', explode('|', $data['divisions']));
                            $divisionIds = [];

                            foreach ($divisionNames as $divName) {
                                $key = strtoupper($divName);
                                if ($allDivisions->has($key)) {
                                    $divisionIds[] = $allDivisions->get($key)->id;
                                }
                            }

                            if (!empty($divisionIds)) {
                                $schedule->divisions()->attach($divisionIds);
                            }
                        }
                        // If divisions column is empty or missing, the item has no divisions
                        // attached, which makes it an "All School" item — this is intentional.

                        $imported++;
                    } catch (\Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                    }
                }
                fclose($handle);
            }

            $message = "Imported {$imported} schedule items successfully!";
            if (!empty($errors)) {
                $message .= " (" . count($errors) . " error(s): " . implode('; ', array_slice($errors, 0, 3)) . ")";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', "CSV import failed: " . $e->getMessage());
        }
    }
}
