@extends('layouts.user')

@section('title', 'Wellness Sessions - TLC Professional Learning')

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

.section-header {
    background: var(--wellness-teal);
    color: white;
    padding: 0.75rem;
    border-radius: 0.75rem;
    font-weight: 600;
}

.wellness-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.wellness-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 70, 67, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.wellness-card.joined {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: 4px solid #10b981;
    box-shadow: 0 8px 32px rgba(16, 185, 129, 0.4), 0 0 0 3px rgba(16, 185, 129, 0.2);
    transform: scale(1.02);
    position: relative;
}

.wellness-card.joined::before {
    content: '✓ JOINED';
    position: absolute;
    top: 12px;
    right: 12px;
    background: white;
    color: #059669;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 800;
    font-size: 0.875rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    z-index: 10;
    letter-spacing: 1px;
}

.admin-unjoin-btn {
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

.admin-unjoin-btn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(220, 38, 38, 0.4);
}

.wellness-card.joined .wellness-card-header {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.wellness-card.joined .wellness-card-body {
    background: white;
}

@media (hover: hover) {
    .wellness-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 70, 67, 0.12);
    }
}

.wellness-card-header {
    background: linear-gradient(135deg, var(--wellness-teal) 0%, #005A56 100%);
    color: white;
    padding: 1.25rem;
    flex-shrink: 0;
}

.wellness-card-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
    min-height: 3.75rem;
    display: flex;
    align-items: center;
}

.wellness-card-header .meta {
    font-size: 0.875rem;
    opacity: 0.9;
    color: white;
}

