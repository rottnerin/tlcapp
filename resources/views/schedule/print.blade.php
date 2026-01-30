<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($season) }} PL Day Schedule - {{ $selectedDate->format('F j, Y') }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0.75in;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            background: white;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        .print-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 0.5in;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #c9a227;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }

        .header-logo img {
            height: 50px;
            width: auto;
        }

        .header-info {
            text-align: right;
        }

        .school-name {
            font-size: 14pt;
            font-weight: bold;
            color: #0d3b66;
            margin-bottom: 0.25rem;
        }

        .school-address {
            font-size: 8pt;
            color: #666;
            line-height: 1.3;
        }

        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 1rem;
        }

        .document-title {
            font-size: 16pt;
            font-weight: bold;
            color: #0d3b66;
            margin-bottom: 0.5rem;
        }

        .document-subtitle {
            font-size: 11pt;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .filter-info {
            font-size: 9pt;
            color: #666;
            font-style: italic;
        }

        /* Schedule Table */
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .schedule-table thead {
            background: linear-gradient(135deg, #0d3b66 0%, #164773 100%);
            color: white;
        }

        .schedule-table th {
            padding: 0.5rem;
            text-align: left;
            font-size: 9pt;
            font-weight: 600;
            border: 1px solid #ddd;
        }

        .schedule-table td {
            padding: 0.5rem;
            border: 1px solid #ddd;
            font-size: 9pt;
            vertical-align: top;
        }

        .schedule-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .schedule-table tbody tr:hover {
            background-color: #faf0ca;
        }

        .time-cell {
            white-space: nowrap;
            font-weight: 600;
            color: #0d3b66;
            width: 120px;
        }

        .title-cell {
            font-weight: 600;
            color: #0d3b66;
            width: 180px;
        }

        .description-cell {
            color: #555;
            line-height: 1.4;
        }

        .presenter-cell {
            color: #666;
            font-style: italic;
            width: 140px;
        }

        .location-cell {
            color: #666;
            width: 120px;
        }

        .divisions-cell {
            width: 100px;
        }

        .division-badge {
            display: inline-block;
            padding: 0.15rem 0.4rem;
            border-radius: 3px;
            font-size: 7pt;
            font-weight: 600;
            margin: 0.1rem;
            white-space: nowrap;
        }

        .division-es {
            background-color: #f4d35e;
            color: #0d3b66;
        }

        .division-ms {
            background-color: #ee964b;
            color: #0d3b66;
        }

        .division-hs {
            background-color: #0d3b66;
            color: white;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-style: italic;
        }

        /* Print Actions */
        .print-actions {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            z-index: 1000;
        }

        .print-btn,
        .close-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .print-btn {
            background: linear-gradient(135deg, #0d3b66 0%, #164773 100%);
            color: white;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(13, 59, 102, 0.4);
        }

        .close-btn {
            background: #e5e7eb;
            color: #374151;
        }

        .close-btn:hover {
            background: #d1d5db;
        }

        /* Footer */
        .footer {
            margin-top: 1.5rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 8pt;
            color: #999;
        }

        /* Hide print actions when printing */
        @media print {
            .print-actions {
                display: none !important;
            }

            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .schedule-table thead {
                background: linear-gradient(135deg, #0d3b66 0%, #164773 100%) !important;
                color: white !important;
            }
        }
    </style>
</head>
<body>
    <!-- Print/Close Buttons -->
    <div class="print-actions">
        <button onclick="window.print()" class="print-btn">🖨️ Print</button>
        <button onclick="window.close()" class="close-btn">✕ Close</button>
    </div>

    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('images/aes-logo.png') }}" alt="AES Logo">
            </div>
            <div class="header-info">
                <div class="school-name">American Embassy School</div>
                <div class="school-address">
                    Chandragupta Marg, Chanakyapuri<br>
                    New Delhi 110021, India<br>
                    Tel: +91 11 2688 8854
                </div>
            </div>
        </div>

        <!-- Title Section -->
        <div class="title-section">
            <div class="document-title">{{ ucfirst($season) }} PL Day Schedule</div>
            <div class="document-subtitle">
                Day {{ str_replace('day', '', $activeTab) }} - {{ $selectedDate->format('l, F j, Y') }}
            </div>
            <div class="filter-info">
                @if(!empty($selectedDivisions))
                    Showing:
                    @foreach($divisions as $division)
                        @if(in_array($division->id, $selectedDivisions))
                            {{ $division->full_name }}{{ !$loop->last ? ', ' : '' }}
                        @endif
                    @endforeach
                @else
                    Showing: All Divisions
                @endif
            </div>
        </div>

        <!-- Schedule Table -->
        @if($scheduleItems->count() > 0)
        <table class="schedule-table">
            <thead>
                <tr>
                    <th class="time-cell">Time</th>
                    <th class="title-cell">Session Title</th>
                    <th class="description-cell">Description</th>
                    <th class="presenter-cell">Presenter</th>
                    <th class="location-cell">Location</th>
                    <th class="divisions-cell">Divisions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scheduleItems as $item)
                <tr>
                    <td class="time-cell">
                        {{ $item->start_time->format('g:i A') }}<br>
                        <span style="font-size: 8pt; font-weight: normal;">{{ $item->end_time->format('g:i A') }}</span>
                    </td>
                    <td class="title-cell">{{ $item->title }}</td>
                    <td class="description-cell">
                        @if($item->description)
                            {{ \Illuminate\Support\Str::limit($item->description, 150) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="presenter-cell">
                        {{ $item->presenter_primary ?? '—' }}
                    </td>
                    <td class="location-cell">
                        {{ $item->location ?? 'TBD' }}
                    </td>
                    <td class="divisions-cell">
                        @if($item->divisions->count() > 0)
                            @foreach($item->divisions as $division)
                                <span class="division-badge division-{{ strtolower($division->name) }}">
                                    {{ $division->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="division-badge" style="background-color: #e5e7eb; color: #666;">All</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            No sessions found for your selected filters.
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            Generated on {{ now()->format('F j, Y \a\t g:i A') }} | TLC Professional Learning Management System
        </div>
    </div>

    <script>
        // Auto-focus on print button when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Auto-trigger print dialog
            // Uncomment the line below if you want the print dialog to open automatically
            // window.print();
        });
    </script>
</body>
</html>
