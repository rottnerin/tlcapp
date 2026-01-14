<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Professional Learning Transcript - {{ $user->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 1in;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            background: white;
        }
        
        .print-container {
            max-width: 8.5in;
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
            height: 60px;
            width: auto;
        }
        
        .header-info {
            text-align: right;
        }
        
        .school-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0d3b66;
            margin-bottom: 0.25rem;
        }
        
        .school-address {
            font-size: 9pt;
            color: #666;
            line-height: 1.3;
        }
        
        /* Contact bar */
        .contact-bar {
            display: flex;
            justify-content: center;
            gap: 2rem;
            font-size: 8pt;
            color: #666;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e0e0e0;
            margin-bottom: 1.5rem;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .document-title {
            font-size: 18pt;
            font-weight: bold;
            color: #0d3b66;
            margin-bottom: 0.5rem;
        }
        
        .document-subtitle {
            font-size: 12pt;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .teacher-name {
            font-size: 14pt;
            font-weight: bold;
            color: #333;
            margin-top: 0.5rem;
        }
        
        /* Certification */
        .certification {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 10pt;
            font-style: italic;
            line-height: 1.6;
        }
        
        /* Session Table */
        .session-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
            font-size: 10pt;
        }
        
        .session-table th {
            background: #0d3b66;
            color: white;
            padding: 0.75rem 0.5rem;
            text-align: left;
            font-weight: 600;
        }
        
        .session-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        
        .session-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .session-table .date-col {
            width: 100px;
            white-space: nowrap;
        }
        
        .session-table .hours-col {
            width: 80px;
            text-align: center;
        }
        
        .session-table .presenter-col {
            width: 150px;
        }
        
        .total-row {
            font-weight: bold;
            background: #f4d35e !important;
        }
        
        .total-row td {
            border-top: 2px solid #0d3b66;
        }
        
        /* Signature Block */
        .signature-block {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e0e0e0;
        }
        
        .signature-line {
            width: 250px;
            border-bottom: 1px solid #333;
            margin-top: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .signature-name {
            font-weight: bold;
            color: #0d3b66;
        }
        
        .signature-title {
            font-size: 10pt;
            color: #666;
        }
        
        /* Footer */
        .footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 2px solid #c9a227;
            text-align: center;
        }
        
        .footer-motto {
            font-size: 12pt;
            font-style: italic;
            color: #c9a227;
            margin-bottom: 0.5rem;
        }
        
        .footer-mission {
            font-size: 8pt;
            color: #666;
            max-width: 500px;
            margin: 0 auto;
        }
        
        /* Print button */
        .print-actions {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1000;
        }
        
        .print-btn {
            background: #0d3b66;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 14px;
            margin-right: 0.5rem;
        }
        
        .print-btn:hover {
            background: #164773;
        }
        
        @media print {
            .print-actions {
                display: none;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .print-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="print-btn" onclick="window.print()">
            🖨️ Print
        </button>
        <button class="print-btn" onclick="window.close()">
            ✕ Close
        </button>
    </div>

    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('logos/AES_GoldLogo.jpg') }}" alt="AES Logo" onerror="this.style.display='none'">
            </div>
            <div class="header-info">
                <div class="school-name">American Embassy School</div>
                <div class="school-address">
                    Chandragupta Marg<br>
                    Chanakyapuri<br>
                    New Delhi - 110 021
                </div>
            </div>
        </div>

        <!-- Contact Bar -->
        <div class="contact-bar">
            <span class="contact-item">📞 +91 11 2688 8854</span>
            <span class="contact-item">📠 +91 11 2687 3320</span>
            <span class="contact-item">🌐 www.aes.ac.in</span>
            <span class="contact-item">✉️ info@aes.ac.in</span>
        </div>

        <!-- Title Section -->
        <div class="title-section">
            <div class="document-title">Professional Learning Transcript</div>
            <div class="document-subtitle">School Year {{ $academicYear }}</div>
            <div class="teacher-name">Teacher Name: {{ $user->name }}</div>
        </div>

        <!-- Certification Statement -->
        <div class="certification">
            This letter certifies that {{ $user->name }} has been an active participant in numerous professional 
            learning activities offered since joining The American Embassy School. In the interest of brevity, 
            a relevant selection of these activities is recorded as below. If more evidence is required to show 
            they have met any mandatory requirements, this can be provided upon request.
        </div>

        <!-- Session Table -->
        @if(count($transcriptItems) > 0)
        <table class="session-table">
            <thead>
                <tr>
                    <th class="date-col">Date</th>
                    <th>Name of Session</th>
                    <th class="hours-col">Contact Hours</th>
                    <th class="presenter-col">Presenter</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transcriptItems as $item)
                <tr>
                    <td class="date-col">
                        {{ $item['date'] ? $item['date']->format('M j, Y') : 'TBD' }}
                    </td>
                    <td>{{ $item['title'] }}</td>
                    <td class="hours-col">{{ number_format($item['contact_hours'], 1) }}</td>
                    <td class="presenter-col">{{ $item['presenter'] ?: '—' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" style="text-align: right; padding-right: 1rem;">
                        <strong>Total Contact Hours:</strong>
                    </td>
                    <td class="hours-col">
                        <strong>{{ number_format($totalHours, 1) }}</strong>
                    </td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        @else
        <div style="text-align: center; padding: 2rem; color: #666;">
            <p>No professional learning sessions recorded for this academic year.</p>
        </div>
        @endif

        <!-- Signature Block -->
        <div class="signature-block">
            <div class="signature-line"></div>
            <div class="signature-name">Bronwyn Weale</div>
            <div class="signature-title">
                Director for Teaching and Learning<br>
                American Embassy School<br>
                www.aes.ac.in<br>
                bweale@aes.ac.in
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-motto">"Enter to learn. Leave to serve."</div>
            <div class="footer-mission">
                The American Embassy School is dedicated to providing an exceptional educational 
                experience for a diverse international community of learners.
            </div>
        </div>
    </div>
</body>
</html>
