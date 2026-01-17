@extends('layouts.user')

@section('title', 'Professional Learning Sessions')

@push('styles')
<style>
    /* TLC Color Palette */
    :root {
        --tlc-navy: #0d3b66;
        --tlc-cream: #faf0ca;
        --tlc-gold: #f4d35e;
        --tlc-orange: #ee964b;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 50%, var(--tlc-navy) 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Floating bubbles animation */
    .bubble {
        position: absolute;
        border-radius: 50%;
        opacity: 0.1;
        animation: float 20s infinite;
    }

    .bubble:nth-child(1) {
        width: 80px;
        height: 80px;
        left: 10%;
        top: 20%;
        background: white;
        animation-delay: 0s;
    }

    .bubble:nth-child(2) {
        width: 120px;
        height: 120px;
        right: 15%;
        top: 30%;
        background: white;
        animation-delay: 2s;
    }

    .bubble:nth-child(3) {
        width: 60px;
        height: 60px;
        left: 30%;
        bottom: 20%;
        background: white;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(5deg); }
        66% { transform: translateY(10px) rotate(-3deg); }
    }

    /* Dashboard Container */
    .dashboard-container {
        background: var(--tlc-cream);
        min-height: calc(100vh - 64px);
    }

    /* Date Section */
    .date-section {
        margin-bottom: 2.5rem;
    }

    .date-header {
        background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 1rem 1rem 0 0;
        font-weight: 600;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        box-shadow: 0 4px 15px rgba(13, 59, 102, 0.3);
    }

    .date-header svg {
        width: 1.5rem;
        height: 1.5rem;
        opacity: 0.9;
    }

    .date-body {
        background: white;
        border-radius: 0 0 1rem 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    /* Session Cards */
    .session-card {
        border-radius: 1rem;
        padding: 1.5rem;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .session-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 1rem 1rem 0 0;
    }

    .session-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    /* TLC Card Variants */
    .card-lavender {
        background: linear-gradient(145deg, rgba(250, 240, 202, 0.5) 0%, rgba(244, 211, 94, 0.2) 100%);
        border: 2px solid rgba(244, 211, 94, 0.4);
    }
    .card-lavender::before { background: linear-gradient(90deg, var(--tlc-gold), var(--tlc-orange)); }
    .card-lavender:hover { border-color: var(--tlc-gold); }

    .card-mint {
        background: linear-gradient(145deg, rgba(250, 240, 202, 0.5) 0%, rgba(238, 150, 75, 0.15) 100%);
        border: 2px solid rgba(238, 150, 75, 0.3);
    }
    .card-mint::before { background: linear-gradient(90deg, var(--tlc-orange), var(--tlc-gold)); }
    .card-mint:hover { border-color: var(--tlc-orange); }

    .card-peach {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.9) 0%, rgba(244, 211, 94, 0.2) 100%);
        border: 2px solid rgba(244, 211, 94, 0.4);
    }
    .card-peach::before { background: linear-gradient(90deg, var(--tlc-navy), #164773); }
    .card-peach:hover { border-color: var(--tlc-gold); }

    .card-sky {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.9) 0%, rgba(13, 59, 102, 0.08) 100%);
        border: 2px solid rgba(13, 59, 102, 0.15);
    }
    .card-sky::before { background: linear-gradient(90deg, var(--tlc-navy), var(--tlc-orange)); }
    .card-sky:hover { border-color: var(--tlc-navy); }

    .card-rose {
        background: linear-gradient(145deg, rgba(250, 240, 202, 0.6) 0%, rgba(238, 150, 75, 0.2) 100%);
        border: 2px solid rgba(238, 150, 75, 0.4);
    }
    .card-rose::before { background: linear-gradient(90deg, var(--tlc-orange), var(--tlc-navy)); }
    .card-rose:hover { border-color: var(--tlc-orange); }

    .card-sage {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.95) 0%, rgba(244, 211, 94, 0.15) 100%);
        border: 2px solid rgba(13, 59, 102, 0.2);
    }
    .card-sage::before { background: linear-gradient(90deg, var(--tlc-gold), var(--tlc-navy)); }
    .card-sage:hover { border-color: var(--tlc-gold); }

    /* Card Title */
    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--tlc-navy);
        margin-bottom: 0.75rem;
        line-height: 1.4;
        transition: color 0.3s ease;
    }

    .session-card:hover .card-title {
        color: var(--tlc-orange);
    }

    /* Card Description */
    .card-description {
        color: #4a5568;
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Time Badge */
    .time-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--tlc-gold) 0%, var(--tlc-orange) 100%);
        color: var(--tlc-navy);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 4px 12px rgba(244, 211, 94, 0.3);
    }

    .time-badge svg {
        width: 1rem;
        height: 1rem;
    }

    /* Location Badge */
    .location-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        color: #64748b;
        font-size: 0.875rem;
        background: rgba(100, 116, 139, 0.1);
        padding: 0.375rem 0.75rem;
        border-radius: 1rem;
    }

    .location-badge svg {
        width: 0.875rem;
        height: 0.875rem;
    }

    /* Division Badge */
    .division-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .division-es {
        background: rgba(244, 211, 94, 0.3);
        color: var(--tlc-navy);
    }

    .division-ms {
        background: rgba(238, 150, 75, 0.3);
        color: var(--tlc-navy);
    }

    .division-hs {
        background: rgba(13, 59, 102, 0.15);
        color: var(--tlc-navy);
    }

    /* View Details Button */
    .view-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, var(--tlc-orange) 0%, #d97706 100%);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(238, 150, 75, 0.3);
        margin-top: auto;
    }

    .view-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(238, 150, 75, 0.4);
        background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
        color: white;
    }

    .view-btn svg {
        width: 1rem;
        height: 1rem;
        transition: transform 0.3s ease;
    }

    .view-btn:hover svg {
        transform: translateX(4px);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(145deg, rgba(250, 240, 202, 0.5) 0%, rgba(244, 211, 94, 0.2) 100%);
        border-radius: 1.5rem;
        border: 2px dashed var(--tlc-gold);
    }

    .empty-state-icon {
        width: 5rem;
        height: 5rem;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(13, 59, 102, 0.3);
    }

    .empty-state-icon svg {
        width: 2.5rem;
        height: 2.5rem;
        color: white;
    }

    /* Link Count Badge */
    .link-count {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: rgba(238, 150, 75, 0.15);
        color: var(--tlc-orange);
        padding: 0.25rem 0.625rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    /* Responsive Grid */
    .sessions-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .sessions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .sessions-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Inactive Warning */
    .inactive-banner {
        background: linear-gradient(135deg, rgba(244, 211, 94, 0.3) 0%, rgba(244, 211, 94, 0.5) 100%);
        border: 2px solid var(--tlc-gold);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
    }

    .inactive-banner svg {
        width: 3rem;
        height: 3rem;
        color: var(--tlc-orange);
        margin-bottom: 0.75rem;
    }

    /* Animation for cards appearing */
    .session-card {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .sessions-grid > div:nth-child(1) .session-card { animation-delay: 0.1s; }
    .sessions-grid > div:nth-child(2) .session-card { animation-delay: 0.2s; }
    .sessions-grid > div:nth-child(3) .session-card { animation-delay: 0.3s; }
    .sessions-grid > div:nth-child(4) .session-card { animation-delay: 0.4s; }
    .sessions-grid > div:nth-child(5) .session-card { animation-delay: 0.5s; }
    .sessions-grid > div:nth-child(6) .session-card { animation-delay: 0.6s; }

    /* Add to My PL Button Styling */
    .add-to-my-pl-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem !important;
        border-radius: 2rem;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(244, 211, 94, 0.3);
        cursor: pointer;
        border: none;
    }

    .add-to-my-pl-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(244, 211, 94, 0.4);
    }

    .add-to-my-pl-btn i {
        font-size: 0.875rem;
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
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--tlc-navy);
        white-space: nowrap;
    }

    .my-pl-checkbox:checked + .my-pl-checkbox-text {
        color: var(--tlc-orange);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section py-12 md:py-16 relative">
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">
                Professional Learning
            </h1>
            <p class="text-xl text-white/90 max-w-2xl mx-auto">
                Explore engaging sessions designed to enhance your professional growth
            </p>
        </div>
    </div>
</div>

<div class="dashboard-container py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(!$settings || !$settings->is_active)
            <!-- Inactive State -->
            <div class="inactive-banner">
                <svg class="mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-amber-800 mb-2">Sessions Currently Unavailable</h3>
                <p class="text-amber-700">Professional Learning sessions are not active at this time. Please check back soon!</p>
            </div>
        @elseif($groupedSessions->isEmpty())
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">No Sessions Yet</h3>
                <p class="text-gray-600 max-w-md mx-auto">We're preparing exciting learning opportunities for you. Check back soon to discover upcoming sessions!</p>
            </div>
        @else
            <!-- Sessions by Date -->
            @php
                $cardColors = ['lavender', 'mint', 'peach', 'sky', 'rose', 'sage'];
            @endphp
            
            @foreach($groupedSessions as $date => $sessions)
                @php
                    $dateObj = \Carbon\Carbon::parse($date);
                @endphp
                
                <div class="date-section">
                    <div class="date-header">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $dateObj->format('l, F j, Y') }}</span>
                        @if($dateObj->isToday())
                            <span class="ml-auto bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full">TODAY</span>
                        @endif
                    </div>
                    
                    <div class="date-body">
                        <div class="sessions-grid">
                            @foreach($sessions as $index => $session)
                                @php
                                    $colorIndex = $index % count($cardColors);
                                    $cardColor = $cardColors[$colorIndex];
                                @endphp
                                
                                <div>
                                    <div class="session-card card-{{ $cardColor }}">
                                        <div class="flex flex-wrap items-start gap-2 mb-3">
                                            @if($session->division)
                                                <span class="division-badge division-{{ strtolower($session->division->name) }}">
                                                    {{ $session->division->name }}
                                                </span>
                                            @endif
                                            @if($session->links->count() > 0)
                                                <span class="link-count">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                    {{ $session->links->count() }} {{ Str::plural('resource', $session->links->count()) }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <h3 class="card-title">
                                            {{ $session->title }}
                                        </h3>
                                        
                                        @if($session->description)
                                            <p class="card-description">
                                                {{ $session->description }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex flex-wrap items-center gap-3 mb-4">
                                            <span class="time-badge">
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $session->formatted_time }}
                                            </span>
                                            
                                            @if($session->location)
                                                <span class="location-badge">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $session->location }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('pl-wednesday.show', $session) }}" class="view-btn">
                                                View Details
                                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                                </svg>
                                            </a>
                                            <!-- Add to My PL Checkbox -->
                                            <label class="my-pl-checkbox-label" onclick="event.stopPropagation();">
                                                <input type="checkbox" 
                                                       class="my-pl-checkbox" 
                                                       {{ auth()->user()->hasSelected($session) ? 'checked' : '' }}
                                                       onchange="toggleMyPL('pl_wednesday_session', {{ $session->id }}, this)">
                                                <span class="my-pl-checkbox-text">Add to My PL</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleMyPL(type, id, checkbox) {
    fetch('{{ route('my-pl.toggle') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            selectable_type: type,
            selectable_id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'added') {
            checkbox.checked = true;
        } else if (data.status === 'removed') {
            checkbox.checked = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        checkbox.checked = !checkbox.checked; // Revert on error
    });
}
</script>
@endpush
@endsection