.wellness-card-body {
    padding: 1.25rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.wellness-card-body .description {
    color: #4b5563;
    font-size: 0.9375rem;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.wellness-card-body .presenter {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.wellness-card-body .location {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.wellness-card-footer {
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
    background: linear-gradient(135deg, var(--wellness-teal) 0%, #005A56 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    font-size: 0.875rem;
    border: 2px solid var(--wellness-teal);
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 70, 67, 0.25), 0 1px 3px rgba(0, 70, 67, 0.15);
}

.join-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #005A56 0%, var(--wellness-teal) 100%);
    color: white;
    border-color: var(--wellness-teal);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 70, 67, 0.4), 0 2px 6px rgba(0, 70, 67, 0.3);
}

.join-btn:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(0, 70, 67, 0.08);
}

.category-tag {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    background: rgba(0, 107, 102, 0.15);
    color: var(--wellness-teal);
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
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
                        <h1 class="text-xl font-bold">Wellness Sessions</h1>
                        <p class="mt-1 text-sm opacity-90">Choose from a variety of wellness activities to enhance your professional learning experience</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border mb-8 p-6" style="border-color: rgba(0, 70, 67, 0.2);">
            <h3 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Filter Sessions</h3>
            <form method="GET" action="{{ route($routePrefix ?? 'wellness.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search sessions, presenters..."
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2"
                           style="border-color: rgba(0, 70, 67, 0.3); --tw-ring-color: var(--wellness-teal);">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Category</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2"
                            style="border-color: rgba(0, 70, 67, 0.3); --tw-ring-color: var(--wellness-teal);">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
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
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full text-white px-4 py-2 rounded-lg transition duration-200" class="btn-wellness-teal">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'category']))
                <div class="mt-4 pt-4" style="border-top: 1px solid rgba(0, 70, 67, 0.2);">
                    <a href="{{ route($routePrefix ?? 'wellness.index') }}"
                       class="text-sm font-medium" style="color: var(--wellness-teal);">
                        <i class="fas fa-times mr-1"></i>Clear all filters
                    </a>
                </div>
            @endif
        </div>

        <!-- Results Info -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="text-gray-600">
                    @if(request()->hasAny(['search', 'category']))
                        Showing {{ $sessions->count() }} of {{ $sessions->total() }} wellness sessions
                        @if(request('search'))
                            matching "{{ request('search') }}"
                        @endif
                        @if(request('category'))
                            in {{ request('category') }}
                        @endif
                    @else
                        Showing {{ $sessions->count() }} wellness sessions
                    @endif
                </div>
            </div>
        </div>

        <!-- Wellness Sessions Grid -->
        @if($sessions->count() > 0)
        <div class="wellness-grid">
            @foreach($sessions as $session)
            @php
                $isUserEnrolled = $userWellnessEnrollment && $userWellnessEnrollment->wellness_session_id === $session->id;
                $hasUserEnrollment = $userWellnessEnrollment !== null;
                $userEnrollment = $session->userSessions->firstWhere('user_id', auth()->id());
                $isEnrolled = $userEnrollment && $userEnrollment->status !== 'cancelled';

                // Admins can join multiple sessions for testing
                $isAdmin = auth()->user()->isAdmin();
                $showAlreadyEnrolled = $hasUserEnrollment && !$isUserEnrolled && !$isAdmin;

                // Enrollment/capacity info
                $currentEnrollment = $session->current_enrollment ?? 0;
                $maxParticipants = $session->max_participants;
                $isFull = $session->isFull();
                $enrollmentPercentage = ($maxParticipants && $maxParticipants > 0) ? min(100, ($currentEnrollment / $maxParticipants) * 100) : 0;
            @endphp
            <div class="wellness-card {{ $isEnrolled ? 'joined' : '' }}">
                @if($isEnrolled && $isAdmin)
                    <form action="{{ route(($routePrefix ?? 'wellness') . '.unjoin', $session) }}" method="POST" style="display: inline;" onsubmit="return confirm('Leave this session?');">
                        @csrf
                        <button type="submit" class="admin-unjoin-btn">
                            <i class="fas fa-times"></i> Unjoin
                        </button>
                    </form>
                @endif
                <div class="wellness-card-header">
                    <h3>{{ $session->title }}</h3>
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
                <div class="wellness-card-body">
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
                    <div class="location">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $session->location }}</span>
                    </div>
                    @endif

                    @if($session->category && is_array($session->category) && count($session->category) > 0)
                    <div class="mt-2">
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
                </div>
                <div class="wellness-card-footer">
                    <a href="{{ route(($routePrefix ?? 'wellness') . '.show', $session) }}" class="view-btn">
                        <i class="fas fa-eye mr-1"></i>View Details
                    </a>
                    @if($isEnrolled)
                        <button class="join-btn" disabled style="background: #10b981; color: white; cursor: not-allowed; border-color: #059669;">
                            <i class="fas fa-check mr-1"></i>Enrolled
                        </button>
                    @elseif($session->isFull())
                        <button class="join-btn" disabled style="background: #ef4444; color: white; cursor: not-allowed; border-color: #dc2626;">
                            <i class="fas fa-times mr-1"></i>Full
                        </button>
                    @elseif($showAlreadyEnrolled)
                        <button class="join-btn" disabled style="background: #6b7280; color: white; cursor: not-allowed; border-color: #4b5563;">
                            <i class="fas fa-info-circle mr-1"></i>Already Enrolled
                        </button>
                    @else
                        <form action="{{ route(($routePrefix ?? 'wellness') . '.enroll', $session) }}" method="POST" style="display: inline;" onclick="event.stopPropagation();">
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

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="mt-8 flex justify-end">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2">
                <nav class="flex items-center gap-0.25rem">
                    {{-- Previous Page Link --}}
                    @if ($sessions->onFirstPage())
                        <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">‹</span>
                    @else
                        <a class="px-3 py-2 text-sm text-gray-700 hover:text-white hover:bg-teal-700 rounded transition-colors" href="{{ $sessions->previousPageUrl() }}" rel="prev">‹</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                        @if ($page == $sessions->currentPage())
                            <span class="px-3 py-2 text-sm font-semibold text-white rounded" style="background-color: var(--wellness-teal);">{{ $page }}</span>
                        @else
                            <a class="px-3 py-2 text-sm text-gray-700 hover:text-white hover:bg-teal-700 rounded transition-colors" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($sessions->hasMorePages())
                        <a class="px-3 py-2 text-sm text-gray-700 hover:text-white hover:bg-teal-700 rounded transition-colors" href="{{ $sessions->nextPageUrl() }}" rel="next">›</a>
                    @else
                        <span class="px-3 py-2 text-sm text-gray-400 cursor-not-allowed">›</span>
                    @endif
                </nav>
            </div>
        </div>
        @endif
        @else
        <div class="empty-state">
            <div class="text-5xl mb-4">🌿</div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Wellness Sessions Available</h3>
            @if(request()->hasAny(['search', 'category']))
                <p class="text-gray-500">No wellness sessions match your current filters. Try adjusting your search criteria or clearing the filters.</p>
            @else
                <p class="text-gray-500">Wellness sessions will be posted here when available.</p>
            @endif
        </div>
        @endif
    </div>
</div>

@endsection
