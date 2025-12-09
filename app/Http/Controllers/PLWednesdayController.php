<?php

namespace App\Http\Controllers;

use App\Models\PLWednesdaySession;
use App\Models\PLWednesdaySetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PLWednesdayController extends Controller
{
    /**
     * Display a listing of PL Wednesday sessions.
     */
    public function index()
    {
        $settings = PLWednesdaySetting::getActive();

        if (!$settings || !$settings->is_active) {
            return view('pl-wednesday.index', ['groupedSessions' => collect(), 'settings' => $settings]);
        }

        $sessions = PLWednesdaySession::active()
            ->whereBetween('date', [$settings->start_date, $settings->end_date])
            ->with('links')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();

        // Group sessions by date and sort by date descending (most recent first)
        $groupedSessions = $sessions->groupBy(function ($session) {
            return $session->date->format('Y-m-d');
        })->sortKeysDesc();

        return view('pl-wednesday.index', compact('groupedSessions', 'settings'));
    }

    /**
     * Display the specified PL Wednesday session.
     */
    public function show(PLWednesdaySession $session)
    {
        if (!$session->isVisible()) {
            abort(404);
        }
        
        $session->load('links');
        return view('pl-wednesday.show', compact('session'));
    }
}
