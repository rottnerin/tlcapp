<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CCL Enrollments Report</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 20px; }
        .header { background: #1e3a5f; color: #f5f0e1; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .meta { color: #666; font-size: 12px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1e3a5f; color: white; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 6px; }
        tr:nth-child(even) { background: #f5f0e1; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 10px; }
        .badge-confirmed { background: #d1fae5; color: #065f46; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CCL Enrollments Report</h1>
    </div>

    <div class="meta">
        Generated: {{ $generatedAt->format('F j, Y g:i A') }}<br>
        Total Enrollments: {{ $enrollments->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Division</th>
                <th>Session</th>
                <th>Date/Time</th>
                <th>Location</th>
                <th>Status</th>
                <th>Enrolled</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
            <tr>
                <td>{{ $enrollment->user->name }}</td>
                <td>{{ $enrollment->user->email }}</td>
                <td>{{ $enrollment->user->division?->name ?? 'N/A' }}</td>
                <td>{{ $enrollment->scheduleItem?->title ?? 'N/A' }}</td>
                <td>
                    {{ $enrollment->scheduleItem?->date?->format('M d, Y') ?? 'N/A' }}<br>
                    {{ $enrollment->scheduleItem?->start_time?->format('g:i A') }} - {{ $enrollment->scheduleItem?->end_time?->format('g:i A') }}
                </td>
                <td>{{ $enrollment->scheduleItem?->location ?? 'TBD' }}</td>
                <td>
                    <span class="badge badge-{{ $enrollment->status }}">
                        {{ ucfirst($enrollment->status) }}
                    </span>
                </td>
                <td>{{ $enrollment->enrolled_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
