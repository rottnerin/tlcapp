<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EarthDayEnrollment;
use App\Models\EarthDayWorkshop;
use App\Models\EarthDaySetting;
use App\Models\User;
use Illuminate\Http\Request;

class EarthDayController extends Controller
{
    public function index()
    {
        $workshops = EarthDayWorkshop::withCount('enrollments')
            ->orderBy('title')
            ->paginate(20);

        $totalWorkshops   = EarthDayWorkshop::count();
        $totalEnrolled    = EarthDayEnrollment::count();
        $totalCapacity    = EarthDayWorkshop::where('is_active', true)->count() * EarthDayWorkshop::CAPACITY;
        $spotsRemaining   = max(0, $totalCapacity - $totalEnrolled);

        EarthDaySetting::initialize();
        $featureActive = EarthDaySetting::isActive();

        return view('admin.earth-day.index', compact(
            'workshops',
            'totalWorkshops',
            'totalEnrolled',
            'spotsRemaining',
            'featureActive'
        ));
    }

    public function create()
    {
        return view('admin.earth-day.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'presenter'  => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        EarthDayWorkshop::create($validated);

        return redirect()->route('admin.earth-day.index')
            ->with('success', 'Workshop created successfully!');
    }

    public function show(EarthDayWorkshop $earthDay)
    {
        $earthDay->load(['enrollments.user.division']);

        return view('admin.earth-day.show', compact('earthDay'));
    }

    public function edit(EarthDayWorkshop $earthDay)
    {
        $earthDay->load(['enrollments.user.division']);

        return view('admin.earth-day.edit', compact('earthDay'));
    }

    public function update(Request $request, EarthDayWorkshop $earthDay)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'presenter'  => 'nullable|string|max:255',
            'location'   => 'nullable|string|max:255',
            'description'=> 'nullable|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',
            'is_active'  => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $earthDay->update($validated);

        return redirect()->route('admin.earth-day.index')
            ->with('success', 'Workshop updated successfully!');
    }

    public function destroy(EarthDayWorkshop $earthDay)
    {
        $earthDay->delete();

        return redirect()->route('admin.earth-day.index')
            ->with('success', 'Workshop deleted successfully!');
    }

    public function toggleStatus(EarthDayWorkshop $earthDay)
    {
        $earthDay->update(['is_active' => !$earthDay->is_active]);

        $status = $earthDay->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Workshop {$status} successfully!");
    }

    public function toggleActive()
    {
        EarthDaySetting::initialize();
        $settings = EarthDaySetting::getActive();
        $settings->update(['is_active' => !$settings->is_active]);

        $status = $settings->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Earth Day feature {$status} successfully!");
    }

    public function removeEnrollment(Request $request, EarthDayWorkshop $earthDay)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $enrollment = EarthDayEnrollment::where('user_id', $request->user_id)
            ->where('earth_day_workshop_id', $earthDay->id)
            ->first();

        if (!$enrollment) {
            return back()->with('error', 'User is not enrolled in this workshop.');
        }

        $enrollment->delete();
        $earthDay->decrement('current_enrollment');

        $user = User::find($request->user_id);

        return back()->with('success', "Removed {$user->name} from the workshop.");
    }

    public function export()
    {
        $workshops = EarthDayWorkshop::orderBy('title')->get();
        $workshopIds = $workshops->pluck('id')->all();

        $enrollments = EarthDayEnrollment::whereIn('earth_day_workshop_id', $workshopIds)
            ->with('user.division')
            ->orderBy('earth_day_workshop_id')
            ->orderBy('enrolled_at')
            ->get()
            ->groupBy('earth_day_workshop_id');

        $filename = 'earth-day-workshops-export-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($workshops, $enrollments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Workshop Title', 'Presenter', 'Location', 'Participant Name', 'Participant Email', 'Division', 'Enrolled At']);

            foreach ($workshops as $workshop) {
                $participants = $enrollments->get($workshop->id) ?? collect();

                if ($participants->isEmpty()) {
                    fputcsv($file, [$workshop->title, $workshop->presenter ?? '', $workshop->location ?? '', '', '', '', '']);
                } else {
                    foreach ($participants as $enrollment) {
                        $user = $enrollment->user;
                        fputcsv($file, [
                            $workshop->title,
                            $workshop->presenter ?? '',
                            $workshop->location ?? '',
                            $user?->name ?? '',
                            $user?->email ?? '',
                            $user?->division?->name ?? '',
                            $enrollment->enrolled_at?->format('M j, Y g:i A') ?? '',
                        ]);
                    }
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
