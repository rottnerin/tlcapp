<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\PDDay;
use App\Models\PLDaysSetting;
use App\Models\PLWednesdaySetting;
use App\Models\ScheduleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedule items
     */
    public function index(Request $request)
    {
        // Initialize settings
        PLDaysSetting::initialize();
        PLWednesdaySetting::initialize();

        // Check if PL Days feature is enabled
        if (! PLDaysSetting::isActive()) {
            // Redirect to PL Wednesday as fallback
            return redirect()->route('pl-wednesday.index');
        }

        $user = auth()->user();
        $divisions = Division::active()->get();

        // Get active PD Day
        $activePDDay = PDDay::getActive();

        // Check if there are any active regular schedule items (exclude CCL & wellness)
        $hasActivePLDaysSessions = false;
        if ($activePDDay) {
            $hasActivePLDaysSessions = ScheduleItem::active()
                ->scheduleOnly()
                ->where('p_d_day_id', $activePDDay->id)
                ->exists();
        }

        // If no active PD Day or no active sessions, redirect to PL Wednesday
        if (! $activePDDay || ! $hasActivePLDaysSessions) {
            return redirect()->route('pl-wednesday.index');
        }

        // Generate date range from PD Day if available
        $eventDates = [];
        if ($activePDDay) {
            $start = Carbon::parse($activePDDay->start_date);
            $end = Carbon::parse($activePDDay->end_date);

            while ($start->lte($end)) {
                $eventDates[] = $start->copy();
                $start->addDay();
            }
        }

        // Get active tab (default to Day 1)
        $activeTab = $request->get('day', 'day1');
        $dayIndex = (int) str_replace('day', '', $activeTab) - 1;
        $selectedDate = $eventDates[$dayIndex] ?? null;

        // Get division filter preference
        // Default to user's own division if no filter is explicitly selected
        if (!$request->has('divisions')) {
            // First page load - default to user's division if available
            $selectedDivisions = $user->division_id ? [$user->division_id] : [];
        } else {
            // User has interacted with filters - respect their choice
            $rawDivisions = $request->get('divisions', []);
            if (is_array($rawDivisions)) {
                $selectedDivisions = array_values(array_filter($rawDivisions));
            } else {
                $selectedDivisions = $rawDivisions !== '' && $rawDivisions !== null ? [$rawDivisions] : [];
            }
        }

        // Get schedule items for the selected date (exclude CCL & wellness - they have their own tabs)
        $scheduleItems = collect();
        if ($selectedDate && $activePDDay) {
            $allSchoolDivisionId = Division::where('name', 'ALL')->value('id');
            $scheduleItems = ScheduleItem::active()
                ->scheduleOnly()
                ->where('p_d_day_id', $activePDDay->id)
                ->whereDate('date', $selectedDate)
                ->when(!empty($selectedDivisions), function ($query) use ($selectedDivisions, $allSchoolDivisionId) {
                    $onlyAllSchoolSelected = $allSchoolDivisionId
                        && count($selectedDivisions) === 1
                        && in_array((int) $allSchoolDivisionId, array_map('intval', $selectedDivisions));
                    if ($onlyAllSchoolSelected) {
                        return $query->whereHas('divisions', fn ($subQ) => $subQ->where('divisions.id', $allSchoolDivisionId));
                    }
                    // Only show items that belong to the selected division(s) — no ALL, no items with no divisions
                    return $query->whereHas('divisions', function ($subQ) use ($selectedDivisions) {
                        $subQ->whereIn('divisions.id', $selectedDivisions);
                    });
                })
                ->with(['divisions'])
                ->orderBy('start_time')
                ->get();
        }

        // Handle CCL session replacement (replace placeholder with user's enrolled CCL)
        $userCCLSession = null;
        if ($selectedDate) {
            $userCCLEnrollments = \App\Models\UserSession::where('user_id', $user->id)
                ->whereHas('scheduleItem', function ($query) {
                    $query->where('session_type', 'ccl');
                })
                ->where('status', '!=', 'cancelled')
                ->with('scheduleItem')
                ->get();

            $enrollmentForDate = $userCCLEnrollments->first(function ($enrollment) use ($selectedDate) {
                return $enrollment->scheduleItem
                    && $enrollment->scheduleItem->date
                    && $enrollment->scheduleItem->date->format('Y-m-d') === $selectedDate->format('Y-m-d');
            });

            if ($enrollmentForDate && $enrollmentForDate->scheduleItem) {
                $userCCLSession = $enrollmentForDate->scheduleItem;

                $scheduleItems = $scheduleItems->filter(function ($item) {
                    return ! str_contains(strtolower($item->title), 'collaborative community learning');
                });

                $userCCLSession->load('divisions');

                $cclScheduleItem = new ScheduleItem([
                    'title' => $userCCLSession->title,
                    'description' => $userCCLSession->description,
                    'location' => $userCCLSession->location,
                    'start_time' => $userCCLSession->start_time,
                    'end_time' => $userCCLSession->end_time,
                    'date' => $userCCLSession->date,
                    'presenter_primary' => $userCCLSession->presenter_primary,
                    'is_active' => true,
                    'session_type' => 'ccl',
                ]);

                $scheduleItems->push($cclScheduleItem);

                $scheduleItems = $scheduleItems->sortBy(function ($item) {
                    return $item->start_time ? $item->start_time->timestamp : 0;
                })->values();
            }
        }

        // Handle wellness session replacement
        $userWellnessSession = null;
        $userWellnessEnrollment = \App\Models\UserSession::where('user_id', $user->id)
            ->whereNotNull('wellness_session_id')
            ->where('status', '!=', 'cancelled')
            ->with('wellnessSession')
            ->first();

        if ($userWellnessEnrollment && $userWellnessEnrollment->wellnessSession) {
            $userWellnessSession = $userWellnessEnrollment->wellnessSession;

            $wellnessDate = Carbon::parse($userWellnessSession->date)->format('Y-m-d');
            $currentDate = $selectedDate ? $selectedDate->format('Y-m-d') : null;

            if ($currentDate && $wellnessDate === $currentDate) {
                // Remove the wellness placeholder (handles both old and new title)
                $scheduleItems = $scheduleItems->filter(function ($item) {
                    $title = strtolower($item->title);
                    return ! str_contains($title, 'belonging and well-being')
                        && ! str_contains($title, 'community culture and wellbeing');
                });

                $wellnessStartTime = Carbon::parse($userWellnessSession->start_time);
                $wellnessEndTime = Carbon::parse($userWellnessSession->end_time);

                $wellnessScheduleItem = new ScheduleItem([
                    'title' => $userWellnessSession->title,
                    'description' => $userWellnessSession->description,
                    'location' => $userWellnessSession->location,
                    'start_time' => $wellnessStartTime,
                    'end_time' => $wellnessEndTime,
                    'date' => $userWellnessSession->date,
                    'presenter_primary' => $userWellnessSession->presenter_name,
                    'presenter_bio' => $userWellnessSession->presenter_bio,
                    'presenter_email' => $userWellnessSession->presenter_email,
                    'max_participants' => $userWellnessSession->max_participants,
                    'current_enrollment' => $userWellnessSession->current_enrollment,
                    'equipment_needed' => $userWellnessSession->equipment_needed,
                    'special_requirements' => $userWellnessSession->special_requirements,
                    'is_active' => true,
                    'session_type' => 'wellness',
                ]);

                $scheduleItems->push($wellnessScheduleItem);

                $scheduleItems = $scheduleItems->sortBy(function ($item) {
                    return $item->start_time ? $item->start_time->timestamp : 0;
                })->values();
            }
        }

        return view('schedule.index', compact(
            'user',
            'divisions',
            'selectedDivisions',
            'scheduleItems',
            'userWellnessSession',
            'userCCLSession',
            'eventDates',
            'activeTab',
            'selectedDate'
        ));
    }

    /**
     * Display the specified schedule item
     */
    public function show(ScheduleItem $scheduleItem)
    {
        // Check if PL Days feature is enabled
        PLDaysSetting::initialize();
        if (! PLDaysSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        // Load relationships
        $scheduleItem->load(['divisions']);

        return view('schedule.show', compact('scheduleItem', 'user'));
    }

    /**
     * Display Fall PL Day schedule
     */
    public function fallIndex(Request $request, ?PDDay $pdday = null)
    {
        return $this->seasonIndex($request, 'fall', $pdday);
    }

    /**
     * Display Spring PL Days schedule
     */
    public function springIndex(Request $request, ?PDDay $pdday = null)
    {
        return $this->seasonIndex($request, 'spring', $pdday);
    }

    /**
     * Print schedule view for Fall or Spring
     */
    public function printSchedule(Request $request)
    {
        PLDaysSetting::initialize();
        if (!PLDaysSetting::isActive()) {
            abort(404);
        }

        $user = auth()->user();

        // Determine season from route name
        $season = str_contains($request->route()->getName(), 'spring') ? 'spring' : 'fall';

        // Get PD Day (active or archived if pdday param provided)
        $pdDayId = $request->get('pdday');
        if ($pdDayId) {
            $activePDDay = PDDay::find($pdDayId);
        } else {
            $activePDDay = PDDay::where('is_active', true)
                ->where('season', $season)
                ->first();
        }

        if (!$activePDDay) {
            abort(404, 'No active PD Day found for ' . ucfirst($season));
        }

        // Generate date range for the PD Day
        $eventDates = [];
        $start = Carbon::parse($activePDDay->start_date);
        $end = Carbon::parse($activePDDay->end_date);
        while ($start->lte($end)) {
            $eventDates[] = $start->copy();
            $start->addDay();
        }

        // Get selected day (from day tab)
        $activeTab = $request->get('day', 'day1');
        $dayIndex = (int) str_replace('day', '', $activeTab) - 1;
        $selectedDate = $eventDates[$dayIndex] ?? $eventDates[0] ?? null;

        // Get division filter
        $selectedDivisions = $request->get('divisions', []);

        // Load schedule items with division filtering (exclude CCL & wellness)
        $scheduleItems = collect();
        if ($selectedDate && $activePDDay) {
            $allSchoolDivisionId = Division::where('name', 'ALL')->value('id');
            $scheduleItems = ScheduleItem::active()
                ->scheduleOnly()
                ->where('p_d_day_id', $activePDDay->id)
                ->whereDate('date', $selectedDate)
                ->when(!empty($selectedDivisions), function ($query) use ($selectedDivisions, $allSchoolDivisionId) {
                    $onlyAllSchoolSelected = $allSchoolDivisionId
                        && count($selectedDivisions) === 1
                        && in_array((int) $allSchoolDivisionId, array_map('intval', (array) $selectedDivisions));
                    if ($onlyAllSchoolSelected) {
                        return $query->whereHas('divisions', fn ($subQ) => $subQ->where('divisions.id', $allSchoolDivisionId));
                    }
                    // Only show items that belong to the selected division(s) — no ALL, no items with no divisions
                    return $query->whereHas('divisions', function ($subQ) use ($selectedDivisions) {
                        $subQ->whereIn('divisions.id', (array) $selectedDivisions);
                    });
                })
                ->with(['divisions'])
                ->orderBy('start_time')
                ->get();
        }

        // Get active divisions for display
        $divisions = Division::active()->get();

        return view('schedule.print', compact(
            'user',
            'divisions',
            'selectedDivisions',
            'scheduleItems',
            'selectedDate',
            'activePDDay',
            'season',
            'activeTab'
        ));
    }

    /**
     * Display schedule for a specific season
     */
    protected function seasonIndex(Request $request, string $season, ?PDDay $pdday = null)
    {
        PLDaysSetting::initialize();
        PLWednesdaySetting::initialize();

        if (! PLDaysSetting::isActive()) {
            return redirect()->route('pl-wednesday.index');
        }

        $user = auth()->user();
        $divisions = Division::active()->get();
        $isArchiveView = false;

        // If specific archived PD Day requested
        if ($pdday && $pdday->exists && $pdday->isArchived() && $pdday->season === $season) {
            $activePDDay = $pdday;
            $isArchiveView = true;
        } else {
            // Get active PD Day for this season
            $activePDDay = PDDay::where('is_active', true)
                ->where('season', $season)
                ->first();

            // Fallback to any active PD day if season-specific not found
            if (! $activePDDay) {
                $activePDDay = PDDay::getActive();
            }
        }

        // Get the current active PD Day for this season (for tabs)
        $activePDDayForSeason = PDDay::where('is_active', true)
            ->where('season', $season)
            ->first();

        // Get archived PL Days for this season (for year tabs)
        $archivedPDDays = PDDay::getArchivedBySeason($season);

        $hasActivePLDaysSessions = false;
        if ($activePDDay) {
            $hasActivePLDaysSessions = ScheduleItem::active()
                ->scheduleOnly()
                ->where('p_d_day_id', $activePDDay->id)
                ->exists();
        }

        // Generate date range
        $eventDates = [];
        if ($activePDDay) {
            $start = Carbon::parse($activePDDay->start_date);
            $end = Carbon::parse($activePDDay->end_date);

            while ($start->lte($end)) {
                $eventDates[] = $start->copy();
                $start->addDay();
            }
        }

        $activeTab = $request->get('day', 'day1');
        $dayIndex = (int) str_replace('day', '', $activeTab) - 1;
        $selectedDate = $eventDates[$dayIndex] ?? null;

        // Default to user's division on first load, otherwise respect explicit filter
        if (!$request->has('divisions')) {
            // First page load - default to user's division if available
            $selectedDivisions = $user->division_id ? [$user->division_id] : [];
        } else {
            // User has interacted with filters - respect their choice
            $rawDivisions = $request->get('divisions', []);
            if (is_array($rawDivisions)) {
                $selectedDivisions = array_values(array_filter($rawDivisions));
            } else {
                $selectedDivisions = $rawDivisions !== '' && $rawDivisions !== null ? [$rawDivisions] : [];
            }
        }

        // Get schedule items (exclude CCL & wellness - they have their own tabs)
        $scheduleItems = collect();
        if ($selectedDate && $activePDDay) {
            $allSchoolDivisionId = Division::where('name', 'ALL')->value('id');
            $scheduleItems = ScheduleItem::active()
                ->scheduleOnly()
                ->where('p_d_day_id', $activePDDay->id)
                ->whereDate('date', $selectedDate)
                ->when(!empty($selectedDivisions), function ($query) use ($selectedDivisions, $allSchoolDivisionId) {
                    $onlyAllSchoolSelected = $allSchoolDivisionId
                        && count($selectedDivisions) === 1
                        && in_array((int) $allSchoolDivisionId, array_map('intval', $selectedDivisions));
                    if ($onlyAllSchoolSelected) {
                        return $query->whereHas('divisions', fn ($subQ) => $subQ->where('divisions.id', $allSchoolDivisionId));
                    }
                    // Only show items that belong to the selected division(s) — no ALL, no items with no divisions
                    return $query->whereHas('divisions', function ($subQ) use ($selectedDivisions) {
                        $subQ->whereIn('divisions.id', $selectedDivisions);
                    });
                })
                ->with(['divisions'])
                ->orderBy('start_time')
                ->get();
        }

        // Handle CCL session replacement (replace placeholder with user's enrolled CCL)
        $userCCLSession = null;
        if (! ($isArchiveView ?? false) && $selectedDate) {
            $userCCLEnrollments = \App\Models\UserSession::where('user_id', $user->id)
                ->whereHas('scheduleItem', function ($query) {
                    $query->where('session_type', 'ccl');
                })
                ->where('status', '!=', 'cancelled')
                ->with('scheduleItem')
                ->get();

            // Find the enrollment for the currently selected date
            $enrollmentForDate = $userCCLEnrollments->first(function ($enrollment) use ($selectedDate) {
                return $enrollment->scheduleItem
                    && $enrollment->scheduleItem->date
                    && $enrollment->scheduleItem->date->format('Y-m-d') === $selectedDate->format('Y-m-d');
            });

            if ($enrollmentForDate && $enrollmentForDate->scheduleItem) {
                $userCCLSession = $enrollmentForDate->scheduleItem;

                // Remove the "Collaborative Community Learning" placeholder
                $scheduleItems = $scheduleItems->filter(function ($item) {
                    return ! str_contains(strtolower($item->title), 'collaborative community learning');
                });

                // Load the user's CCL ScheduleItem with divisions and add it
                $userCCLSession->load('divisions');

                // Mark session_type so the view can style it distinctly
                $cclScheduleItem = new ScheduleItem([
                    'title' => $userCCLSession->title,
                    'description' => $userCCLSession->description,
                    'location' => $userCCLSession->location,
                    'start_time' => $userCCLSession->start_time,
                    'end_time' => $userCCLSession->end_time,
                    'date' => $userCCLSession->date,
                    'presenter_primary' => $userCCLSession->presenter_primary,
                    'is_active' => true,
                    'session_type' => 'ccl',
                ]);

                $scheduleItems->push($cclScheduleItem);

                // Re-sort by start_time
                $scheduleItems = $scheduleItems->sortBy(function ($item) {
                    return $item->start_time ? $item->start_time->timestamp : 0;
                })->values();
            }
        }

        // Handle wellness session replacement (only for non-archive view)
        $userWellnessSession = null;
        if (! ($isArchiveView ?? false)) {
            $userWellnessEnrollment = \App\Models\UserSession::where('user_id', $user->id)
                ->whereNotNull('wellness_session_id')
                ->where('status', '!=', 'cancelled')
                ->with('wellnessSession')
                ->first();

            if ($userWellnessEnrollment && $userWellnessEnrollment->wellnessSession) {
                $userWellnessSession = $userWellnessEnrollment->wellnessSession;

                // Check if the wellness session is on the currently selected date
                $wellnessDate = Carbon::parse($userWellnessSession->date)->format('Y-m-d');
                $currentDate = $selectedDate ? $selectedDate->format('Y-m-d') : null;

                if ($currentDate && $wellnessDate === $currentDate) {
                    // Remove the wellness placeholder (handles both old and new title)
                    $scheduleItems = $scheduleItems->filter(function ($item) {
                        $title = strtolower($item->title);
                        return ! str_contains($title, 'belonging and well-being')
                            && ! str_contains($title, 'community culture and wellbeing');
                    });

                    // Create wellness schedule item with proper Carbon datetime instances
                    $wellnessStartTime = Carbon::parse($userWellnessSession->start_time);
                    $wellnessEndTime = Carbon::parse($userWellnessSession->end_time);

                    $wellnessScheduleItem = new ScheduleItem([
                        'title' => $userWellnessSession->title,
                        'description' => $userWellnessSession->description,
                        'location' => $userWellnessSession->location,
                        'start_time' => $wellnessStartTime,
                        'end_time' => $wellnessEndTime,
                        'date' => $userWellnessSession->date,
                        'presenter_primary' => $userWellnessSession->presenter_name,
                        'presenter_bio' => $userWellnessSession->presenter_bio,
                        'presenter_email' => $userWellnessSession->presenter_email,
                        'max_participants' => $userWellnessSession->max_participants,
                        'current_enrollment' => $userWellnessSession->current_enrollment,
                        'equipment_needed' => $userWellnessSession->equipment_needed,
                        'special_requirements' => $userWellnessSession->special_requirements,
                        'is_active' => true,
                        'session_type' => 'wellness',
                    ]);

                    // Add wellness session to the collection
                    $scheduleItems->push($wellnessScheduleItem);

                    // Sort by start_time chronologically (ensuring proper Carbon comparison)
                    $scheduleItems = $scheduleItems->sortBy(function ($item) {
                        return $item->start_time ? $item->start_time->timestamp : 0;
                    })->values();
                }
            }
        }

        return view('schedule.index', compact(
            'user',
            'divisions',
            'selectedDivisions',
            'scheduleItems',
            'userWellnessSession',
            'userCCLSession',
            'eventDates',
            'activeTab',
            'selectedDate',
            'activePDDay',
            'activePDDayForSeason',
            'archivedPDDays',
            'isArchiveView'
        ))->with('season', $season);
    }

    /**
     * Display archived PL content by academic year
     */
    public function archive(Request $request)
    {
        $user = auth()->user();
        $academicYear = $request->get('year', PDDay::getCurrentAcademicYear());

        // Get all PD days for the selected academic year
        $pdDays = PDDay::where('academic_year', $academicYear)
            ->orderBy('start_date')
            ->get();

        // Get available academic years
        $academicYears = PDDay::select('academic_year')
            ->distinct()
            ->whereNotNull('academic_year')
            ->pluck('academic_year')
            ->filter()
            ->values();

        return view('archive.index', compact('user', 'pdDays', 'academicYear', 'academicYears'));
    }
}
