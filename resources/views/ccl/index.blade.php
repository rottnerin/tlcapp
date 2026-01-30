@extends('layouts.user')

@section('title', 'Collaborative Community Learning Sessions - TLC Professional Learning')

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

.ttt-card.joined {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: 4px solid #f59e0b;
    box-shadow: 0 8px 32px rgba(245, 158, 11, 0.45), 0 0 0 3px rgba(245, 158, 11, 0.25);
    transform: scale(1.02);
    position: relative;
}

.ttt-card.joined::before {
    content: '✓ JOINED';
    position: absolute;
    top: 12px;
    right: 12px;
    background: white;
    color: #d97706;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 800;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10;
    letter-spacing: 1px;
}

.admin-unjoin-btn-ccl {
    position: absolute;
    top: 56px;
    right: 12px;
    background: #dc2626;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.75rem;
    border: none;
    cursor: pointer;
    z-index: 10;
    box-shadow: 0 2px 6px rgba(220, 38, 38, 0.3);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.admin-unjoin-btn-ccl:hover {
    background: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.4);
}

.ttt-card.joined .ttt-card-header {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
}

.ttt-card.joined .ttt-card-body {
    background: white;
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
    min-height: 3.75rem;
    display: flex;
    align-items: center;
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

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
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
        @php
            // Group sessions by date and time slot
            $groupedSessions = $sessions->groupBy(function($session) {
                return $session->date->format('Y-m-d') . '_' . $session->start_time->format('H:i');
            })->sortKeys();
            
            $sessionNumber = 1;
        @endphp
        
        @foreach($groupedSessions as $key => $slotSessions)
        @php
            $firstSession = $slotSessions->first();
            $sessionDate = $firstSession->date;
            $startTime = $firstSession->start_time;
            $endTime = $firstSession->end_time;
        @endphp
        
        <!-- CCL Session {{ $sessionNumber }} Header -->
        <div class="mb-4 mt-{{ $sessionNumber > 1 ? '10' : '0' }}">
            <div class="session-header-card rounded-2xl shadow-lg overflow-hidden" style="border: 3px solid #ee964b;">
                <div class="px-6 py-5" style="background: linear-gradient(135deg, #7c2d12 0%, #9a3412 100%);">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #ee964b, #f97316);">
                                <span class="font-black text-2xl" style="color: white;">{{ $sessionNumber }}</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-black tracking-wide" style="color: #fed7aa; text-shadow: 1px 1px 2px rgba(0,0,0,0.3);">
                                    CCL SESSION {{ $sessionNumber }}
                                </h2>
                                <p class="text-base font-semibold mt-1" style="color: white;">
                                    <i class="fas fa-calendar-alt mr-2"></i>{{ $sessionDate->format('l, F j, Y') }}
                                </p>
                                <p class="text-sm font-medium" style="color: rgba(255,255,255,0.85);">
                                    <i class="fas fa-clock mr-2"></i>{{ $startTime->format('g:i A') }} - {{ $endTime->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="px-4 py-2 rounded-full text-sm font-bold" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            @if($sessionNumber === 1)
                                Choose 1
                            @else
                                {{ $slotSessions->count() }} {{ Str::plural('option', $slotSessions->count()) }} available
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="ttt-grid mb-6">
            @foreach($slotSessions as $session)
            @php
                $isEnrolled = isset($userEnrollments[$session->id]) && $userEnrollments[$session->id];
                // Find corresponding schedule item to check capacity
                // Match by title, session_type, date, and start_time (as datetime)
                $scheduleItemStartTime = $session->date->format('Y-m-d') . ' ' . $session->start_time->format('H:i:s');
                $scheduleItem = \App\Models\ScheduleItem::where('p_d_day_id', $session->p_d_day_id)
                    ->where('title', $session->title)
                    ->where('session_type', 'ccl')
                    ->where('date', $session->date->format('Y-m-d'))
                    ->where('start_time', $scheduleItemStartTime)
                    ->first();
                $currentEnrollment = $scheduleItem ? $scheduleItem->current_enrollment : 0;
                $maxParticipants = $scheduleItem ? $scheduleItem->max_participants : null;
                $isFull = $scheduleItem && $maxParticipants !== null && $currentEnrollment >= $maxParticipants;
                $enrollmentPercentage = ($maxParticipants && $maxParticipants > 0) ? min(100, ($currentEnrollment / $maxParticipants) * 100) : 0;
            @endphp
            <div class="ttt-card {{ $isEnrolled ? 'joined' : '' }}">
                @if($isEnrolled && auth()->user()->isAdmin())
                    <form action="{{ route('spring.ccl.unjoin', $session) }}" method="POST" style="display: inline;" onsubmit="return confirm('Leave this session?');">
                        @csrf
                        <button type="submit" class="admin-unjoin-btn-ccl">
                            <i class="fas fa-times"></i> Unjoin
                        </button>
                    </form>
                @endif
                <div class="ttt-card-header">
                    <div class="flex items-center justify-between">
                        <h3 class="flex-1">{{ $session->title }}</h3>
                    </div>
                    <!-- Enrollment Badge -->
                    <div class="mt-2 flex items-center gap-2">
                        <div class="flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-semibold" 
                             style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-users"></i>
                            @if($maxParticipants)
                                <span>{{ $currentEnrollment }}/{{ $maxParticipants }} Enrolled</span>
                            @else
                                <span>{{ $currentEnrollment }} Enrolled</span>
                            @endif
                        </div>
                        @if($isFull)
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">FULL</span>
                        @elseif($maxParticipants && $enrollmentPercentage >= 75)
                            <span class="px-2 py-1 rounded-full text-xs font-bold bg-yellow-500 text-white">FILLING UP</span>
                        @endif
                    </div>
                    @if($maxParticipants)
                    <!-- Progress Bar -->
                    <div class="mt-2 w-full bg-white/20 rounded-full h-2 overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-300" 
                             style="width: {{ $enrollmentPercentage }}%; background: {{ $isFull ? '#ef4444' : ($enrollmentPercentage >= 75 ? '#f59e0b' : '#10b981') }};"></div>
                    </div>
                    @endif
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
                </div>
                <div class="ttt-card-footer">
                    <a href="{{ route('spring.ccl.show', $session) }}" class="view-btn">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </a>
                    @if($isEnrolled)
                        <button class="join-btn" disabled style="background: #f59e0b; color: white; cursor: not-allowed;">
                            <i class="fas fa-check mr-1"></i>Enrolled
                        </button>
                    @elseif($isFull)
                        <button class="join-btn" disabled style="background: #ef4444; color: white; cursor: not-allowed;">
                            <i class="fas fa-times mr-1"></i>Full
                        </button>
                    @else
                        <form action="{{ route('spring.ccl.join', $session) }}" method="POST" style="display: inline;">
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
        
        @php $sessionNumber++; @endphp
        @endforeach
        @else
        <div class="empty-state">
            <div class="text-5xl mb-4">👨‍🏫</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No CCL Sessions Available</h3>
            <p class="text-gray-500">Collaborative Community Learning Sessions sessions will be posted here when available.</p>
        </div>
        @endif
    </div>
</div>

@endsection
