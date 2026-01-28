<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Division Summary Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 20px; }
        .header { background: #1e3a5f; color: #f5f0e1; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .meta { color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1e3a5f; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; }
        tr:nth-child(even) { background: #f5f0e1; }
        .participation-high { background: #d1fae5; color: #065f46; font-weight: bold; }
        .participation-medium { background: #fef3c7; color: #92400e; font-weight: bold; }
        .participation-low { background: #fee2e2; color: #991b1b; font-weight: bold; }
        .summary-box { margin-top: 20px; padding: 15px; background: #f5f0e1; border-left: 4px solid #1e3a5f; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Division Summary Report</h1>
    </div>

    <div class="meta">
        Generated: {{ $generatedAt->format('F j, Y g:i A') }}<br>
        Date Range: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}<br>
        Total Divisions: {{ $divisionData->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Division</th>
                <th>Total Users</th>
                <th>Total Enrollments</th>
                <th>Wellness</th>
                <th>Schedule Items</th>
                <th>Participation Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($divisionData as $division)
            <tr>
                <td><strong>{{ $division['name'] }}</strong></td>
                <td>{{ $division['total_users'] }}</td>
                <td>{{ $division['total_enrollments'] }}</td>
                <td>{{ $division['wellness_enrollments'] }}</td>
                <td>{{ $division['schedule_enrollments'] }}</td>
                <td class="
                    @if($division['participation_rate'] >= 80) participation-high
                    @elseif($division['participation_rate'] >= 50) participation-medium
                    @else participation-low
                    @endif
                ">
                    {{ $division['participation_rate'] }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <strong>Summary Statistics:</strong><br>
        Total Users: {{ $divisionData->sum('total_users') }}<br>
        Total Enrollments: {{ $divisionData->sum('total_enrollments') }}<br>
        Total Wellness Enrollments: {{ $divisionData->sum('wellness_enrollments') }}<br>
        Total Schedule Enrollments: {{ $divisionData->sum('schedule_enrollments') }}<br>
        Average Participation Rate: {{ number_format($divisionData->avg('participation_rate'), 2) }}%
    </div>

    <div style="margin-top: 20px; padding: 15px; background: #f5f0e1; border-left: 4px solid #c9a227;">
        <strong>Participation Guide:</strong><br>
        <span class="participation-high" style="padding: 2px 6px; margin-right: 10px;">80%+ High</span>
        <span class="participation-medium" style="padding: 2px 6px; margin-right: 10px;">50-79% Medium</span>
        <span class="participation-low" style="padding: 2px 6px;">Below 50% Low</span>
    </div>
</body>
</html>
