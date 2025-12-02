<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PDDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PDDayController extends Controller
{
    /**
     * Display a listing of PD days
     */
    public function index()
    {
        $pdDays = PDDay::withCount(['scheduleItems', 'wellnessSessions'])
            ->latest('start_date')
            ->paginate(15);

        return view('admin.pddays.index', compact('pdDays'));
    }

    /**
     * Show the form for creating a new PD day
     */
    public function create()
    {
        return view('admin.pddays.create');
    }

    /**
     * Store a newly created PD day
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // If setting this as active, deactivate all others
        if ($request->boolean('is_active')) {
            PDDay::where('is_active', true)->update(['is_active' => false]);
        }

        PDDay::create($validated);

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PD Day created successfully.');
    }

    /**
     * Show the form for editing a PD day
     */
    public function edit(PDDay $pdday)
    {
        return view('admin.pddays.edit', compact('pdday'));
    }

    /**
     * Update the specified PD day
     */
    public function update(Request $request, PDDay $pdday)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        // If setting this as active, deactivate all others
        if ($request->boolean('is_active') && !$pdday->is_active) {
            PDDay::where('is_active', true)->update(['is_active' => false]);
        }

        $pdday->update($validated);

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PD Day updated successfully.');
    }

    /**
     * Toggle the active status of a PD day
     */
    public function toggleActive(PDDay $pdday)
    {
        DB::transaction(function () use ($pdday) {
            if (!$pdday->is_active) {
                // Deactivate all other PD days
                PDDay::where('is_active', true)->update(['is_active' => false]);
                $pdday->update(['is_active' => true]);
            } else {
                $pdday->update(['is_active' => false]);
            }
        });

        $status = $pdday->fresh()->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "PD Day {$status} successfully.");
    }

    /**
     * Remove the specified PD day
     */
    public function destroy(PDDay $pdday)
    {
        // Check if there are any associated sessions
        $sessionsCount = $pdday->scheduleItems()->count() + $pdday->wellnessSessions()->count();
        
        if ($sessionsCount > 0) {
            return redirect()
                ->back()
                ->with('error', "Cannot delete PD Day with {$sessionsCount} associated sessions. Please delete or reassign sessions first.");
        }

        $pdday->delete();

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PD Day deleted successfully.');
    }
}
