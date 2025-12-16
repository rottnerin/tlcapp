@extends('layouts.user')

@section('title', 'Professional Learning - AES Professional Learning Days')

@section('content')
<style>
    .pl-wednesday-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border: 3px solid #000000;
    }
    
    .pl-wednesday-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color:rgb(216, 6, 6);
    }
    
    .date-header {
        background: #000000;
        color: #FFD700;
        padding: 1rem 1.5rem;
        border-radius: 0.75rem 0.75rem 0 0;
        font-weight: 600;
        font-size: 1.125rem;
        margin-bottom: 0;
        border: 3px solid #000000;
    }
    
    .time-badge {
        background: #000000;
        color: #FFD700;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-weight: 500;
        font-size: 0.875rem;
        border: 2px solid #FFD700;
    }
    
    .view-details-btn {
        background: #000000;
        color: #FFD700;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 2px solid #FFD700;
    }
    
    .view-details-btn:hover {
        background: #FFD700;
        color: #000000;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3);
    }
</style>

<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Professional Learning Wednesdays</h1>
        @if($settings)
            <p class="text-lg text-gray-600">
                Sessions from {{ $settings->start_date->format('F j, Y') }} to {{ $settings->end_date->format('F j, Y') }}
            </p>
        @endif
    </div>

    @if(!$settings || !$settings->is_active)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <p class="text-yellow-800">Professional Learning Wednesdays are currently inactive.</p>
        </div>
    @elseif($groupedSessions->isEmpty())
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
            <p class="text-blue-800">No sessions scheduled yet. Check back soon!</p>
        </div>
    @else
        @foreach($groupedSessions as $date => $sessions)
            <div class="mb-10">
                <div class="date-header">
                    {{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}
                </div>
                <div class="bg-white border border-gray-200 rounded-b-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sessions as $session)
                            <div class="pl-wednesday-card">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-gray-900 mb-3">
                                            <a href="{{ route('pl-wednesday.show', $session) }}" class="hover:text-yellow-600 transition-colors">
                                                {{ $session->title }}
                                            </a>
                                        </h3>
                                        @if($session->description)
                                            <p class="text-gray-600 mb-4 text-sm line-clamp-3">{{ Str::limit($session->description, 120) }}</p>
                                        @endif
                                        <div class="flex flex-wrap gap-3 mb-4">
                                            <span class="time-badge">
                                                {{ $session->formatted_time }}
                                            </span>
                                            @if($session->location)
                                                <span class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $session->location }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ route('pl-wednesday.show', $session) }}" 
                                       class="view-details-btn text-center inline-block">
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
@endsection

