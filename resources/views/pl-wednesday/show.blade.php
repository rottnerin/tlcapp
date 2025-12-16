@extends('layouts.user')

@section('title', $session->title . ' - Professional Learning')

@push('styles')
<style>
    /* Pastel Theme Variables */
    :root {
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --pastel-lavender: #f5f0ff;
        --pastel-mint: #f0fff4;
    }

    /* Hero Header */
    .session-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        position: relative;
        overflow: hidden;
    }

    .session-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    /* Back Button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        background: white/10;
        backdrop-filter: blur(8px);
        padding: 0.625rem 1.25rem;
        border-radius: 2rem;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .back-btn:hover {
        background: white;
        color: #667eea;
        transform: translateX(-4px);
    }

    .back-btn svg {
        width: 1rem;
        height: 1rem;
        transition: transform 0.3s ease;
    }

    .back-btn:hover svg {
        transform: translateX(-2px);
    }

    /* Content Container */
    .content-container {
        background: linear-gradient(180deg, #f8f9ff 0%, #f0f4ff 100%);
        min-height: calc(100vh - 300px);
    }

    /* Main Card */
    .main-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-top: -4rem;
        position: relative;
        z-index: 10;
    }

    .card-accent {
        height: 6px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    }

    .card-content {
        padding: 2.5rem;
    }

    /* Info Pills */
    .info-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .info-pill svg {
        width: 1.125rem;
        height: 1.125rem;
    }

    .pill-date {
        background: linear-gradient(135deg, #f5f0ff 0%, #e8e0f0 100%);
        color: #5b4b7a;
        border: 2px solid #d4c4e8;
    }

    .pill-time {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .pill-location {
        background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
        color: #256739;
        border: 2px solid #b7dbc4;
    }

    .pill-duration {
        background: linear-gradient(135deg, #fff8f5 0%, #ffe5d9 100%);
        color: #935116;
        border: 2px solid #f5d0c0;
    }

    /* Division Badge */
    .division-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.75px;
    }

    .division-es {
        background: linear-gradient(135deg, #d4edda 0%, #b7e4c7 100%);
        color: #155724;
    }

    .division-ms {
        background: linear-gradient(135deg, #d6eaf8 0%, #aed6f1 100%);
        color: #154360;
    }

    .division-hs {
        background: linear-gradient(135deg, #ffe5d9 0%, #fad0bf 100%);
        color: #935116;
    }

    /* Session Title */
    .session-title {
        font-size: 2.25rem;
        font-weight: 800;
        color: #1a202c;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    /* Description Section */
    .description-section {
        background: linear-gradient(145deg, #f8f9ff 0%, #f0f4ff 100%);
        border-radius: 1rem;
        padding: 1.75rem;
        margin-top: 2rem;
        border: 2px solid #e8e0f0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .section-title-icon {
        width: 2rem;
        height: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-title-icon svg {
        width: 1.125rem;
        height: 1.125rem;
        color: white;
    }

    .description-text {
        color: #4a5568;
        font-size: 1.05rem;
        line-height: 1.8;
        white-space: pre-wrap;
    }

    /* Resources Section */
    .resources-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px dashed #e8e0f0;
    }

    .resource-card {
        background: white;
        border: 2px solid #e8e0f0;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .resource-card:hover {
        border-color: #667eea;
        transform: translateX(8px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
    }

    .resource-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .resource-card:nth-child(odd) .resource-icon {
        background: linear-gradient(135deg, #f5f0ff 0%, #e8e0f0 100%);
        color: #667eea;
    }

    .resource-card:nth-child(even) .resource-icon {
        background: linear-gradient(135deg, #f0fff4 0%, #d4edda 100%);
        color: #2e7d32;
    }

    .resource-card:hover .resource-icon {
        transform: scale(1.1);
    }

    .resource-icon svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .resource-content {
        flex: 1;
        min-width: 0;
    }

    .resource-title {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .resource-card:hover .resource-title {
        color: #667eea;
    }

    .resource-description {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .resource-arrow {
        width: 2rem;
        height: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        opacity: 0;
        transform: translateX(-10px);
        transition: all 0.3s ease;
    }

    .resource-card:hover .resource-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    .resource-arrow svg {
        width: 1rem;
        height: 1rem;
        color: white;
    }

    /* Empty Resources */
    .no-resources {
        text-align: center;
        padding: 2rem;
        background: linear-gradient(145deg, #f8f9ff 0%, #f0f4ff 100%);
        border-radius: 1rem;
        border: 2px dashed #d4c4e8;
    }

    .no-resources-icon {
        width: 4rem;
        height: 4rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .no-resources-icon svg {
        width: 2rem;
        height: 2rem;
        color: white;
    }

    /* Animations */
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

    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
</style>
@endpush

@section('content')
<!-- Hero Header -->
<div class="session-hero py-10 md:py-14 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="{{ route('pl-wednesday.index') }}" class="back-btn mb-6 inline-flex">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to All Sessions
        </a>
        
        <div class="text-white">
            @if($session->division)
                <span class="division-badge division-{{ strtolower($session->division->name) }} mb-4 inline-block">
                    {{ $session->division->full_name ?? $session->division->name }}
                </span>
            @endif
            <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ $session->title }}</h1>
        </div>
    </div>
</div>

<!-- Content Section -->
<div class="content-container pb-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Card -->
        <div class="main-card animate-in">
            <div class="card-accent"></div>
            <div class="card-content">
                <!-- Info Pills -->
                <div class="flex flex-wrap gap-3 mb-6">
                    <div class="info-pill pill-date">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $session->date->format('l, F j, Y') }}
                    </div>
                    
                    <div class="info-pill pill-time">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $session->formatted_time }}
                    </div>
                    
                    @if($session->location)
                        <div class="info-pill pill-location">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            {{ $session->location }}
                        </div>
                    @endif
                    
                    @if($session->duration)
                        <div class="info-pill pill-duration">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $session->duration }} minutes
                        </div>
                    @endif
                </div>

                <!-- Description -->
                @if($session->description)
                    <div class="description-section animate-in delay-1">
                        <h2 class="section-title">
                            <span class="section-title-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </span>
                            About This Session
                        </h2>
                        <p class="description-text">{{ $session->description }}</p>
                    </div>
                @endif

                <!-- Resources & Links -->
                <div class="resources-section animate-in delay-2">
                    <h2 class="section-title mb-4">
                        <span class="section-title-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                        </span>
                        Resources & Materials
                    </h2>
                    
                    @if($session->links->count() > 0)
                        <div class="space-y-3">
                            @foreach($session->links as $link)
                                <a href="{{ $link->formatted_url }}" target="_blank" rel="noopener noreferrer" class="resource-card">
                                    <div class="resource-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="resource-content">
                                        <div class="resource-title">{{ $link->title }}</div>
                                        @if($link->description)
                                            <div class="resource-description">{{ $link->description }}</div>
                                        @endif
                                    </div>
                                    <div class="resource-arrow">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="no-resources">
                            <div class="no-resources-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-1">No Resources Yet</h3>
                            <p class="text-gray-500">Materials for this session will be added soon.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
