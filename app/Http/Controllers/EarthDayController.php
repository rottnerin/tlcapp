<?php

namespace App\Http\Controllers;

use App\Models\EarthDayEnrollment;
use App\Models\EarthDayWorkshop;
use App\Models\EarthDaySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EarthDayController extends Controller
{
    public function index()
    {
        EarthDaySetting::initialize();
        if (!EarthDaySetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        $workshops = EarthDayWorkshop::active()
            ->withCount('enrollments')
            ->with(['enrollments' => fn($q) => $q->where('user_id', $user->id)])
            ->orderBy('title')
            ->get();

        $userEnrollment = EarthDayEnrollment::where('user_id', $user->id)
            ->with('workshop')
            ->first();

        return view('earth-day.index', compact('workshops', 'userEnrollment'));
    }

    public function enroll(Request $request, EarthDayWorkshop $workshop)
    {
        EarthDaySetting::initialize();
        if (!EarthDaySetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        // Already enrolled somewhere — lock them in
        $existing = EarthDayEnrollment::where('user_id', $user->id)->first();
        if ($existing) {
            return back()->with('error', 'You have already chosen a workshop.');
        }

        try {
            DB::transaction(function () use ($user, $workshop) {
                $locked = EarthDayWorkshop::where('id', $workshop->id)->lockForUpdate()->first();

                if ($locked->isFull()) {
                    throw new \Exception('Sorry, this workshop is full.');
                }

                EarthDayEnrollment::create([
                    'user_id'               => $user->id,
                    'earth_day_workshop_id' => $locked->id,
                    'enrolled_at'           => now(),
                ]);

                $locked->increment('current_enrollment');
            });

            return back()->with('success', 'You\'re registered! See you at Earth Day.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
