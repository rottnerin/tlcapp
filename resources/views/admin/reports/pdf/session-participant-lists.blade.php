<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Session Participant Lists</title>
    <style>
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; }
        .session-page { page-break-after: always; padding: 20px; }
        .session-page:last-child { page-break-after: auto; }
        .header { background: #1e3a5f; color: #f5f0e1; padding: 15px; margin-bottom: 15px; }
        .header h1 { margin: 0 0 10px 0; font-size: 20px; }
        .header p { margin: 3px 0; font-size: 12px; }
        .meta { color: #666; font-size: 11px; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th { background: #1e3a5f; color: white; padding: 8px; text-align: left; font-size: 10px; }
        td { border: 1px solid #ddd; padding: 6px; }
        tr:nth-child(even) { background: #f5f0e1; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; display: inline-block; }
        .badge-wellness { background: #c9a227; color: white; }
        .badge-ccl { background: #9333ea; color: white; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    @foreach($sessionData as $session)
    <div class="session-page">
        <div class="header">
            <h1>{{ $session['title'] }}</h1>
            <p><strong>Type:</strong> <span class="badge badge-{{ strtolower($session['type']) }}">{{ $session['type'] }}</span></p>
            <p><strong>Date:</strong> {{ $session['date']->format('l, F j, Y') }}</p>
            <p><strong>Time:</strong> {{ $session['start_time']->format('g:i A') }} - {{ $session['end_time']->format('g:i A') }}</p>
            <p><strong>Location:</strong> {{ $session['location'] ?? 'TBD' }}</p>
            <p><strong>Presenter:</strong> {{ $session['presenter'] ?? 'TBD' }}</p>
            <p><strong>Capacity:</strong> {{ $session['enrolled'] }} / {{ $session['capacity'] ?? 'Unlimited' }}</p>
        </div>

        @if($session['participants']->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Division</th>
                    <th>Enrolled Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($session['participants'] as $participant)
                <tr>
                    <td>{{ $participant->user->name }}</td>
                    <td>{{ $participant->user->email }}</td>
                    <td>{{ $participant->user->division->name ?? 'N/A' }}</td>
                    <td>{{ $participant->enrolled_at->format('M d, Y') }}</td>
                    <td>{{ ucfirst($participant->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="text-align: center; color: #666; padding: 20px;">No participants enrolled</p>
        @endif

        <div class="footer">
            Generated: {{ $generatedAt->format('F j, Y g:i A') }} | Page {{ $loop->iteration }} of {{ count($sessionData) }}
        </div>
    </div>
    @endforeach
</body>
</html>
