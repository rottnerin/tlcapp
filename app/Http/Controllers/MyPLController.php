<?php

namespace App\Http\Controllers;

use App\Models\PDDay;
use App\Models\UserSelectedSession;
use App\Models\ScheduleItem;
use App\Models\WellnessSession;
use App\Models\PLWednesdaySession;
use App\Models\CCLSession;
use App\Models\EarthDayWorkshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPLController extends Controller
{
    /**
     * Get valid user selections for a given academic year, filtering out orphaned records
     *
     * @param \App\Models\User $user
     * @param string $academicYear
     * @return \Illuminate\Support\Collection
     */
    private function getValidSelections($user, string $academicYear)
    {
        // Get all selected sessions for this academic year
        $allSelections = $user->selectedSessions()
            ->where('academic_year', $academicYear)
            ->with('selectable')
            ->get();

        // Filter out orphaned records (where selectable no longer exists)
        // Note: Orphaned records are cleaned up by scheduled command 'selections:clean-orphaned'
        return $allSelections->filter(fn($selection) => $selection->selectable !== null);
    }

    /**
     * Display the user's personal PL schedule
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $academicYear = $request->get('year', PDDay::getCurrentAcademicYear());

        // Get valid selections (orphans are filtered out, cleaned by scheduled command)
        $selectedSessions = $this->getValidSelections($user, $academicYear);

        // Group sessions by type
        $groupedSessions = [
            'schedule' => [],
            'wellness' => [],
            'pl_wednesday' => [],
            'ccl' => [],
            'earth_day' => [],
        ];

        foreach ($selectedSessions as $selection) {
            $session = $selection->selectable;
            if (!$session) continue;
            
            $type = match(get_class($session)) {
                ScheduleItem::class => 'schedule',
                WellnessSession::class => 'wellness',
                PLWednesdaySession::class => 'pl_wednesday',
                CCLSession::class => 'ccl',
                EarthDayWorkshop::class => 'earth_day',
                default => null,
            };
            
            if ($type) {
                $groupedSessions[$type][] = $session;
            }
        }

        // Sort each group by date/time
        foreach ($groupedSessions as $type => $sessions) {
            usort($groupedSessions[$type], function($a, $b) {
                $dateA = $a->date ?? ($a->start_time ?? now());
                $dateB = $b->date ?? ($b->start_time ?? now());
                return $dateA <=> $dateB;
            });
        }

        // Get available academic years for filter
        $academicYears = UserSelectedSession::where('user_id', $user->id)
            ->select('academic_year')
            ->distinct()
            ->pluck('academic_year')
            ->filter()
            ->values();

        // Add current year if not present
        if (!$academicYears->contains($academicYear)) {
            $academicYears->push($academicYear);
        }

        return view('my-pl.index', compact(
            'groupedSessions', 
            'academicYear', 
            'academicYears',
            'selectedSessions'
        ));
    }

    /**
     * Toggle a session in/out of My PL
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'selectable_type' => 'required|string',
            'selectable_id' => 'required|integer',
        ]);

        $user = Auth::user();
        $type = $request->selectable_type;
        $id = $request->selectable_id;

        // Map type string to class
        $classMap = [
            'schedule_item' => ScheduleItem::class,
            'wellness_session' => WellnessSession::class,
            'pl_wednesday_session' => PLWednesdaySession::class,
            'ccl_session' => CCLSession::class,
            'earth_day_workshop' => EarthDayWorkshop::class,
        ];

        $class = $classMap[$type] ?? null;
        if (!$class) {
            return response()->json(['error' => 'Invalid session type'], 400);
        }

        $session = $class::find($id);
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        // Check if already selected
        $existing = $user->selectedSessions()
            ->where('selectable_type', $class)
            ->where('selectable_id', $id)
            ->first();

        if ($existing) {
            // Remove from My PL
            $existing->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Removed from My PL',
            ]);
        } else {
            // Add to My PL
            $user->addToMyPL($session);
            return response()->json([
                'status' => 'added',
                'message' => 'Added to My PL',
            ]);
        }
    }

    /**
     * Print view for My PL (Professional Learning Transcript)
     */
    public function print(Request $request)
    {
        $user = Auth::user();
        $academicYear = $request->get('year', PDDay::getCurrentAcademicYear());

        // Get valid selections (orphans are filtered out, cleaned by scheduled command)
        $selectedSessions = $this->getValidSelections($user, $academicYear);

        // Build transcript data
        $transcriptItems = [];

        foreach ($selectedSessions as $selection) {
            $session = $selection->selectable;
            if (!$session) continue;

            $item = [
                'date' => null,
                'title' => '',
                'contact_hours' => 0,
                'presenter' => '',
            ];

            if ($session instanceof ScheduleItem) {
                $item['date'] = $session->date;
                $item['title'] = $session->title;
                $item['presenter'] = $session->presenter_primary ?? '';
                // Calculate hours from start/end time
                if ($session->start_time && $session->end_time) {
                    $item['contact_hours'] = round($session->start_time->diffInMinutes($session->end_time) / 60, 2);
                }
            } elseif ($session instanceof WellnessSession) {
                $item['date'] = $session->date;
                $item['title'] = $session->title;
                $item['presenter'] = $session->presenter_name ?? '';
                // Wellness sessions are 1 hour
                $item['contact_hours'] = 1.0;
            } elseif ($session instanceof PLWednesdaySession) {
                $item['date'] = $session->date;
                $item['title'] = $session->title;
                $item['presenter'] = '';
                // Calculate from duration
                $item['contact_hours'] = round(($session->duration ?? 60) / 60, 2);
            } elseif ($session instanceof CCLSession) {
                $item['date'] = $session->date;
                $item['title'] = $session->title;
                $item['presenter'] = $session->presenter_name ?? '';
                $item['contact_hours'] = $session->contact_hours ?? $session->calculateContactHours();
            } elseif ($session instanceof EarthDayWorkshop) {
                $item['date'] = $session->date;
                $item['title'] = $session->title;
                $item['presenter'] = $session->presenter ?? '';
                $item['contact_hours'] = 0.75; // 45-minute session
            }

            $transcriptItems[] = $item;
        }

        // Sort by date
        usort($transcriptItems, function($a, $b) {
            return ($a['date'] ?? now()) <=> ($b['date'] ?? now());
        });

        // Calculate total hours
        $totalHours = array_sum(array_column($transcriptItems, 'contact_hours'));

        return view('my-pl.print', compact(
            'user',
            'academicYear',
            'transcriptItems',
            'totalHours'
        ));
    }
}
