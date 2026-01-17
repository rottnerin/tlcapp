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
            'season' => 'nullable|in:fall,spring',
            'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$/',
        ]);

        // If setting this as active, deactivate others of the same season
        if ($request->boolean('is_active') && $validated['season']) {
            PDDay::where('is_active', true)
                ->where('season', $validated['season'])
                ->update(['is_active' => false]);
        }

        PDDay::create($validated);

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PL Day created successfully.');
    }

    /**
     * Show the form for editing a PD day
     */
    public function edit(PDDay $pdday)
    {
        // Block editing of archived PL Days
        if ($pdday->isArchived()) {
            return redirect()
                ->route('admin.pddays.index')
                ->with('error', 'Archived PL Days cannot be edited. Please unarchive first.');
        }

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
            'season' => 'nullable|in:fall,spring',
            'academic_year' => 'nullable|string|max:9|regex:/^\d{4}-\d{4}$/',
        ]);

        // If setting this as active, deactivate others of the same season
        $season = $validated['season'] ?? $pdday->season;
        if ($request->boolean('is_active') && ! $pdday->is_active && $season) {
            PDDay::where('is_active', true)
                ->where('season', $season)
                ->update(['is_active' => false]);
        }

        $pdday->update($validated);

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PL Day updated successfully.');
    }

    /**
     * Toggle the active status of a PD day
     */
    public function toggleActive(PDDay $pdday)
    {
        // Block activating archived PL Days
        if ($pdday->isArchived()) {
            return redirect()
                ->back()
                ->with('error', 'Archived PL Days cannot be activated. Please unarchive first.');
        }

        DB::transaction(function () use ($pdday) {
            if (! $pdday->is_active) {
                // Deactivate other PD days of the same season
                if ($pdday->season) {
                    PDDay::where('is_active', true)
                        ->where('season', $pdday->season)
                        ->update(['is_active' => false]);
                }
                $pdday->update(['is_active' => true]);
            } else {
                $pdday->update(['is_active' => false]);
            }
        });

        $status = $pdday->fresh()->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "PL Day {$status} successfully.");
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
                ->with('error', "Cannot delete PL Day with {$sessionsCount} associated sessions. Please delete or reassign sessions first.");
        }

        $pdday->delete();

        return redirect()
            ->route('admin.pddays.index')
            ->with('success', 'PL Day deleted successfully.');
    }

    /**
     * Archive a PD day
     */
    public function archive(PDDay $pdday)
    {
        // PL Day must be inactive to be archived
        if ($pdday->is_active) {
            return redirect()
                ->back()
                ->with('error', 'Cannot archive an active PL Day. Please deactivate it first.');
        }

        if ($pdday->isArchived()) {
            return redirect()
                ->back()
                ->with('error', 'This PL Day is already archived.');
        }

        $pdday->archive();

        return redirect()
            ->back()
            ->with('success', 'PL Day archived successfully.');
    }

    /**
     * Unarchive a PD day
     */
    public function unarchive(PDDay $pdday)
    {
        if (! $pdday->isArchived()) {
            return redirect()
                ->back()
                ->with('error', 'This PL Day is not archived.');
        }

        $pdday->unarchive();

        return redirect()
            ->back()
            ->with('success', 'PL Day unarchived successfully.');
    }
}
