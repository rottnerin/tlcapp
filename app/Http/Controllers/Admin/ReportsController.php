<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\ScheduleItem;
use App\Models\User;
use App\Models\UserSession;
use App\Models\WellnessSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Reports dashboard
     */
    public function index()
    {
        $totalUsers = User::count();
        $totalEnrollments = UserSession::where('status', 'confirmed')->count();

        // Include both Wellness and CCL sessions
        $activeWellnessSessions = WellnessSession::where('is_active', true)->count();
        $activeCCLSessions = ScheduleItem::where('session_type', 'ccl')->count();
        $activeSessions = $activeWellnessSessions + $activeCCLSessions;

        $divisions = Division::count();

        return view('admin.reports.index', compact(
            'totalUsers',
            'totalEnrollments',
            'activeSessions',
            'divisions'
        ));
    }

    /**
     * Wellness session enrollments report
     */
    public function wellnessEnrollments(Request $request)
    {
        $sessionId = $request->get('session_id');
        $status = $request->get('status', 'confirmed');
        $divisionId = $request->get('division_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = UserSession::with(['user.division', 'wellnessSession'])
            ->whereNotNull('wellness_session_id')
            ->whereHas('wellnessSession');

        if ($sessionId) {
            $query->where('wellness_session_id', $sessionId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($divisionId) {
            $query->whereHas('user', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            });
        }

        if ($dateFrom) {
            $query->whereDate('enrolled_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('enrolled_at', '<=', $dateTo);
        }

        $enrollments = $query->orderBy('enrolled_at', 'desc')->get();
        $wellnessSessions = WellnessSession::orderBy('title')->get();
        $divisions = Division::orderBy('name')->get();

        if ($request->has('export')) {
            $type = $request->get('export');
            if ($type === 'pdf') {
                return $this->exportWellnessEnrollmentsPDF($enrollments);
            }

            return $this->exportWellnessEnrollments($enrollments);
        }

        return view('admin.reports.wellness-enrollments', compact(
            'enrollments',
            'wellnessSessions',
            'divisions',
            'sessionId',
            'status',
            'divisionId',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * CCL session enrollments report
     */
    public function cclEnrollments(Request $request)
    {
        $sessionId = $request->get('session_id');
        $status = $request->get('status', 'confirmed');
        $divisionId = $request->get('division_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Query UserSessions linked to CCL schedule items
        $query = UserSession::with(['user.division', 'scheduleItem'])
            ->whereHas('scheduleItem', function ($q) {
                $q->where('session_type', 'ccl');
            });

        if ($sessionId) {
            $query->where('schedule_item_id', $sessionId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($divisionId) {
            $query->whereHas('user', function ($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            });
        }

        if ($dateFrom) {
            $query->whereDate('enrolled_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('enrolled_at', '<=', $dateTo);
        }

        $enrollments = $query->orderBy('enrolled_at', 'desc')->get();

        // Get CCL sessions for dropdown (ScheduleItems with session_type='ccl')
        $cclSessions = ScheduleItem::where('session_type', 'ccl')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $divisions = Division::orderBy('name')->get();

        if ($request->has('export')) {
            $type = $request->get('export');
            if ($type === 'pdf') {
                return $this->exportCCLEnrollmentsPDF($enrollments);
            }

            return $this->exportCCLEnrollments($enrollments);
        }

        return view('admin.reports.ccl-enrollments', compact(
            'enrollments',
            'cclSessions',
            'divisions',
            'sessionId',
            'status',
            'divisionId',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Users not enrolled in any sessions
     */
    public function unenrolledUsers(Request $request)
    {
        $divisionId = $request->get('division_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = User::with('division')
            ->whereDoesntHave('userSessions', function ($q) use ($dateFrom, $dateTo) {
                if ($dateFrom) {
                    $q->whereDate('enrolled_at', '>=', $dateFrom);
                }
                if ($dateTo) {
                    $q->whereDate('enrolled_at', '<=', $dateTo);
                }
            });

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        $unenrolledUsers = $query->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        if ($request->has('export')) {
            return $this->exportUnenrolledUsers($unenrolledUsers);
        }

        return view('admin.reports.unenrolled-users', compact(
            'unenrolledUsers',
            'divisions',
            'divisionId',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Session capacity utilization report
     */
    public function capacityUtilization(Request $request)
    {
        $category = $request->get('category');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = WellnessSession::withCount(['userSessions' => function ($q) {
            $q->where('status', 'confirmed');
        }]);

        if ($category) {
            $query->where('category', 'like', "%{$category}%");
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $sessions = $query->orderBy('date', 'desc')->get();

        $sessions = $sessions->map(function ($session) {
            $utilization = $session->max_participants > 0 ?
                round(($session->user_sessions_count / $session->max_participants) * 100, 2) : 0;

            return [
                'id' => $session->id,
                'title' => $session->title,
                'date' => $session->date,
                'category' => $session->category_names,
                'max_participants' => $session->max_participants,
                'enrolled' => $session->user_sessions_count,
                'utilization' => $utilization,
                'available_spots' => max(0, $session->max_participants - $session->user_sessions_count),
                'status' => $session->status,
            ];
        });

        $categories = WellnessSession::select('category')
            ->whereNotNull('category')
            ->get()
            ->pluck('category')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        if ($request->has('export')) {
            $type = $request->get('export');
            if ($type === 'pdf') {
                return $this->exportCapacityUtilizationPDF($sessions);
            }

            return $this->exportCapacityUtilization($sessions);
        }

        return view('admin.reports.capacity-utilization', compact(
            'sessions',
            'categories',
            'category',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Division enrollment summary
     */
    public function divisionSummary(Request $request)
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());

        $divisions = Division::withCount(['users'])->get();

        $divisionData = $divisions->map(function ($division) use ($dateFrom, $dateTo) {
            $enrollments = UserSession::whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division->id);
            })
                ->whereBetween('enrolled_at', [$dateFrom, $dateTo])
                ->where('status', 'confirmed')
                ->count();

            $wellnessEnrollments = UserSession::whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division->id);
            })
                ->whereNotNull('wellness_session_id')
                ->whereBetween('enrolled_at', [$dateFrom, $dateTo])
                ->where('status', 'confirmed')
                ->count();

            $scheduleEnrollments = UserSession::whereHas('user', function ($q) use ($division) {
                $q->where('division_id', $division->id);
            })
                ->whereNotNull('schedule_item_id')
                ->whereBetween('enrolled_at', [$dateFrom, $dateTo])
                ->where('status', 'confirmed')
                ->count();

            $participationRate = $division->users_count > 0 ?
                round(($enrollments / $division->users_count) * 100, 2) : 0;

            return [
                'id' => $division->id,
                'name' => $division->name,
                'total_users' => $division->users_count,
                'total_enrollments' => $enrollments,
                'wellness_enrollments' => $wellnessEnrollments,
                'schedule_enrollments' => $scheduleEnrollments,
                'participation_rate' => $participationRate,
            ];
        });

        if ($request->has('export')) {
            $type = $request->get('export');
            if ($type === 'pdf') {
                return $this->exportDivisionSummaryPDF($divisionData, $dateFrom, $dateTo);
            }

            return $this->exportDivisionSummary($divisionData);
        }

        return view('admin.reports.division-summary', compact(
            'divisionData',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * User activity report
     */
    public function userActivity(Request $request)
    {
        $divisionId = $request->get('division_id');
        $dateFrom = $request->get('date_from', Carbon::now()->subDays(30));
        $dateTo = $request->get('date_to', Carbon::now());

        $query = User::with(['division', 'userSessions.wellnessSession', 'userSessions.scheduleItem']);

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        $users = $query->get()->map(function ($user) use ($dateFrom, $dateTo) {
            $enrollments = $user->userSessions()
                ->whereBetween('enrolled_at', [$dateFrom, $dateTo])
                ->where('status', 'confirmed')
                ->get();

            $wellnessCount = $enrollments->where('wellness_session_id', '!=', null)->count();
            $scheduleCount = $enrollments->where('schedule_item_id', '!=', null)->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'division' => $user->division_name,
                'total_enrollments' => $enrollments->count(),
                'wellness_enrollments' => $wellnessCount,
                'schedule_enrollments' => $scheduleCount,
                'last_enrollment' => $enrollments->max('enrolled_at'),
                'last_login' => $user->last_login_at,
            ];
        });

        $divisions = Division::orderBy('name')->get();

        if ($request->has('export')) {
            return $this->exportUserActivity($users);
        }

        return view('admin.reports.user-activity', compact(
            'users',
            'divisions',
            'divisionId',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Session participant lists report
     */
    public function sessionParticipantLists(Request $request)
    {
        $sessionType = $request->get('session_type', 'all');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $status = $request->get('status', 'confirmed');

        $sessionData = [];

        // Wellness Sessions
        if ($sessionType === 'all' || $sessionType === 'wellness') {
            $wellnessSessions = WellnessSession::with(['userSessions' => function ($q) use ($status) {
                $q->with(['user.division']);
                if ($status) {
                    $q->where('status', $status);
                }
            }])
                ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
                ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
                ->where('is_active', true)
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            foreach ($wellnessSessions as $session) {
                $sessionData[] = [
                    'type' => 'Wellness',
                    'title' => $session->title,
                    'date' => $session->date,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'location' => $session->location,
                    'presenter' => $session->presenter_name,
                    'capacity' => $session->max_participants,
                    'enrolled' => $session->userSessions->count(),
                    'participants' => $session->userSessions,
                ];
            }
        }

        // CCL Sessions
        if ($sessionType === 'all' || $sessionType === 'ccl') {
            $cclScheduleItems = ScheduleItem::where('session_type', 'ccl')
                ->with(['userSessions' => function ($q) use ($status) {
                    $q->with(['user.division']);
                    if ($status) {
                        $q->where('status', $status);
                    }
                }])
                ->when($dateFrom, fn ($q) => $q->whereDate('date', '>=', $dateFrom))
                ->when($dateTo, fn ($q) => $q->whereDate('date', '<=', $dateTo))
                ->orderBy('date')
                ->orderBy('start_time')
                ->get();

            foreach ($cclScheduleItems as $item) {
                $sessionData[] = [
                    'type' => 'CCL',
                    'title' => $item->title,
                    'date' => $item->date,
                    'start_time' => $item->start_time,
                    'end_time' => $item->end_time,
                    'location' => $item->location,
                    'presenter' => $item->presenter_primary,
                    'capacity' => $item->max_participants,
                    'enrolled' => $item->userSessions->count(),
                    'participants' => $item->userSessions,
                ];
            }
        }

        // Sort by date and time
        usort($sessionData, function ($a, $b) {
            $dateCompare = $a['date'] <=> $b['date'];
            if ($dateCompare !== 0) {
                return $dateCompare;
            }

            return $a['start_time'] <=> $b['start_time'];
        });

        if ($request->has('export')) {
            return $this->exportSessionParticipantListsPDF($sessionData);
        }

        return view('admin.reports.session-participant-lists', compact(
            'sessionData',
            'sessionType',
            'dateFrom',
            'dateTo',
            'status'
        ));
    }

    // CSV Export Methods

    private function exportWellnessEnrollments($enrollments)
    {
        $filename = 'wellness_enrollments_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($enrollments) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'User Name',
                'Email',
                'Division',
                'Session Title',
                'Session Date',
                'Category',
                'Status',
                'Enrolled At',
                'Rating',
                'Attended',
            ]);

            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->user->name,
                    $enrollment->user->email,
                    $enrollment->user->division_name ?? 'N/A',
                    $enrollment->wellnessSession->title ?? 'N/A',
                    $enrollment->wellnessSession->date ?? 'N/A',
                    $enrollment->wellnessSession->category_names ?? 'N/A',
                    $enrollment->status,
                    $enrollment->enrolled_at->format('Y-m-d H:i:s'),
                    $enrollment->rating ?? 'N/A',
                    $enrollment->attended ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUnenrolledUsers($users)
    {
        $filename = 'unenrolled_users_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Name',
                'Email',
                'Division',
                'ID Card Code',
                'Last Login',
                'Account Created',
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->division_name ?? 'N/A',
                    $user->id_card_code ?? 'N/A',
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : 'Never',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCapacityUtilization($sessions)
    {
        $filename = 'capacity_utilization_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($sessions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Session Title',
                'Date',
                'Category',
                'Max Participants',
                'Enrolled',
                'Utilization %',
                'Available Spots',
                'Status',
            ]);

            foreach ($sessions as $session) {
                fputcsv($file, [
                    $session['title'],
                    $session['date']->format('Y-m-d'),
                    $session['category'],
                    $session['max_participants'],
                    $session['enrolled'],
                    $session['utilization'].'%',
                    $session['available_spots'],
                    $session['status'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportDivisionSummary($divisionData)
    {
        $filename = 'division_summary_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($divisionData) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Division',
                'Total Users',
                'Total Enrollments',
                'Wellness Enrollments',
                'Schedule Enrollments',
                'Participation Rate %',
            ]);

            foreach ($divisionData as $division) {
                fputcsv($file, [
                    $division['name'],
                    $division['total_users'],
                    $division['total_enrollments'],
                    $division['wellness_enrollments'],
                    $division['schedule_enrollments'],
                    $division['participation_rate'].'%',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUserActivity($users)
    {
        $filename = 'user_activity_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Name',
                'Email',
                'Division',
                'Total Enrollments',
                'Wellness Enrollments',
                'Schedule Enrollments',
                'Last Enrollment',
                'Last Login',
            ]);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user['name'],
                    $user['email'],
                    $user['division'],
                    $user['total_enrollments'],
                    $user['wellness_enrollments'],
                    $user['schedule_enrollments'],
                    $user['last_enrollment'] ? $user['last_enrollment']->format('Y-m-d H:i:s') : 'Never',
                    $user['last_login'] ? $user['last_login']->format('Y-m-d H:i:s') : 'Never',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportCCLEnrollments($enrollments)
    {
        $filename = 'ccl_enrollments_'.date('Y-m-d_H-i-s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        return response()->stream(function () use ($enrollments) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, [
                'User Name',
                'Email',
                'Division',
                'Session Title',
                'Date',
                'Start Time',
                'End Time',
                'Location',
                'Presenter',
                'Status',
                'Enrolled At',
                'Rating',
                'Attended',
            ]);

            // Data rows
            foreach ($enrollments as $enrollment) {
                fputcsv($handle, [
                    $enrollment->user->name,
                    $enrollment->user->email,
                    $enrollment->user->division->name ?? 'N/A',
                    $enrollment->scheduleItem->title ?? 'N/A',
                    $enrollment->scheduleItem->date?->format('Y-m-d') ?? 'N/A',
                    $enrollment->scheduleItem->start_time?->format('g:i A') ?? 'N/A',
                    $enrollment->scheduleItem->end_time?->format('g:i A') ?? 'N/A',
                    $enrollment->scheduleItem->location ?? 'N/A',
                    $enrollment->scheduleItem->presenter_primary ?? 'N/A',
                    $enrollment->status,
                    $enrollment->enrolled_at->format('Y-m-d H:i:s'),
                    $enrollment->rating ?? 'N/A',
                    $enrollment->attended ? 'Yes' : 'No',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    // PDF Export Methods

    private function exportWellnessEnrollmentsPDF($enrollments)
    {
        $filename = 'wellness_enrollments_'.date('Y-m-d_H-i-s').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf.wellness-enrollments', [
            'enrollments' => $enrollments,
            'generatedAt' => now(),
        ]);

        $pdf->setPaper('letter', 'landscape');

        return $pdf->download($filename);
    }

    private function exportCCLEnrollmentsPDF($enrollments)
    {
        $filename = 'ccl_enrollments_'.date('Y-m-d_H-i-s').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf.ccl-enrollments', [
            'enrollments' => $enrollments,
            'generatedAt' => now(),
        ]);

        $pdf->setPaper('letter', 'landscape');

        return $pdf->download($filename);
    }

    private function exportCapacityUtilizationPDF($sessions)
    {
        $filename = 'capacity_utilization_'.date('Y-m-d_H-i-s').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf.capacity-utilization', [
            'sessions' => $sessions,
            'generatedAt' => now(),
        ]);

        return $pdf->download($filename);
    }

    private function exportDivisionSummaryPDF($divisionData, $dateFrom, $dateTo)
    {
        $filename = 'division_summary_'.date('Y-m-d_H-i-s').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf.division-summary', [
            'divisionData' => $divisionData,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'generatedAt' => now(),
        ]);

        return $pdf->download($filename);
    }

    private function exportSessionParticipantListsPDF($sessionData)
    {
        $filename = 'session_participant_lists_'.date('Y-m-d_H-i-s').'.pdf';

        $pdf = Pdf::loadView('admin.reports.pdf.session-participant-lists', [
            'sessionData' => $sessionData,
            'generatedAt' => now(),
        ]);

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download($filename);
    }
}
