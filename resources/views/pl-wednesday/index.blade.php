@extends('layouts.user')

@section('title', 'Professional Learning - AES Professional Learning Days')

@section('content')
<style>
    /* Main container background */
    .pl-wednesday-container {
        background: linear-gradient(to bottom right, #f9fafb, #f0f4f8, #e0e7ff);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Date header with gradient */
    .date-header {
        background: linear-gradient(to right, #3b82f6, #6366f1);
        color: white;
        padding: 1.25rem 1.75rem;
        border-radius: 0.75rem 0.75rem 0 0;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 0;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .date-header i {
        font-size: 1.125rem;
        opacity: 0.95;
    }

    /* Session card - clean white design */
    .pl-wednesday-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 1.75rem;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .pl-wednesday-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #c7d2fe;
    }
    
    /* Time badge - soft blue */
    .time-badge {
        background: #dbeafe;
        color: #1e40af;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .time-badge i {
        font-size: 0.75rem;
    }
    
    /* View Details button - professional blue */
    .view-details-btn {
        background: #3b82f6;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-align: center;
        display: inline-block;
        width: 100%;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
    }
    
    .view-details-btn:hover {
        background: #2563eb;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        color: white;
    }

    /* Session title link */
    .session-title-link {
        color: #1f2937;
        transition: color 0.2s ease;
    }

    .session-title-link:hover {
        color: #3b82f6;
    }

    /* Location icon styling */
    .location-text {
        color: #6b7280;
        font-size: 0.875rem;
    }

    /* Empty state improvements */
    .empty-state {
        background: white;
        border-radius: 1rem;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-state-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(to right, #3b82f6, #6366f1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.5rem;
    }

    /* Date group container */
    .date-group {
        margin-bottom: 2.5rem;
    }

    /* Session grid container */
    .sessions-grid-container {
        background: white;
        border: 1px solid #e5e7eb;
        border-top: none;
        border-radius: 0 0 0.75rem 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Page header improvements */
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        font-size: 1.125rem;
        color: #6b7280;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .date-header {
            font-size: 1.125rem;
            padding: 1rem 1.25rem;
        }

        .pl-wednesday-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="pl-wednesday-container">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Professional Learning Wednesdays</h1>
            @if($settings)
                <p class="page-subtitle">
                    Sessions from {{ $settings->start_date->format('F j, Y') }} to {{ $settings->end_date->format('F j, Y') }}
                </p>
            @endif
        </div>

        @if(!$settings || !$settings->is_active)
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Professional Learning Wednesdays Inactive</h3>
                <p class="text-gray-600">Professional Learning Wednesdays are currently inactive. Please check back later.</p>
            </div>
        @elseif($groupedSessions->isEmpty())
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No Sessions Scheduled</h3>
                <p class="text-gray-600">No sessions have been scheduled yet. Check back soon for upcoming Professional Learning Wednesday sessions!</p>
            </div>
        @else
            @foreach($groupedSessions as $date => $sessions)
                <div class="date-group">
                    <!-- Date Header with Icon -->
                    <div class="date-header">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</span>
                    </div>
                    
                    <!-- Sessions Grid -->
                    <div class="sessions-grid-container">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($sessions as $session)
                                <div class="pl-wednesday-card">
                                    <div class="flex flex-col h-full">
                                        <!-- Card Content -->
                                        <div class="flex-1">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                                <a href="{{ route('pl-wednesday.show', $session) }}" class="session-title-link">
                                                    {{ $session->title }}
                                                </a>
                                            </h3>
                                            
                                            @if($session->description)
                                                <p class="text-gray-600 mb-4 text-sm leading-relaxed line-clamp-3">
                                                    {{ Str::limit($session->description, 120) }}
                                                </p>
                                            @endif
                                            
                                            <!-- Time and Location -->
                                            <div class="flex flex-wrap gap-3 mb-4">
                                                <span class="time-badge">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $session->formatted_time }}
                                                </span>
                                                
                                                @if($session->location)
                                                    <span class="flex items-center location-text">
                                                        <i class="fas fa-map-marker-alt mr-1.5 text-blue-500"></i>
                                                        {{ $session->location }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- View Details Button -->
                                        <a href="{{ route('pl-wednesday.show', $session) }}" 
                                           class="view-details-btn">
                                            <i class="fas fa-arrow-right mr-2"></i>
                                            View Details
                                        </a>
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
@endsection
