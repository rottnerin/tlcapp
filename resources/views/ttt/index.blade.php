@extends('layouts.user')

@section('title', 'Teachers Teaching Teachers - TLC Professional Learning')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
}

.section-header {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
    padding: 0.75rem;
    border-radius: 0.75rem;
    font-weight: 600;
}

.ttt-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.ttt-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.ttt-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(13, 59, 102, 0.12);
}

.ttt-card-header {
    background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
    color: white;
    padding: 1.25rem;
    flex-shrink: 0;
}

.ttt-card-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.ttt-card-header .meta {
    font-size: 0.875rem;
    opacity: 0.9;
    color: white;
}

.ttt-card-body {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.ttt-card-body .description {
    color: #4b5563;
    font-size: 0.9375rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.ttt-card-body .presenter {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.ttt-card-footer {
    padding: 1rem 1.25rem;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
    margin-top: auto;
}

.view-btn {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, var(--tlc-orange) 0%, #d97706 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
}

.view-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(238, 150, 75, 0.3);
}

.join-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--tlc-gold) 0%, #f4d35e 100%);
    color: var(--tlc-navy);
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.join-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--tlc-orange) 0%, #d97706 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(238, 150, 75, 0.3);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.08);
}

</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
                    <div class="text-2xl mr-3">👨‍🏫</div>
                    <div class="text-center">
                        <h1 class="text-xl font-bold">{{ $settings->title }}</h1>
                        @if($settings->description)
                        <p class="mt-1 text-sm opacity-90">{{ $settings->description }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($sessions->count() > 0)
        <div class="ttt-grid">
            @foreach($sessions as $session)
            <div class="ttt-card">
                <div class="ttt-card-header">
                    <h3>{{ $session->title }}</h3>
                </div>
                <div class="ttt-card-body">
                    @if($session->description)
                    <div class="description">{{ $session->description }}</div>
                    @endif
                    
                    <div class="presenter">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ $session->presenter_name }}</span>
                        @if($session->co_presenter_name)
                        <span class="text-gray-400">&</span>
                        <span>{{ $session->co_presenter_name }}</span>
                        @endif
                    </div>

                    @if($session->location)
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $session->location }}</span>
                    </div>
                    @endif

                    @if($session->division)
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $session->division->full_name }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="ttt-card-footer">
                    <a href="{{ route('spring.ttt.show', $session) }}" class="view-btn">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </a>
                    @php
                        $isEnrolled = isset($userEnrollments[$session->id]) && $userEnrollments[$session->id];
                        // Find corresponding schedule item to check capacity
                        $scheduleItem = \App\Models\ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                            ->where('date', $session->date)
                            ->where('start_time', $session->start_time)
                            ->where('title', $session->title)
                            ->where('session_type', 'ttt')
                            ->first();
                        $isFull = $scheduleItem && $scheduleItem->max_participants !== null && $scheduleItem->current_enrollment >= $scheduleItem->max_participants;
                    @endphp
                    @if($isEnrolled)
                        <button class="join-btn" disabled style="background: #10b981; color: white; cursor: not-allowed;">
                            <i class="fas fa-check mr-1"></i>Joined
                        </button>
                    @elseif($isFull)
                        <button class="join-btn" disabled style="background: #ef4444; color: white; cursor: not-allowed;">
                            <i class="fas fa-times mr-1"></i>Full
                        </button>
                    @else
                        <form action="{{ route('spring.ttt.join', $session) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="join-btn">
                                <i class="fas fa-user-plus mr-1"></i>Join Session
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="text-5xl mb-4">👨‍🏫</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No TTT Sessions Available</h3>
            <p class="text-gray-500">Teachers Teaching Teachers sessions will be posted here when available.</p>
        </div>
        @endif
    </div>
</div>

@endsection
