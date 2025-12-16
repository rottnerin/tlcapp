<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ScheduleItem;
use App\Models\WellnessSession;
use App\Models\Division;
use App\Models\UserSession;
use App\Models\WellnessSetting;
use App\Models\PLDaysSetting;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_schedule_items' => ScheduleItem::count(),
            'total_wellness_sessions' => WellnessSession::count(),
            'total_enrollments' => UserSession::where('status', 'confirmed')->count(),
        ];

        // Recent registrations (last 7 days)
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))
            ->with('division')
            ->latest()
            ->take(10)
            ->get();

        // Popular wellness sessions
        $popularSessions = WellnessSession::withCount(['userSessions' => function($query) {
            $query->where('status', 'confirmed');
        }])
        ->orderBy('user_sessions_count', 'desc')
        ->take(5)
        ->get();

        // Division breakdown
        $divisionStats = Division::withCount('users')->get();

        // Initialize and get feature settings
        WellnessSetting::initialize();
        PLDaysSetting::initialize();
        $wellnessSetting = WellnessSetting::getActive();
        $plDaysSetting = PLDaysSetting::getActive();

        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'popularSessions', 
            'divisionStats',
            'wellnessSetting',
            'plDaysSetting'
        ));
    }

    /**
     * User management
     */
    public function users(Request $request)
    {
        $query = User::with('division');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Division filter
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        // Admin filter
        if ($request->filled('admin_only')) {
            $query->where('is_admin', true);
        }

        $users = $query->latest()->paginate(20);
        $divisions = Division::all();

        return view('admin.users.index', compact('users', 'divisions'));
    }

    /**
     * Show specific user
     */
    public function showUser(User $user)
    {
        $user->load(['division', 'userSessions.wellnessSession', 'userSessions.scheduleItem']);
        
        $enrollmentHistory = $user->userSessions()
            ->with(['wellnessSession', 'scheduleItem'])
            ->latest()
            ->get();

        return view('admin.users.show', compact('user', 'enrollmentHistory'));
    }

    /**
     * Toggle user admin status
     */
    public function toggleAdmin(User $user)
    {
        $user->update(['is_admin' => !$user->is_admin]);
        
        $message = $user->is_admin ? 'User granted admin privileges' : 'Admin privileges revoked';
        
        return back()->with('success', $message);
    }

    /**
     * Update user password (for admin login)
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully for ' . $user->name);
    }

    /**
     * Enrollment reports
     */
    public function enrollmentReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        // Wellness session enrollments
        $wellnessEnrollments = UserSession::whereNotNull('wellness_session_id')
            ->whereBetween('enrolled_at', [$startDate, $endDate])
            ->with(['user', 'wellnessSession'])
            ->get()
            ->groupBy('wellness_session_id');

        // Schedule item enrollments  
        $scheduleEnrollments = UserSession::whereNotNull('schedule_item_id')
            ->whereBetween('enrolled_at', [$startDate, $endDate])
            ->with(['user', 'scheduleItem'])
            ->get()
            ->groupBy('schedule_item_id');

        // Division breakdown
        $divisionBreakdown = UserSession::whereBetween('enrolled_at', [$startDate, $endDate])
            ->with('user.division')
            ->get()
            ->groupBy('user.division.name')
            ->map(function($enrollments) {
                return $enrollments->count();
            });

        return view('admin.reports.enrollments', compact(
            'wellnessEnrollments',
            'scheduleEnrollments', 
            'divisionBreakdown',
            'startDate',
            'endDate'
        ));
    }

    /**
     * General reports dashboard
     */
    public function reports()
    {
        // Capacity utilization
        $wellnessSessions = WellnessSession::withCount(['userSessions' => function($query) {
            $query->where('status', 'confirmed');
        }])->get();

        $capacityData = $wellnessSessions->map(function($session) {
            return [
                'title' => $session->title,
                'capacity' => $session->max_participants,
                'enrolled' => $session->user_sessions_count,
                'utilization' => $session->max_participants > 0 ? 
                    round(($session->user_sessions_count / $session->max_participants) * 100, 2) : 0
            ];
        });

        // Daily enrollment trends
        $enrollmentTrends = UserSession::selectRaw('DATE(enrolled_at) as date, COUNT(*) as count')
            ->where('enrolled_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.index', compact('capacityData', 'enrollmentTrends'));
    }

    /**
     * Toggle Wellness feature activation
     */
    public function toggleWellness()
    {
        WellnessSetting::initialize();
        $settings = WellnessSetting::getActive();
        
        $settings->update(['is_active' => !$settings->is_active]);
        
        $status = $settings->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Wellness feature {$status} successfully!");
    }

    /**
     * Toggle PL Days feature activation
     */
    public function togglePLDays()
    {
        PLDaysSetting::initialize();
        $settings = PLDaysSetting::getActive();
        
        $settings->update(['is_active' => !$settings->is_active]);
        
        $status = $settings->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "PL Days feature {$status} successfully!");
    }
}
