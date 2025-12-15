@extends('layouts.user')

@section('title', $session->title . ' - AES Professional Learning Days')

@section('content')
<style>
    /* Page container with light background */
    .pl-wednesday-show-container {
        background: linear-gradient(to bottom right, #f9fafb, #f0f4f8, #e0e7ff);
        min-height: 100vh;
        padding: 2rem 0;
    }

    /* Main content card */
    .session-detail-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        border: 1px solid #e5e7eb;
    }

    /* Back button */
    .back-button {
        color: #3b82f6;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        color: #2563eb;
        transform: translateX(-2px);
    }

    /* Session title */
    .session-title {
        font-size: 2.25rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    /* Info badges */
    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #eff6ff;
        color: #1e40af;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .info-badge i {
        color: #3b82f6;
    }

    /* Section headers */
    .section-header {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        margin-top: 1.5rem;
    }

    .section-header:first-of-type {
        margin-top: 0;
    }

    /* Description text */
    .description-text {
        color: #4b5563;
        line-height: 1.7;
        font-size: 1rem;
        white-space: pre-wrap;
    }

    /* Links section */
    .links-section {
        border-top: 2px solid #e5e7eb;
        padding-top: 1.5rem;
        margin-top: 2rem;
    }

    .link-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }

    .link-card:hover {
        background: #f3f4f6;
        border-color: #c7d2fe;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .link-title {
        font-weight: 600;
        color: #1f2937;
        font-size: 1rem;
        transition: color 0.2s ease;
    }

    .link-card:hover .link-title {
        color: #3b82f6;
    }

    .link-description {
        color: #6b7280;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .external-link-icon {
        color: #9ca3af;
        transition: all 0.2s ease;
    }

    .link-card:hover .external-link-icon {
        color: #3b82f6;
        transform: translate(2px, -2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .session-detail-card {
            padding: 1.5rem;
        }

        .session-title {
            font-size: 1.75rem;
        }
    }
</style>

<div class="pl-wednesday-show-container">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('pl-wednesday.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Professional Learning</span>
        </a>

        <!-- Session Detail Card -->
        <div class="session-detail-card">
            <!-- Session Title -->
            <h1 class="session-title">{{ $session->title }}</h1>
            
            <!-- Session Info Badges -->
            <div class="flex flex-wrap gap-3 mb-6">
                <div class="info-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $session->date->format('l, F j, Y') }}</span>
                </div>
                <div class="info-badge">
                    <i class="fas fa-clock"></i>
                    <span>{{ $session->formatted_time }}</span>
                </div>
                @if($session->location)
                    <div class="info-badge">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $session->location }}</span>
                    </div>
                @endif
            </div>

            <!-- Description -->
            @if($session->description)
                <div>
                    <h2 class="section-header">Description</h2>
                    <div class="description-text">{{ $session->description }}</div>
                </div>
            @endif

            <!-- Resources & Links -->
            @if($session->links->count() > 0)
                <div class="links-section">
                    <h2 class="section-header">Resources & Links</h2>
                    <div class="space-y-3">
                        @foreach($session->links as $link)
                            <div class="link-card">
                                <a href="{{ $link->formatted_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between group">
                                    <div class="flex-1">
                                        <h3 class="link-title">{{ $link->title }}</h3>
                                        @if($link->description)
                                            <p class="link-description">{{ $link->description }}</p>
                                        @endif
                                    </div>
                                    <i class="fas fa-external-link-alt external-link-icon ml-4"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
