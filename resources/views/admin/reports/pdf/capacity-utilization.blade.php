<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Capacity Utilization Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 20px; }
        .header { background: #1e3a5f; color: #f5f0e1; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .meta { color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1e3a5f; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; }
        tr:nth-child(even) { background: #f5f0e1; }
        .utilization-high { background: #d1fae5; color: #065f46; font-weight: bold; }
        .utilization-medium { background: #fef3c7; color: #92400e; font-weight: bold; }
        .utilization-low { background: #fee2e2; color: #991b1b; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Capacity Utilization Report</h1>
    </div>

    <div class="meta">
        Generated: {{ $generatedAt->format('F j, Y g:i A') }}<br>
        Total Sessions: {{ $sessions->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Session</th>
                <th>Date</th>
                <th>Category</th>
                <th>Capacity</th>
                <th>Enrolled</th>
                <th>Utilization</th>
                <th>Available</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sessions as $session)
            <tr>
                <td>{{ $session['title'] }}</td>
                <td>{{ $session['date']->format('M d, Y') }}</td>
                <td>{{ $session['category'] }}</td>
                <td>{{ $session['max_participants'] }}</td>
                <td>{{ $session['enrolled'] }}</td>
                <td class="
                    @if($session['utilization'] >= 80) utilization-high
                    @elseif($session['utilization'] >= 50) utilization-medium
                    @else utilization-low
                    @endif
                ">
                    {{ $session['utilization'] }}%
                </td>
                <td>{{ $session['available_spots'] }}</td>
                <td>{{ ucfirst($session['status']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; padding: 15px; background: #f5f0e1; border-left: 4px solid #1e3a5f;">
        <strong>Utilization Guide:</strong><br>
        <span class="utilization-high" style="padding: 2px 6px; margin-right: 10px;">80%+ High</span>
        <span class="utilization-medium" style="padding: 2px 6px; margin-right: 10px;">50-79% Medium</span>
        <span class="utilization-low" style="padding: 2px 6px;">Below 50% Low</span>
    </div>
</body>
</html>
