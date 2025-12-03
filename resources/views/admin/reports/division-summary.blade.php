@extends('layouts.app')

@section('title', 'Division Summary Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Division Summary Report</h1>
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
                    <h6 class="m-0 font-weight-bold text-primary">Date Range Filter</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.division-summary') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">From Date</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ $dateFrom }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">To Date</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ $dateTo }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
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
                                Total Divisions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $divisionData->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
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
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $divisionData->sum('total_users') }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Enrollments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $divisionData->sum('total_enrollments') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
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
                                Avg Participation
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $divisionData->count() > 0 ? round($divisionData->avg('participation_rate'), 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
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
                        Division Enrollment Summary ({{ $divisionData->count() }} divisions)
                    </h6>
                    <div>
                        <span class="badge badge-success">{{ $divisionData->where('participation_rate', '>=', 70)->count() }} High Participation</span>
                        <span class="badge badge-warning">{{ $divisionData->where('participation_rate', '>=', 30)->where('participation_rate', '<', 70)->count() }} Medium</span>
                        <span class="badge badge-danger">{{ $divisionData->where('participation_rate', '<', 30)->count() }} Low</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($divisionData->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Division</th>
                                        <th>Total Users</th>
                                        <th>Total Enrollments</th>
                                        <th>Wellness Enrollments</th>
                                        <th>Schedule Enrollments</th>
                                        <th>Participation Rate</th>
                                        <th>Progress Bar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($divisionData->sortByDesc('participation_rate') as $division)
                                        <tr class="{{ $division['participation_rate'] >= 70 ? 'table-success' : ($division['participation_rate'] < 30 ? 'table-warning' : '') }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        {{ substr($division['name'], 0, 1) }}
                                                    </div>
                                                    <strong>{{ $division['name'] }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">{{ $division['total_users'] }}</span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-primary">{{ $division['total_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ $division['wellness_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">{{ $division['schedule_enrollments'] }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bold me-2">{{ $division['participation_rate'] }}%</span>
                                                    @if($division['participation_rate'] >= 70)
                                                        <i class="fas fa-arrow-up text-success"></i>
                                                    @elseif($division['participation_rate'] < 30)
                                                        <i class="fas fa-arrow-down text-warning"></i>
                                                    @else
                                                        <i class="fas fa-minus text-info"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar 
                                                        {{ $division['participation_rate'] >= 70 ? 'bg-success' : ($division['participation_rate'] >= 30 ? 'bg-info' : 'bg-warning') }}" 
                                                        role="progressbar" 
                                                        style="width: {{ min($division['participation_rate'], 100) }}%"
                                                        aria-valuenow="{{ $division['participation_rate'] }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                        {{ $division['participation_rate'] }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No divisions found</h5>
                            <p class="text-muted">No division data available for the selected date range.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Division Comparison Chart -->
    @if($divisionData->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Participation Rate by Division</h6>
                </div>
                <div class="card-body">
                    <canvas id="participationChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Enrollment Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="enrollmentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const divisionData = @json($divisionData);
    
    if (divisionData.length > 0) {
        // Participation Rate Chart
        const participationCtx = document.getElementById('participationChart').getContext('2d');
        new Chart(participationCtx, {
            type: 'bar',
            data: {
                labels: divisionData.map(d => d.name),
                datasets: [{
                    label: 'Participation Rate (%)',
                    data: divisionData.map(d => d.participation_rate),
                    backgroundColor: divisionData.map(d => 
                        d.participation_rate >= 70 ? 'rgba(75, 192, 192, 0.8)' :
                        d.participation_rate >= 30 ? 'rgba(54, 162, 235, 0.8)' :
                        'rgba(255, 205, 86, 0.8)'
                    ),
                    borderColor: divisionData.map(d => 
                        d.participation_rate >= 70 ? 'rgba(75, 192, 192, 1)' :
                        d.participation_rate >= 30 ? 'rgba(54, 162, 235, 1)' :
                        'rgba(255, 205, 86, 1)'
                    ),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
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

        // Enrollment Distribution Chart
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        new Chart(enrollmentCtx, {
            type: 'doughnut',
            data: {
                labels: divisionData.map(d => d.name),
                datasets: [{
                    data: divisionData.map(d => d.total_enrollments),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(255, 159, 64, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
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
