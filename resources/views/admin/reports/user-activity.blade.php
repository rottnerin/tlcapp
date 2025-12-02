@extends('layouts.app')

@section('title', 'User Activity Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">User Activity Report</h1>
                <div>
                    <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['export' => '1']) }}" class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.user-activity') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="division_id" class="form-label">Division</label>
                                <select name="division_id" id="division_id" class="form-control">
                                    <option value="">All Divisions</option>
                                    @foreach($divisions as $division)
                                        <option value="{{ $division->id }}" {{ $divisionId == $division->id ? 'selected' : '' }}>
                                            {{ $division->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('total_enrollments', '>', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Inactive Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('total_enrollments', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg Enrollments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->count() > 0 ? round($users->avg('total_enrollments'), 1) : 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        User Activity Summary ({{ $users->count() }} users)
                    </h6>
                    <div>
                        <span class="badge badge-success">{{ $users->where('total_enrollments', '>', 0)->count() }} Active</span>
                        <span class="badge badge-warning">{{ $users->where('total_enrollments', 0)->count() }} Inactive</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Division</th>
                                        <th>Total Enrollments</th>
                                        <th>Wellness</th>
                                        <th>Schedule</th>
                                        <th>Last Enrollment</th>
                                        <th>Last Login</th>
                                        <th>Activity Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users->sortByDesc('total_enrollments') as $user)
                                        <tr class="{{ $user['total_enrollments'] == 0 ? 'table-warning' : '' }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($user['name'], 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <strong>{{ $user['name'] }}</strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user['email'] }}</td>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $user['division'] ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-primary">{{ $user['total_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">{{ $user['wellness_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $user['schedule_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                @if($user['last_enrollment'])
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $user['last_enrollment']->format('M d, Y') }}</span>
                                                        <small class="text-muted">{{ $user['last_enrollment']->diffForHumans() }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user['last_login'])
                                                    <div class="d-flex flex-column">
                                                        <span>{{ $user['last_login']->format('M d, Y') }}</span>
                                                        <small class="text-muted">{{ $user['last_login']->diffForHumans() }}</small>
                                                    </div>
                                                @else
                                                    <span class="badge badge-warning">Never</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user['total_enrollments'] >= 5)
                                                    <span class="badge badge-success">High</span>
                                                @elseif($user['total_enrollments'] >= 2)
                                                    <span class="badge badge-info">Medium</span>
                                                @elseif($user['total_enrollments'] == 1)
                                                    <span class="badge badge-warning">Low</span>
                                                @else
                                                    <span class="badge badge-danger">None</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No users found</h5>
                            <p class="text-muted">Try adjusting your filters to see more results.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Distribution Chart -->
    @if($users->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Level Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Types</h6>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentTypeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const users = @json($users);
    
    if (users.length > 0) {
        // Activity Level Chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        
        const activityLevels = {
            'High (5+)': users.filter(u => u.total_enrollments >= 5).length,
            'Medium (2-4)': users.filter(u => u.total_enrollments >= 2 && u.total_enrollments < 5).length,
            'Low (1)': users.filter(u => u.total_enrollments === 1).length,
            'None (0)': users.filter(u => u.total_enrollments === 0).length
        };
        
        new Chart(activityCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(activityLevels),
                datasets: [{
                    data: Object.values(activityLevels),
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Enrollment Types Chart
        const enrollmentTypeCtx = document.getElementById('enrollmentTypeChart').getContext('2d');
        
        const totalWellness = users.reduce((sum, user) => sum + user.wellness_enrollments, 0);
        const totalSchedule = users.reduce((sum, user) => sum + user.schedule_enrollments, 0);
        
        new Chart(enrollmentTypeCtx, {
            type: 'bar',
            data: {
                labels: ['Wellness Sessions', 'Schedule Items'],
                datasets: [{
                    label: 'Total Enrollments',
                    data: [totalWellness, totalSchedule],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
});
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
    font-weight: bold;
}
</style>
@endsection
