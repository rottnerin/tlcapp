@extends('layouts.app')

@section('title', 'Capacity Utilization Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Capacity Utilization Report</h1>
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
                    <form method="GET" action="{{ route('admin.reports.capacity-utilization') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                            {{ $cat }}
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
                                Total Sessions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $sessions->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                Average Utilization
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $sessions->count() > 0 ? round($sessions->avg('utilization'), 1) : 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
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
                                Full Sessions
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $sessions->where('utilization', 100)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Low Utilization
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $sessions->where('utilization', '<', 50)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
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
                        Session Capacity Analysis ({{ $sessions->count() }} sessions)
                    </h6>
                    <div>
                        <span class="badge badge-success">{{ $sessions->where('utilization', 100)->count() }} Full</span>
                        <span class="badge badge-warning">{{ $sessions->where('utilization', '>=', 50)->where('utilization', '<', 100)->count() }} Good</span>
                        <span class="badge badge-danger">{{ $sessions->where('utilization', '<', 50)->count() }} Low</span>
                    </div>
                </div>
                <div class="card-body">
                    @if($sessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Session Title</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Capacity</th>
                                        <th>Enrolled</th>
                                        <th>Utilization</th>
                                        <th>Available Spots</th>
                                        <th>Status</th>
                                        <th>Progress Bar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sessions as $session)
                                        <tr class="{{ $session['utilization'] == 100 ? 'table-success' : ($session['utilization'] < 50 ? 'table-warning' : '') }}">
                                            <td>
                                                <strong>{{ $session['title'] }}</strong>
                                            </td>
                                            <td>{{ $session['date']->format('M d, Y') }}</td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    {{ $session['category'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">{{ $session['max_participants'] }}</span>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold">{{ $session['enrolled'] }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="font-weight-bold me-2">{{ $session['utilization'] }}%</span>
                                                    @if($session['utilization'] >= 80)
                                                        <i class="fas fa-arrow-up text-success"></i>
                                                    @elseif($session['utilization'] < 50)
                                                        <i class="fas fa-arrow-down text-warning"></i>
                                                    @else
                                                        <i class="fas fa-minus text-info"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($session['available_spots'] > 0)
                                                    <span class="badge badge-info">{{ $session['available_spots'] }} spots</span>
                                                @else
                                                    <span class="badge badge-danger">Full</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($session['status'] === 'available')
                                                    <span class="badge badge-success">Available</span>
                                                @elseif($session['status'] === 'full')
                                                    <span class="badge badge-danger">Full</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($session['status']) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar 
                                                        {{ $session['utilization'] >= 80 ? 'bg-success' : ($session['utilization'] >= 50 ? 'bg-info' : 'bg-warning') }}" 
                                                        role="progressbar" 
                                                        style="width: {{ $session['utilization'] }}%"
                                                        aria-valuenow="{{ $session['utilization'] }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                        {{ $session['utilization'] }}%
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
                            <h5 class="text-muted">No sessions found</h5>
                            <p class="text-muted">Try adjusting your filters to see more results.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Utilization Chart -->
    @if($sessions->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Utilization Distribution</h6>
                </div>
                <div class="card-body">
                    <canvas id="utilizationChart" width="400" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sessions = @json($sessions);
    
    if (sessions.length > 0) {
        const ctx = document.getElementById('utilizationChart').getContext('2d');
        
        // Group sessions by utilization ranges
        const ranges = {
            '0-25%': sessions.filter(s => s.utilization >= 0 && s.utilization < 25).length,
            '25-50%': sessions.filter(s => s.utilization >= 25 && s.utilization < 50).length,
            '50-75%': sessions.filter(s => s.utilization >= 50 && s.utilization < 75).length,
            '75-100%': sessions.filter(s => s.utilization >= 75 && s.utilization <= 100).length
        };
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(ranges),
                datasets: [{
                    label: 'Number of Sessions',
                    data: Object.values(ranges),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)'
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
@endsection
