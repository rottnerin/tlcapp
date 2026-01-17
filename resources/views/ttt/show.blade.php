@extends('layouts.user')

@section('title', $session->title . ' - TTT')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
}

.session-detail-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px rgba(13, 59, 102, 0.1);
    overflow: hidden;
}

.session-header {
    background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
    color: white;
    padding: 2rem;
}

.session-header h1 {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.session-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    font-size: 0.9375rem;
}

.session-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.session-body {
    padding: 2rem;
}

.section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tlc-navy);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.description {
    color: #4b5563;
    line-height: 1.7;
    margin-bottom: 2rem;
}

.presenter-card {
    background: #f9fafb;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.presenter-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--tlc-navy);
    margin-bottom: 0.5rem;
}

.presenter-bio {
    color: #6b7280;
    font-size: 0.9375rem;
    line-height: 1.6;
}

.links-section {
    margin-top: 2rem;
}

.link-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 0.75rem;
    margin-bottom: 0.75rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.link-card:hover {
    background: var(--tlc-gold);
}

.link-icon {
    width: 2.5rem;
    height: 2.5rem;
    background: var(--tlc-navy);
    color: white;
    border-radius: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.link-title {
    font-weight: 600;
    color: var(--tlc-navy);
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--tlc-orange) 0%, #d97706 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(238, 150, 75, 0.4);
}

.btn-secondary {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
}

.btn-secondary:hover {
    background: var(--tlc-orange);
    color: white;
}

.btn-secondary.added {
    background: #10b981;
    color: white;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--tlc-navy);
    text-decoration: none;
    font-weight: 500;
    margin-bottom: 1.5rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: var(--tlc-orange);
}

/* My PL Checkbox Styling */
.my-pl-checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    user-select: none;
    z-index: 10;
    position: relative;
}

.my-pl-checkbox {
    width: 1.25rem;
    height: 1.25rem;
    cursor: pointer;
    accent-color: var(--tlc-orange);
    flex-shrink: 0;
    background-color: white;
}

.my-pl-checkbox-text {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--tlc-navy);
    white-space: nowrap;
}

.my-pl-checkbox:checked + .my-pl-checkbox-text {
    color: var(--tlc-orange);
}
</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('spring.ttt') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to TTT Sessions
        </a>

        <div class="session-detail-card">
            <div class="session-header">
                <h1>{{ $session->title }}</h1>
                <div class="session-meta">
                    <div class="session-meta-item">
                        <i class="fas fa-calendar"></i>
                        {{ $session->date->format('l, F j, Y') }}
                    </div>
                    @if($session->start_time && $session->end_time)
                    <div class="session-meta-item">
                        <i class="fas fa-clock"></i>
                        {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}
                    </div>
                    @endif
                    @if($session->location)
                    <div class="session-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $session->location }}
                    </div>
                    @endif
                    @if($session->contact_hours)
                    <div class="session-meta-item">
                        <i class="fas fa-hourglass-half"></i>
                        {{ $session->contact_hours }} Contact Hours
                    </div>
                    @endif
                </div>
            </div>

            <div class="session-body">
                @if($session->description)
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    About This Session
                </div>
                <div class="description">{{ $session->description }}</div>
                @endif

                <!-- Presenter Info -->
                <div class="section-title">
                    <i class="fas fa-user-tie"></i>
                    Presenter
                </div>
                <div class="presenter-card">
                    <div class="presenter-name">{{ $session->presenter_name }}</div>
                    @if($session->presenter_bio)
                    <div class="presenter-bio">{{ $session->presenter_bio }}</div>
                    @endif
                </div>

                @if($session->co_presenter_name)
                <div class="section-title">
                    <i class="fas fa-user-friends"></i>
                    Co-Presenter
                </div>
                <div class="presenter-card">
                    <div class="presenter-name">{{ $session->co_presenter_name }}</div>
                </div>
                @endif

                <!-- Links -->
                @if($session->links->count() > 0)
                <div class="links-section">
                    <div class="section-title">
                        <i class="fas fa-link"></i>
                        Resources
                    </div>
                    @foreach($session->links as $link)
                    <a href="{{ $link->formatted_url }}" target="_blank" class="link-card">
                        <div class="link-icon">
                            @switch($link->type)
                                @case('video')
                                    <i class="fas fa-video"></i>
                                    @break
                                @case('document')
                                    <i class="fas fa-file-alt"></i>
                                    @break
                                @default
                                    <i class="fas fa-external-link-alt"></i>
                            @endswitch
                        </div>
                        <div>
                            <div class="link-title">{{ $link->title }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($link->type) }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($session->google_calendar_url)
                    <a href="{{ $session->google_calendar_url }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-calendar-plus"></i>
                        Add to Calendar
                    </a>
                    @endif
                    @php
                        // Find corresponding schedule item to check capacity
                        $scheduleItem = \App\Models\ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                            ->where('date', $session->date)
                            ->where('start_time', $session->start_time)
                            ->where('title', $session->title)
                            ->where('session_type', 'ttt')
                            ->first();
                        $isFull = $scheduleItem && $scheduleItem->max_participants !== null && $scheduleItem->current_enrollment >= $scheduleItem->max_participants;
                    @endphp
                    @if($userEnrollment)
                        <button class="btn btn-success" disabled style="background: #10b981; color: white; cursor: not-allowed;">
                            <i class="fas fa-check mr-1"></i>Joined
                        </button>
                    @elseif($isFull)
                        <button class="btn btn-danger" disabled style="background: #ef4444; color: white; cursor: not-allowed;">
                            <i class="fas fa-times mr-1"></i>Full
                        </button>
                    @else
                        <form action="{{ route('spring.ttt.join', $session) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus mr-1"></i>Join Session
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
