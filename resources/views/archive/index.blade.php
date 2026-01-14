@extends('layouts.user')

@section('title', 'Archive - Professional Learning')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
}

.archive-header {
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    color: white;
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.year-filter {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--tlc-gold);
    font-weight: 500;
    color: var(--tlc-navy);
}

.pd-day-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.08);
    margin-bottom: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.pd-day-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(13, 59, 102, 0.12);
}

.pd-day-header {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
    padding: 1rem 1.5rem;
    font-weight: 600;
}

.pd-day-body {
    padding: 1.5rem;
}

.season-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.season-fall {
    background: #fef3c7;
    color: #92400e;
}

.season-spring {
    background: #d1fae5;
    color: #065f46;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
}
</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="archive-header">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">
                        <i class="fas fa-archive mr-2"></i>Professional Learning Archive
                    </h1>
                    <p class="opacity-90">Browse past professional learning events</p>
                </div>
                <div>
                    <form method="GET" class="flex items-center gap-2">
                        <select name="year" class="year-filter" onchange="this.form.submit()">
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $year == $academicYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>

        @if($pdDays->count() > 0)
            @foreach($pdDays as $pdDay)
            <div class="pd-day-card">
                <div class="pd-day-header">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if($pdDay->season === 'fall')
                                <span class="season-badge season-fall">
                                    <i class="fas fa-leaf"></i>
                                    Fall
                                </span>
                            @elseif($pdDay->season === 'spring')
                                <span class="season-badge season-spring">
                                    <i class="fas fa-sun"></i>
                                    Spring
                                </span>
                            @endif
                            <span>{{ $pdDay->title }}</span>
                        </div>
                        <span class="text-sm opacity-75">{{ $pdDay->date_range }}</span>
                    </div>
                </div>
                <div class="pd-day-body">
                    @if($pdDay->description)
                    <p class="text-gray-600 mb-4">{{ $pdDay->description }}</p>
                    @endif
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-tlc-navy">{{ $pdDay->scheduleItems->count() }}</div>
                            <div class="text-gray-500">Schedule Items</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-tlc-navy">{{ $pdDay->wellnessSessions->count() }}</div>
                            <div class="text-gray-500">Wellness Sessions</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-tlc-navy">{{ $pdDay->tttSessions->count() }}</div>
                            <div class="text-gray-500">TTT Sessions</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-lg font-semibold text-gray-400">
                                {{ $pdDay->start_date->format('M j') }} - {{ $pdDay->end_date->format('M j, Y') }}
                            </div>
                            <div class="text-gray-500">Date Range</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="text-5xl mb-4">📁</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Archived Events</h3>
                <p class="text-gray-500">No professional learning events found for {{ $academicYear }}.</p>
            </div>
        @endif
    </div>
</div>
@endsection
