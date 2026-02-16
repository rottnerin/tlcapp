@extends('layouts.app')

@section('title', 'Division Summary Report')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Division Summary Report</h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.reports') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg text-sm font-medium transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Reports
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-download mr-2"></i> Export CSV
            </a>
            <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Report Scope</h2>
        <form method="GET" action="{{ route('admin.reports.division-summary') }}" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="p_d_day_id" class="block text-sm font-medium text-gray-700 mb-1">PD Day</label>
                <select name="p_d_day_id" id="p_d_day_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-tlc-navy focus:ring-tlc-navy">
                    @foreach($pdDays ?? [] as $pd)
                        <option value="{{ $pd->id }}" {{ ($pDDayId ?? '') == $pd->id ? 'selected' : '' }}>{{ $pd->title }} ({{ $pd->start_date->format('M j') }})</option>
                    @endforeach
                </select>
                <p class="mt-0.5 text-xs text-gray-500">CCL and Wellness enrollment for this day</p>
            </div>
            <div class="flex-1">
                <label for="division_id" class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                <select name="division_id" id="division_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-tlc-navy focus:ring-tlc-navy">
                    <option value="">All divisions</option>
                    @foreach($divisions ?? [] as $d)
                        <option value="{{ $d->id }}" {{ ($divisionId ?? '') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-tlc-orange hover:bg-tlc-navy text-white font-medium rounded-lg transition">
                <i class="fas fa-search mr-2"></i> Update
            </button>
        </form>
    </div>

    <!-- User Enrollment Matrix (primary content) - Sorted by Division, then Name -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-lg font-semibold text-gray-900">User Enrollment by Division</h2>
            <p class="text-sm text-gray-500">Users sorted by division, then name. Empty cells = not enrolled.</p>
        </div>
        <div class="overflow-x-auto">
            @if(!empty($userMatrixRows))
                <table class="min-w-full divide-y divide-gray-200" id="user-matrix-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Division</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">Wellness</th>
                            @foreach($cclSessionHeaders ?? [] as $h)
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider whitespace-nowrap">{{ $h }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($userMatrixRows as $row)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $row['division'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $row['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $row['email'] }}</td>
                                <td class="px-4 py-3 text-sm {{ empty($row['wellness']) ? 'bg-amber-50 text-gray-500 italic' : 'text-gray-900' }}">{{ $row['wellness'] ?: '—' }}</td>
                                @foreach($row['ccl_by_session'] ?? [] as $sessionTitle)
                                    <td class="px-4 py-3 text-sm {{ empty($sessionTitle) ? 'bg-amber-50 text-gray-500 italic' : 'text-gray-900' }}">{{ $sessionTitle ?: '—' }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                    <p>Select a PD Day and click Update to see user enrollments.</p>
                    <p class="text-sm mt-1">Columns: Division, Name, Email, Wellness, CCL Session 1, CCL Session 2.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Compact division stats (collapsed summary) -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="text-lg font-semibold text-gray-900">Division Stats</h2>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $divisionData->where('participation_rate', '>=', 70)->count() }} High</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">{{ $divisionData->where('participation_rate', '>=', 30)->where('participation_rate', '<', 70)->count() }} Medium</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $divisionData->where('participation_rate', '<', 30)->count() }} Low</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            @if($divisionData->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Division</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Users</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Enrollments</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Wellness</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">CCL</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Participation</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($divisionData->sortBy('name') as $division)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $division['name'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $division['total_users'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $division['total_enrollments'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $division['wellness_enrollments'] }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $division['schedule_enrollments'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 max-w-[80px] h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $division['participation_rate'] >= 70 ? 'bg-green-500' : ($division['participation_rate'] >= 30 ? 'bg-amber-500' : 'bg-red-400') }}" style="width: {{ min($division['participation_rate'], 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $division['participation_rate'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-8 text-center text-gray-500">
                    <p>No division data. Add a PD Day to see enrollment stats.</p>
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('user-matrix-table');
    if (!table) return;
    const thead = table.querySelector('thead tr');
    const tbody = table.querySelector('tbody');
    if (!thead || !tbody) return;

    // Make column headers clickable for sorting (Division=0, Name=1, Email=2, Wellness=3, then CCL)
    thead.querySelectorAll('th').forEach((th, colIndex) => {
        th.style.cursor = 'pointer';
        th.title = 'Click to sort';
        th.addEventListener('click', function() {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const asc = th.dataset.sort === 'asc';
            th.dataset.sort = asc ? 'desc' : 'asc';
            // Reset other headers
            thead.querySelectorAll('th').forEach((h, i) => { if (i !== colIndex) delete h.dataset.sort; });

            rows.sort((a, b) => {
                const aVal = (a.cells[colIndex]?.textContent ?? '').trim();
                const bVal = (b.cells[colIndex]?.textContent ?? '').trim();
                const cmp = aVal.localeCompare(bVal, undefined, { numeric: true });
                return asc ? cmp : -cmp;
            });
            rows.forEach(r => tbody.appendChild(r));
        });
    });
});
</script>
@endpush
@endsection
