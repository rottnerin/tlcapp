@extends('layouts.user')

@section('title', 'My PL - Professional Learning')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
}

.my-pl-header {
    background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
    color: white;
    padding: 2rem;
    border-radius: 1rem;
    margin-bottom: 2rem;
}

.section-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.08);
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.section-header {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
    padding: 1rem 1.5rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.session-item {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background 0.2s ease;
}

.session-item:last-child {
    border-bottom: none;
}

.session-item:hover {
    background: #f9fafb;
}

.session-info {
    flex: 1;
}

.session-title {
    font-weight: 600;
    color: var(--tlc-navy);
    margin-bottom: 0.25rem;
}

.session-meta {
    font-size: 0.875rem;
    color: #6b7280;
}

.remove-btn {
    background: #fee2e2;
    color: #dc2626;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.remove-btn:hover {
    background: #fecaca;
}

.print-btn {
    background: linear-gradient(135deg, var(--tlc-orange) 0%, #d97706 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(238, 150, 75, 0.25);
}

.print-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(238, 150, 75, 0.4);
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.year-filter {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 2px solid var(--tlc-gold);
    font-weight: 500;
    color: var(--tlc-navy);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 0.75rem;
    text-align: center;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
}

.stat-label {
    font-size: 0.875rem;
    opacity: 0.9;
}
</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="my-pl-header">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold mb-2">
                        <i class="fas fa-bookmark mr-2"></i>My Professional Learning
                    </h1>
                    <p class="opacity-90">Your personalized PL schedule for {{ $academicYear }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <form method="GET" class="flex items-center gap-2">
                        <select name="year" class="year-filter" onchange="this.form.submit()">
                            @foreach($academicYears as $year)
                                <option value="{{ $year }}" {{ $year == $academicYear ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    <a href="{{ route('my-pl.print', ['year' => $academicYear]) }}" class="print-btn" target="_blank">
                        <i class="fas fa-print"></i>
                        Print Transcript
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $selectedSessions->count() }}</div>
                    <div class="stat-label">Sessions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ count($groupedSessions['schedule']) }}</div>
                    <div class="stat-label">Schedule Items</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ count($groupedSessions['wellness']) }}</div>
                    <div class="stat-label">Wellness</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ count($groupedSessions['pl_wednesday']) + count($groupedSessions['ccl']) }}</div>
                    <div class="stat-label">Other</div>
                </div>
            </div>
        </div>

        @if($selectedSessions->count() === 0)
            <div class="section-card">
                <div class="empty-state">
                    <div class="empty-state-icon">📚</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Sessions Selected</h3>
                    <p class="text-gray-500 mb-4">Start building your professional learning schedule by adding sessions to My PL.</p>
                    <p class="text-sm text-gray-400">
                        Look for the <span class="inline-flex items-center px-2 py-1 bg-tlc-gold text-tlc-navy rounded-full text-xs font-medium">
                            <i class="fas fa-plus mr-1"></i>Add to My PL
                        </span> button on session cards.
                    </p>
                </div>
            </div>
        @else
            <!-- Schedule Items -->
            @if(count($groupedSessions['schedule']) > 0)
            <div class="section-card">
                <div class="section-header">
                    <span><i class="fas fa-calendar-alt mr-2"></i>Schedule Sessions</span>
                    <span class="bg-white text-tlc-navy px-3 py-1 rounded-full text-sm font-bold">
                        {{ count($groupedSessions['schedule']) }}
                    </span>
                </div>
                <div class="session-list">
                    @foreach($groupedSessions['schedule'] as $session)
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-title">{{ $session->title }}</div>
                            <div class="session-meta">
                                @if($session->date)
                                    <i class="fas fa-calendar mr-1"></i>{{ $session->date->format('M j, Y') }}
                                @endif
                                @if($session->start_time && $session->end_time)
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock mr-1"></i>{{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                @endif
                                @if($session->presenter_primary)
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-user mr-1"></i>{{ $session->presenter_primary }}
                                @endif
                            </div>
                        </div>
                        <button class="remove-btn" onclick="toggleSession('schedule_item', {{ $session->id }})">
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Wellness Sessions -->
            @if(count($groupedSessions['wellness']) > 0)
            <div class="section-card">
                <div class="section-header">
                    <span><i class="fas fa-heart mr-2"></i>Wellness Sessions</span>
                    <span class="bg-white text-tlc-navy px-3 py-1 rounded-full text-sm font-bold">
                        {{ count($groupedSessions['wellness']) }}
                    </span>
                </div>
                <div class="session-list">
                    @foreach($groupedSessions['wellness'] as $session)
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-title">{{ $session->title }}</div>
                            <div class="session-meta">
                                @if($session->date)
                                    <i class="fas fa-calendar mr-1"></i>{{ $session->date->format('M j, Y') }}
                                @endif
                                @if($session->presenter_name)
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-user mr-1"></i>{{ $session->presenter_name }}
                                @endif
                            </div>
                        </div>
                        <button class="remove-btn" onclick="toggleSession('wellness_session', {{ $session->id }})">
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- PL Wednesday Sessions -->
            @if(count($groupedSessions['pl_wednesday']) > 0)
            <div class="section-card">
                <div class="section-header">
                    <span><i class="fas fa-calendar-week mr-2"></i>PL Wednesday Sessions</span>
                    <span class="bg-white text-tlc-navy px-3 py-1 rounded-full text-sm font-bold">
                        {{ count($groupedSessions['pl_wednesday']) }}
                    </span>
                </div>
                <div class="session-list">
                    @foreach($groupedSessions['pl_wednesday'] as $session)
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-title">{{ $session->title }}</div>
                            <div class="session-meta">
                                @if($session->date)
                                    <i class="fas fa-calendar mr-1"></i>{{ $session->date->format('M j, Y') }}
                                @endif
                            </div>
                        </div>
                        <button class="remove-btn" onclick="toggleSession('pl_wednesday_session', {{ $session->id }})">
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- CCL Sessions -->
            @if(count($groupedSessions['ccl']) > 0)
            <div class="section-card">
                <div class="section-header">
                    <span><i class="fas fa-chalkboard-teacher mr-2"></i>Collaborative Community Learning Sessions</span>
                    <span class="bg-white text-tlc-navy px-3 py-1 rounded-full text-sm font-bold">
                        {{ count($groupedSessions['ccl']) }}
                    </span>
                </div>
                <div class="session-list">
                    @foreach($groupedSessions['ccl'] as $session)
                    <div class="session-item">
                        <div class="session-info">
                            <div class="session-title">{{ $session->title }}</div>
                            <div class="session-meta">
                                @if($session->date)
                                    <i class="fas fa-calendar mr-1"></i>{{ $session->date->format('M j, Y') }}
                                @endif
                                @if($session->presenter_name)
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-user mr-1"></i>{{ $session->presenter_name }}
                                @endif
                            </div>
                        </div>
                        <button class="remove-btn" onclick="toggleSession('ccl_session', {{ $session->id }})">
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleSession(type, id) {
    fetch('{{ route('my-pl.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            selectable_type: type,
            selectable_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'removed' || data.status === 'added') {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endpush
@endsection
