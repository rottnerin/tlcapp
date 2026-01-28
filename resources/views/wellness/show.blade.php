@extends('layouts.user')

@section('title', $session->title . ' - Wellness')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
    --wellness-teal: #004643;
    --wellness-teal-dark: #004643;
    --wellness-teal-light: #006B66;
}

.session-detail-card {
    background: white;
    border-radius: 1.5rem;
    box-shadow: 0 8px 32px rgba(0, 70, 67, 0.1);
    overflow: hidden;
}

.session-header {
    background: linear-gradient(135deg, var(--wellness-teal) 0%, #005A56 100%);
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

.capacity-card {
    background: rgba(0, 107, 102, 0.15);
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid rgba(0, 70, 67, 0.3);
}

.capacity-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--wellness-teal);
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
    background: linear-gradient(135deg, var(--wellness-teal) 0%, #005A56 100%);
    color: white;
    border: 2px solid var(--wellness-teal);
    box-shadow: 0 2px 8px rgba(0, 70, 67, 0.25), 0 1px 3px rgba(0, 70, 67, 0.15);
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, #005A56 0%, var(--wellness-teal) 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 70, 67, 0.4), 0 2px 6px rgba(0, 70, 67, 0.3);
}

.btn-primary:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

.btn-secondary {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
}

.btn-secondary:hover {
    background: var(--tlc-orange);
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
    color: var(--wellness-teal);
}

.category-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    background: rgba(0, 107, 102, 0.15);
    color: var(--wellness-teal);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
}

.participants-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.participant-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 0.5rem;
}

.participant-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    object-fit: cover;
}

.participant-initial {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: rgba(0, 107, 102, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: var(--wellness-teal);
}
</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="/spring-pl-days/wellness" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to Wellness Sessions
        </a>

        <div class="session-detail-card">
            <div class="session-header">
                <h1>{{ $session->title }}</h1>
                <div class="session-meta">
                    @if($session->date)
                    <div class="session-meta-item">
                        <i class="fas fa-calendar"></i>
                        {{ $session->date->format('l, F j, Y') }}
                    </div>
                    @endif
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

                <!-- Capacity Info -->
                <div class="capacity-card">
                    <div class="section-title" style="margin-bottom: 0.5rem;">
                        <i class="fas fa-users"></i>
                        Capacity
                    </div>
                    <div class="capacity-number">{{ $session->current_enrollment ?? 0 }} / {{ $session->max_participants ?? 'Unlimited' }}</div>
                    @if($session->isAvailableForEnrollment())
                        <div class="text-sm mt-1" style="color: var(--wellness-teal);">
                            {{ $session->available_spots }} spots available
                        </div>
                    @else
                        <div class="text-sm mt-1" style="color: #dc2626;">
                            Session is full
                        </div>
                    @endif
                </div>

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

                <!-- Categories -->
                @if($session->category && is_array($session->category) && count($session->category) > 0)
                <div class="section-title">
                    <i class="fas fa-tags"></i>
                    Categories
                </div>
                <div class="mb-4">
                    @foreach($session->category as $category)
                    <span class="category-tag">
                        @switch($category)
                            @case('The Arts (Visual or Performing)')
                                🎨 {{ $category }}
                                @break
                            @case('Sports and Exercise')
                                🏃 {{ $category }}
                                @break
                            @case('Dance and Movement')
                                💃 {{ $category }}
                                @break
                            @case('Language and Culture')
                                🌍 {{ $category }}
                                @break
                            @case('Crafts')
                                🎨 {{ $category }}
                                @break
                            @case('Yoga / Meditation')
                                🧘 {{ $category }}
                                @break
                            @case('A general opportunity for joy and connection')
                                😊 {{ $category }}
                                @break
                            @case('Health and Well-being')
                                💚 {{ $category }}
                                @break
                            @case('Other')
                                🔧 {{ $category }}
                                @break
                            @default
                                🌿 {{ $category }}
                        @endswitch
                    </span>
                    @endforeach
                </div>
                @endif

                <!-- Equipment and Requirements -->
                @if($session->equipment_needed)
                <div class="section-title">
                    <i class="fas fa-toolbox"></i>
                    Equipment Needed
                </div>
                <div class="description" style="margin-bottom: 1.5rem;">{{ $session->equipment_needed }}</div>
                @endif

                @if($session->special_requirements)
                <div class="section-title">
                    <i class="fas fa-exclamation-circle"></i>
                    Special Requirements
                </div>
                <div class="description" style="margin-bottom: 1.5rem;">{{ $session->special_requirements }}</div>
                @endif

                @if($session->preparation_notes)
                <div class="section-title">
                    <i class="fas fa-sticky-note"></i>
                    Preparation Notes
                </div>
                <div class="description" style="margin-bottom: 1.5rem;">{{ $session->preparation_notes }}</div>
                @endif

                <!-- Action Buttons -->
                <div class="action-buttons">
                    @if($session->google_calendar_url)
                    <a href="{{ $session->google_calendar_url }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-calendar-plus"></i>
                        Add to Calendar
                    </a>
                    @endif
                    @if($userEnrollment)
                        <button class="btn btn-primary" disabled style="background: #004643; color: white; cursor: not-allowed; border-color: #005A56;">
                            <i class="fas fa-check mr-1"></i>Enrolled
                        </button>
                    @elseif($session->isAvailableForEnrollment())
                        <form action="{{ route('wellness.enroll', $session) }}" method="POST" style="display: inline;" id="enroll-form-{{ $session->id }}">
                            @csrf
                            <button type="button" 
                                    onclick="confirmEnrollment({{ $session->id }}, '{{ $session->title }}')"
                                    class="btn btn-primary">
                                <i class="fas fa-user-plus mr-1"></i>Enroll Now
                            </button>
                        </form>
                    @else
                        <button class="btn btn-primary" disabled style="background: #ef4444; color: white; cursor: not-allowed; border-color: #dc2626;">
                            <i class="fas fa-times mr-1"></i>Session Full
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmEnrollment(sessionId, sessionTitle) {
    const message = `Are you sure you want to enroll in "${sessionTitle}"?`;
    
    if (confirm(message)) {
        document.getElementById('enroll-form-' + sessionId).submit();
    }
}
</script>

@endsection
