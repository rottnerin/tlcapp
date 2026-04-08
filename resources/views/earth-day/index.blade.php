@extends('layouts.user')

@section('title', 'Earth Day Mini-PL Workshops')

@section('content')
<style>
:root {
    --ed-green:       #2d6a4f;
    --ed-green-light: #52b788;
    --ed-green-dark:  #1b4332;
    --ed-cream:       #f0faf4;
}

.ed-header {
    background: linear-gradient(135deg, var(--ed-green-dark) 0%, var(--ed-green) 60%, #40916c 100%);
    color: white;
    padding: 2rem 1.5rem 1.75rem;
}

.ed-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.ed-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(27, 67, 50, 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

@media (hover: hover) {
    .ed-card:not(.greyed-out):hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(27, 67, 50, 0.14);
    }
}

.ed-card-header {
    background: linear-gradient(135deg, var(--ed-green) 0%, var(--ed-green-dark) 100%);
    color: white;
    padding: 1.25rem;
}

.ed-card-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.375rem;
    line-height: 1.35;
}

.ed-card-body {
    padding: 1.1rem 1.25rem 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.ed-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #4b5563;
}

.ed-meta i {
    width: 1rem;
    color: var(--ed-green);
    flex-shrink: 0;
}

.ed-description {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.5;
    margin-top: 0.25rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

/* Fill bar */
.fill-bar-wrap {
    margin-top: auto;
    padding-top: 0.75rem;
}

.fill-bar-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 0.3rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.fill-bar-track {
    height: 8px;
    background: #e5e7eb;
    border-radius: 99px;
    overflow: hidden;
}

.fill-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 0.4s ease;
}

.fill-bar-fill[data-level="low"]  { background: var(--ed-green-light); }
.fill-bar-fill[data-level="high"] { background: #f4a261; }
.fill-bar-fill[data-level="full"] { background: #e63946; }

.full-pill {
    font-size: 0.7rem;
    font-weight: 700;
    background: #fee2e2;
    color: #b91c1c;
    padding: 0.15rem 0.5rem;
    border-radius: 99px;
    letter-spacing: 0.04em;
}

/* Buttons */
.btn-join {
    display: block;
    width: 100%;
    margin-top: 0.875rem;
    padding: 0.625rem 1rem;
    background: var(--ed-green);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    border-radius: 0.5rem;
    border: none;
    cursor: pointer;
    transition: background 0.2s ease;
}

@media (hover: hover) {
    .btn-join:hover { background: var(--ed-green-dark); }
}

.btn-calendar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.4rem;
    width: 100%;
    margin-top: 0.875rem;
    padding: 0.625rem 1rem;
    background: white;
    color: var(--ed-green);
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    border-radius: 0.5rem;
    border: 2px solid var(--ed-green);
    text-decoration: none;
    transition: all 0.2s ease;
}

@media (hover: hover) {
    .btn-calendar:hover {
        background: var(--ed-green);
        color: white;
    }
}

.btn-disabled {
    display: block;
    width: 100%;
    margin-top: 0.875rem;
    padding: 0.625rem 1rem;
    background: #e5e7eb;
    color: #9ca3af;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    border-radius: 0.5rem;
    border: none;
    cursor: not-allowed;
}

/* Greyed-out state */
.ed-card.greyed-out {
    filter: grayscale(0.55);
    opacity: 0.62;
    pointer-events: none;
}

/* Enrolled banner on card */
.enrolled-banner {
    background: var(--ed-green-dark);
    color: #d1fae5;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-align: center;
    padding: 0.3rem 0.75rem;
}
</style>

<div class="ed-header">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-2xl font-bold flex items-center gap-2">
                    🌍 Earth Day Mini-PL Workshops
                </h1>
                <p class="mt-1 text-green-100 text-sm">
                    Wednesday, April 22 &nbsp;·&nbsp; 3:40 – 4:25 PM &nbsp;·&nbsp; Choose one workshop to attend
                </p>
            </div>
            @if($userEnrollment)
            <div class="bg-white/15 rounded-lg px-4 py-2 text-sm font-medium text-white">
                ✓ Registered: <span class="font-bold">{{ $userEnrollment->workshop->title }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg font-medium text-sm">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg font-medium text-sm">
        {{ session('error') }}
    </div>
    @endif

    @if($workshops->isEmpty())
    <div class="text-center py-16 text-gray-500">
        <div class="text-5xl mb-3">🌱</div>
        <p class="font-medium">Workshops will be available soon. Check back later!</p>
    </div>
    @else
    <div class="ed-grid">
        @foreach($workshops as $workshop)
            @php
                $isEnrolledHere  = $userEnrollment && $userEnrollment->earth_day_workshop_id === $workshop->id;
                $hasEnrolled     = $userEnrollment !== null;
                $greyedOut       = $hasEnrolled && !$isEnrolledHere;
                $level           = $workshop->fill_percentage >= 100 ? 'full' : ($workshop->fill_percentage >= 67 ? 'high' : 'low');
            @endphp

            <div class="ed-card {{ $greyedOut ? 'greyed-out' : '' }}">

                @if($isEnrolledHere)
                <div class="enrolled-banner">✓ YOUR WORKSHOP</div>
                @endif

                <div class="ed-card-header">
                    <h3>{{ $workshop->title }}</h3>
                    @if($workshop->presenter)
                    <p class="text-green-100 text-sm">{{ $workshop->presenter }}</p>
                    @endif
                </div>

                <div class="ed-card-body">

                    @if($workshop->location)
                    <div class="ed-meta">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $workshop->location }}</span>
                    </div>
                    @endif

                    <div class="ed-meta">
                        <i class="fas fa-clock"></i>
                        <span>{{ \Carbon\Carbon::parse($workshop->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($workshop->end_time)->format('g:i A') }}</span>
                    </div>

                    @if($workshop->description)
                    <p class="ed-description">{{ $workshop->description }}</p>
                    @endif

                    {{-- Fill bar --}}
                    <div class="fill-bar-wrap">
                        <div class="fill-bar-label">
                            <span>Availability</span>
                            @if($workshop->isFull())
                            <span class="full-pill">Full</span>
                            @endif
                        </div>
                        <div class="fill-bar-track">
                            <div class="fill-bar-fill" style="width: {{ $workshop->fill_percentage }}%" data-level="{{ $level }}"></div>
                        </div>
                    </div>

                    {{-- Action --}}
                    @if($isEnrolledHere)
                        <a href="{{ $workshop->google_calendar_url }}" target="_blank" rel="noopener" class="btn-calendar">
                            <i class="fas fa-calendar-plus"></i> Add to Google Calendar
                        </a>
                    @elseif($hasEnrolled || $workshop->isFull())
                        <button class="btn-disabled" disabled>
                            {{ $workshop->isFull() ? 'Full' : 'Join Workshop' }}
                        </button>
                    @else
                        <form method="POST" action="{{ route('earth-day.enroll', $workshop) }}">
                            @csrf
                            <button type="submit" class="btn-join">Join Workshop</button>
                        </form>
                    @endif

                </div>
            </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
