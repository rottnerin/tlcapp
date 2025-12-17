<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PLWednesdaySession;
use App\Models\PLWednesdayLink;
use App\Models\PLWednesdaySetting;
use App\Models\Division;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PLWednesdayController extends Controller
{
    /**
     * Display a listing of PL Wednesday sessions
     */
    public function index(Request $request)
    {
        // Initialize settings if they don't exist
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();

        $query = PLWednesdaySession::with(['links', 'division']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } else if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $sessions = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        return view('admin.pl-wednesday.index', compact('sessions', 'settings'));
    }

    /**
     * Show the form for creating a new PL Wednesday session
     */
    public function create()
    {
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();
        
        // Generate list of valid Wednesday dates
        $wednesdayDates = $this->getWednesdayDates($settings->start_date, $settings->end_date);
        
        // Get all active divisions
        $divisions = Division::active()->orderBy('name')->get();

        return view('admin.pl-wednesday.create', compact('settings', 'wednesdayDates', 'divisions'));
    }

    /**
     * Store a newly created PL Wednesday session
     */
    public function store(Request $request)
    {
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($settings) {
                    $date = Carbon::parse($value);
                    // Check if it's a Wednesday
                    if ($date->dayOfWeek !== Carbon::WEDNESDAY) {
                        $fail('The selected date must be a Wednesday.');
                    }
                    // Check if it's within the date range
                    if ($date->lt($settings->start_date) || $date->gt($settings->end_date)) {
                        $fail('The selected date must be between ' . $settings->start_date->format('M j, Y') . ' and ' . $settings->end_date->format('M j, Y') . '.');
                    }
                },
            ],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'nullable|boolean',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links.*.url|string|max:255',
            'links.*.url' => 'required_with:links.*.title|string|max:500',
            'links.*.description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Convert time strings to time format
            $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
            $validated['end_time'] = Carbon::parse($validated['end_time'])->format('H:i:s');
            
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Create session
            $session = PLWednesdaySession::create($validated);
            
            // Calculate and save duration
            $session->calculateDuration();
            $session->save();

            // Create links if provided
            if ($request->has('links') && is_array($request->links)) {
                foreach ($request->links as $index => $linkData) {
                    if (!empty($linkData['title']) && !empty($linkData['url'])) {
                        PLWednesdayLink::create([
                            'pl_wednesday_session_id' => $session->id,
                            'title' => $linkData['title'],
                            'url' => $linkData['url'],
                            'description' => $linkData['description'] ?? null,
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.pl-wednesday.index')
                ->with('success', 'PL Wednesday session created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create session: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified PL Wednesday session
     */
    public function show($pl_wednesday)
    {
        $plWednesday = PLWednesdaySession::findOrFail($pl_wednesday);
        $plWednesday->load('links');
        return view('admin.pl-wednesday.show', compact('plWednesday'));
    }

    /**
     * Show the form for editing the specified PL Wednesday session
     */
    public function edit($pl_wednesday)
    {
        $plWednesday = PLWednesdaySession::findOrFail($pl_wednesday);
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();
        
        // Generate list of valid Wednesday dates
        $wednesdayDates = $this->getWednesdayDates($settings->start_date, $settings->end_date);
        
        // Get all active divisions
        $divisions = Division::active()->orderBy('name')->get();
        
        $plWednesday->load('links');

        return view('admin.pl-wednesday.edit', compact('plWednesday', 'settings', 'wednesdayDates', 'divisions'));
    }

    /**
     * Update the specified PL Wednesday session
     */
    public function update(Request $request, $pl_wednesday)
    {
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'division_id' => 'nullable|exists:divisions,id',
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($settings) {
                    $date = Carbon::parse($value);
                    // Check if it's a Wednesday
                    if ($date->dayOfWeek !== Carbon::WEDNESDAY) {
                        $fail('The selected date must be a Wednesday.');
                    }
                    // Check if it's within the date range
                    if ($date->lt($settings->start_date) || $date->gt($settings->end_date)) {
                        $fail('The selected date must be between ' . $settings->start_date->format('M j, Y') . ' and ' . $settings->end_date->format('M j, Y') . '.');
                    }
                },
            ],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'nullable|boolean',
            'links' => 'nullable|array',
            'links.*.title' => 'required_with:links.*.url|string|max:255',
            'links.*.url' => 'required_with:links.*.url|string|max:500',
            'links.*.description' => 'nullable|string',
        ]);

        $plWednesday = PLWednesdaySession::findOrFail($pl_wednesday);
        
        DB::beginTransaction();
        try {
            // Convert time strings to time format
            $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
            $validated['end_time'] = Carbon::parse($validated['end_time'])->format('H:i:s');
            
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Update session
            $plWednesday->update($validated);
            
            // Calculate and save duration
            $plWednesday->calculateDuration();
            $plWednesday->save();

            // Delete existing links
            $plWednesday->links()->delete();

            // Create new links if provided
            if ($request->has('links') && is_array($request->links)) {
                foreach ($request->links as $index => $linkData) {
                    if (!empty($linkData['title']) && !empty($linkData['url'])) {
                        PLWednesdayLink::create([
                            'pl_wednesday_session_id' => $plWednesday->id,
                            'title' => $linkData['title'],
                            'url' => $linkData['url'],
                            'description' => $linkData['description'] ?? null,
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.pl-wednesday.index')
                ->with('success', 'PL Wednesday session updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update session: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified PL Wednesday session
     */
    public function destroy($pl_wednesday)
    {
        // Route model binding in AppServiceProvider should provide the model instance
        // But if it doesn't, fall back to finding it
        if (!($pl_wednesday instanceof PLWednesdaySession)) {
            $pl_wednesday = PLWednesdaySession::findOrFail($pl_wednesday);
        }
        
        $pl_wednesday->delete();

        return redirect()->route('admin.pl-wednesday.index')
            ->with('success', 'PL Wednesday session deleted successfully!');
    }

    /**
     * Toggle PL Wednesday feature activation
     */
    public function toggleActive()
    {
        PLWednesdaySetting::initialize();
        $settings = PLWednesdaySetting::getActive();
        
        $settings->update(['is_active' => !$settings->is_active]);
        
        $status = $settings->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "PL Wednesday feature {$status} successfully!");
    }

    /**
     * Toggle individual session active status
     */
    public function toggleSessionStatus($plWednesday)
    {
        $session = PLWednesdaySession::findOrFail($plWednesday);
        $session->update(['is_active' => !$session->is_active]);
        
        $status = $session->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "PL Wednesday session {$status} successfully!");
    }

    /**
     * Get all Wednesday dates between start and end date
     */
    private function getWednesdayDates($startDate, $endDate): array
    {
        $dates = [];
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        // Find first Wednesday
        while ($current->dayOfWeek !== Carbon::WEDNESDAY && $current->lte($end)) {
            $current->addDay();
        }

        // Collect all Wednesdays
        while ($current->lte($end)) {
            $dates[] = [
                'value' => $current->format('Y-m-d'),
                'label' => $current->format('l, F j, Y'),
            ];
            $current->addWeek();
        }

        return $dates;
    }
}
