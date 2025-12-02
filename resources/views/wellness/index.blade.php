@extends('layouts.user')

@section('title', 'Wellness Sessions - AES Professional Learning Days')

@section('content')
    <style>
body {
  background-color: #f9fafb; /* light gray, not pure white */
  color: #1f2937; /* dark gray text for contrast */
}

.section-header {
  background: #facc15; /* amber-400 yellow */
  color: #1f2937; /* dark gray text */
  padding: 0.75rem;
  border-radius: 0.75rem;
  font-weight: 600;
}

/* Wellness session cards - 3 per row design */
.wellness-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

.wellness-card {
  background: linear-gradient(135deg, #fde68a 0%, #facc15 100%);
  border-radius: 1.5rem;
  position: relative;
  transition: transform 0.6s ease;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  min-height: 380px;
  transform-style: preserve-3d;
  cursor: pointer;
}

.wellness-card:hover:not(.flipped):not(.full) {
  transform: translateY(-8px);
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.15);
}

.wellness-card.enrolled {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
}

.wellness-card.full {
  background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
  color: #ffffff;
  opacity: 0.7;
  cursor: not-allowed;
  pointer-events: none;
}

.wellness-card.full .card-front {
  background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
  color: #ffffff;
}

.wellness-card.full .card-back {
  background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
  color: #ffffff;
}

.wellness-card.full:hover {
  transform: none;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.wellness-card.flipped {
  transform: rotateY(180deg);
}

/* Card faces */
.card-face {
  position: absolute;
  width: 100%;
  height: 100%;
  backface-visibility: hidden;
  border-radius: 1.5rem;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  top: 0;
  left: 0;
  justify-content: space-between;
}

.card-back {
  padding-top: 4rem; /* Add extra padding to account for return arrow */
}

.card-front {
  background: linear-gradient(135deg, #fde68a 0%, #facc15 100%);
}

.card-back {
  background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
  color: white;
  transform: rotateY(180deg);
}

.wellness-card.enrolled .card-front {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
}

.wellness-card.enrolled .card-back {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

/* Card content */
.card-face h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.75rem;
  margin-top: 0;
  line-height: 1.3;
  flex-grow: 1;
}

/* Wellness Logo */
.wellness-logo {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 0.75rem;
  margin-top: 0.5rem;
}

.logo-image {
  width: 40px;
  height: 40px;
  object-fit: contain;
  opacity: 0.8;
  transition: opacity 0.3s ease;
}

.wellness-card:hover .logo-image {
  opacity: 1;
}

.card-back h3 {
  color: white;
}


.card-face .audience {
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  margin-bottom: 1.5rem;
}

.card-back .audience {
  color: rgba(255, 255, 255, 0.8);
}

/* Presenter and location info */
.presenter {
  font-size: 1rem;
  font-weight: 600;
  color: #374151;
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

.card-back .presenter {
  color: rgba(255, 255, 255, 0.9);
}

.location {
  font-size: 0.875rem;
  font-weight: 500;
  color: #6b7280;
  margin-bottom: 1rem;
  line-height: 1.4;
}

.card-back .location {
  color: rgba(255, 255, 255, 0.8);
}

.session-presenter {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 1rem;
  line-height: 1.4;
}

/* Categories */
.categories {
  display: flex;
  flex-wrap: wrap;
  gap: 0.375rem;
  margin-bottom: 1rem;
  max-height: 3.5rem; /* Limit to approximately 2 rows */
  overflow: hidden;
  align-content: flex-start;
  position: relative;
}

.categories::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 0.75rem;
  background: linear-gradient(transparent, rgba(255, 255, 255, 0.1));
  pointer-events: none;
}

.category-tag {
  background: rgba(255, 255, 255, 0.2);
  color: #1f2937;
  padding: 0.25rem 0.5rem;
  border-radius: 0.5rem;
  font-size: 0.6875rem;
  font-weight: 500;
  display: inline-block;
  border: 1px solid rgba(255, 255, 255, 0.3);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 100%;
  backdrop-filter: blur(10px);
  flex-shrink: 0;
  line-height: 1.2;
}

/* Wellness tag */
.wellness-tag {
  display: inline-flex;
  align-items: center;
  background: rgba(255, 255, 255, 0.2);
  color: #1f2937;
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-bottom: 1rem;
  backdrop-filter: blur(10px);
}

.wellness-card.enrolled .wellness-tag {
  background: rgba(255, 255, 255, 0.3);
  color: white;
}

/* Status indicators */
.status-indicator {
  position: absolute;
  top: 1rem;
  left: 1rem;
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.status-available {
  background: rgba(34, 197, 94, 0.9);
  color: white;
}

.status-full {
  background: rgba(239, 68, 68, 0.9);
  color: white;
}

.status-enrolled {
  background: rgba(16, 185, 129, 0.9);
  color: white;
}

/* View Details Button */
.view-details-btn {
  background: rgba(255, 255, 255, 0.9);
  color: #1f2937;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 100%;
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.view-details-btn:hover {
  background: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

/* Back card content */
.card-back .session-description {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.9);
  line-height: 1.6;
  margin-bottom: 1.25rem;
  overflow-y: auto;
  max-height: 120px;
  flex-shrink: 0;
}


.card-back .session-location {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 1rem;
}


.card-back .session-location svg {
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
  color: rgba(255, 255, 255, 0.6);
}

/* Sign up button */
.signup-btn {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  border: none;
  padding: 0.375rem 0.75rem;
  border-radius: 0.375rem;
  font-weight: 600;
  font-size: 0.6875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  width: auto;
  min-width: 70px;
  max-width: 120px;
  box-shadow: 0 2px 8px rgba(17, 153, 142, 0.25);
  margin: 0;
  position: relative;
  z-index: 5;
  white-space: nowrap;
  text-align: center;
}

.signup-btn:disabled {
  background: #6b7280;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
  font-size: 0.625rem;
  padding: 0.375rem 0.5rem;
  max-width: 140px;
  white-space: normal;
  line-height: 1.2;
  text-align: center;
}

.signup-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(17, 153, 142, 0.4);
}

/* Return arrow in top left corner */
.return-arrow {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  width: 2.5rem;
  height: 2.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
  backdrop-filter: blur(10px);
}

.return-arrow:hover {
  background: rgba(255, 255, 255, 0.3);
  border-color: rgba(255, 255, 255, 0.5);
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Card back content structure */
.card-back-content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  padding-bottom: 2.5rem; /* Space for smaller positioned button */
}

.card-back-actions {
  position: absolute;
  bottom: 1rem;
  right: 1rem;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  align-items: center;
  gap: 0.5rem;
  z-index: 10;
  width: calc(100% - 2rem);
}

/* Calendar button styling for wellness cards */
.wellness-card .mobile-calendar-container {
  width: 100%;
}

.wellness-card .calendar-button {
  width: 100%;
  justify-content: center;
  font-size: 0.875rem;
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  color: white;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
}

.wellness-card .calendar-button:hover {
  background: rgba(255, 255, 255, 0.2);
  border-color: rgba(255, 255, 255, 0.3);
}

.wellness-card .mobile-calendar-options {
  background: rgba(0, 0, 0, 0.9);
  border: 1px solid rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
}

.wellness-card .calendar-option {
  color: white;
  border-bottom-color: rgba(255, 255, 255, 0.1);
}

.wellness-card .calendar-option:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

/* Pagination Styling */
.pagination {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  color: #6b7280;
}

.pagination .page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.375rem 0.5rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  color: #374151;
  text-decoration: none;
  transition: all 0.2s ease;
  min-width: 2rem;
  height: 2rem;
}

.pagination .page-link:hover {
  background-color: #f3f4f6;
  border-color: #d1d5db;
  color: #1f2937;
}

.pagination .page-item.active .page-link {
  background: linear-gradient(135deg, #fde68a 0%, #facc15 100%);
  border-color: #facc15;
  color: #1f2937;
  font-weight: 600;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pagination .page-item.disabled .page-link {
  color: #9ca3af;
  background-color: #f9fafb;
  border-color: #e5e7eb;
  cursor: not-allowed;
}

.pagination .page-item.disabled .page-link:hover {
  background-color: #f9fafb;
  border-color: #e5e7eb;
  color: #9ca3af;
}

.pagination .page-info {
  color: #6b7280;
  font-size: 0.75rem;
  margin: 0 1.25rem 0 0;
  white-space: nowrap;
}

/* Empty state */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
  background: white;
  border-radius: 1.5rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
}

.empty-state-icon {
  width: 80px;
  height: 80px;
  background: linear-gradient(135deg, #fde68a, #facc15);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1.5rem;
}

.empty-state h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.75rem;
}

.empty-state p {
  color: #6b7280;
  font-size: 1rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .wellness-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
}

@media (max-width: 768px) {
  .wellness-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .wellness-card {
    min-height: 350px;
  }
  
  .card-face {
    padding: 1.5rem;
  }
  
  .card-back {
    padding-top: 3rem; /* Adjust for mobile */
  }
  
  .card-face h3 {
    font-size: 1.25rem;
  }
  
  .logo-image {
    width: 35px;
    height: 35px;
  }
  
  .view-details-btn,
  .signup-btn {
    padding: 0.625rem 1.25rem;
    font-size: 0.8125rem;
  }
}

@media (max-width: 480px) {
  .wellness-grid {
    gap: 1rem;
  }
  
  .wellness-card {
    min-height: 320px;
  }
  
  .card-face {
    padding: 1rem;
  }
  
  .card-back {
    padding-top: 2.5rem; /* Adjust for small mobile */
  }
  
  .card-face h3 {
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
  }
  
  .logo-image {
    width: 30px;
    height: 30px;
  }
  
  .card-face .presenter {
    font-size: 0.875rem;
    margin-bottom: 0.375rem;
  }
  
  .card-face .audience {
    font-size: 0.75rem;
    margin-bottom: 1rem;
  }
  
  .categories {
    gap: 0.25rem;
    margin-bottom: 0.625rem;
    max-height: 3rem; /* Slightly smaller on mobile */
  }
  
  .category-tag {
    font-size: 0.625rem;
    padding: 0.1875rem 0.375rem;
    border-radius: 0.375rem;
  }
  
  .card-back-actions {
    bottom: 0.75rem;
    right: 0.75rem;
    gap: 0.375rem;
  }
  
  .return-arrow {
    top: 0.5rem;
    left: 0.5rem;
    width: 2rem;
    height: 2rem;
  }
  
  .session-description {
    font-size: 0.75rem;
    line-height: 1.4;
    max-height: 100px;
  }
  
  .session-location {
    font-size: 0.75rem;
    margin-bottom: 0.375rem;
  }
  
  
  .wellness-tag {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    margin-bottom: 1rem;
  }
  
  .view-details-btn {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
  }
  
  .signup-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.625rem;
    min-width: 60px;
    max-width: 100px;
  }
  
  .signup-btn:disabled {
    font-size: 0.5625rem;
    padding: 0.25rem 0.375rem;
    max-width: 120px;
    line-height: 1.1;
  }
  
  .card-back-content {
    padding-bottom: 2rem; /* Adjust for smaller mobile screens */
  }
  
  .card-back-actions {
    bottom: 0.5rem;
    right: 0.5rem;
    gap: 0.375rem;
  }
  
  .return-arrow {
    top: 0.75rem;
    left: 0.75rem;
    width: 2.25rem;
    height: 2.25rem;
  }
  
  .pagination {
    gap: 0.125rem;
    font-size: 0.625rem;
  }
  
  .pagination .page-link {
    padding: 0.25rem 0.375rem;
    min-width: 1.75rem;
    height: 1.75rem;
  }
  
  .pagination .page-info {
    font-size: 0.625rem;
    margin: 0 0.75rem 0 0;
  }
  
  
  .session-description {
    font-size: 0.8125rem;
    line-height: 1.5;
  }
  
  .session-location {
    font-size: 0.8125rem;
    margin-bottom: 0.375rem;
  }
  
  .section-header {
    padding: 0.75rem;
    font-size: 0.875rem;
  }
  
  /* Improve touch interactions on mobile */
  .wellness-card {
    -webkit-tap-highlight-color: transparent;
  }
  
  .view-details-btn,
  .signup-btn,
  .return-arrow {
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
  }
  
  /* Prevent zoom on input focus */
  input, select, textarea {
    font-size: 16px;
  }
  
}
    </style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
                    <div class="text-2xl mr-3">🌿</div>
                    <div class="text-center">
                        <h1 class="text-xl font-bold">Wellness Sessions</h1>
                        <p class="mt-1 text-sm opacity-90">Choose from a variety of wellness activities to enhance your professional learning experience</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Sessions</h3>
            <form method="GET" action="{{ route('wellness.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search sessions, presenters..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category" 
                            name="category" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-search mr-2"></i>Apply Filters
                    </button>
                </div>
            </form>
            
            <!-- Clear Filters -->
            @if(request()->hasAny(['search', 'category']))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route('wellness.index') }}" 
                       class="text-green-600 hover:text-green-800 text-sm font-medium">
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
                        $presenterNames = explode(',', $session->presenter_name ?? '');
                        $presenterNames = array_map('trim', $presenterNames);
                    @endphp
                    
                    <div class="wellness-card {{ $isUserEnrolled ? 'enrolled' : '' }} {{ $session->isFull() ? 'full' : '' }}" data-session-id="{{ $session->id }}">
                        <!-- Front of Card -->
                        <div class="card-face card-front">
                            <!-- Wellness Logo -->
                            <div class="wellness-logo">
                                <img src="{{ asset('logos/wellbeing.png') }}" alt="Wellness" class="logo-image">
                            </div>
                            
                                <!-- Card Content -->
                                <div class="flex items-center justify-between">
                                    <h3>{{ $session->title }}</h3>
                                    @if($session->isFull())
                                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            FULL
                                        </span>
                                    @endif
                                </div>
                            
                            <div class="presenter">
                                <strong>Presenter:</strong> {{ $session->presenter_name }}
                                @if($session->co_presenter_name)
                                    <br><strong>Co-Presenter:</strong> {{ $session->co_presenter_name }}
                                @endif
                            </div>

                            @if($session->location)
                                <div class="location">
                                    <strong>Location:</strong> {{ $session->location }}
                                </div>
                            @endif


                            <!-- Categories -->
                            @if($session->category && is_array($session->category) && count($session->category) > 0)
                                <div class="categories">
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


                            <!-- View Details Button -->
                            @if($session->isFull())
                                <button class="view-details-btn" disabled style="background: rgba(156, 163, 175, 0.9); color: #ffffff; cursor: not-allowed;">
                                    Session Full
                                </button>
                            @else
                                <button class="view-details-btn" onclick="flipCard({{ $session->id }})">
                                    View Details
                                </button>
                            @endif
                        </div>

                        <!-- Back of Card -->
                        <div class="card-face card-back">
                            <!-- Return Arrow -->
                            <button class="return-arrow" onclick="flipCard({{ $session->id }})">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <div class="card-back-content">
                                <h3>{{ $session->title }}</h3>
                                
                                <div class="session-description">
                                    {{ $session->description ?? 'No description available.' }}
                                </div>

                                <div class="session-presenter">
                                    <strong>Presenter:</strong> {{ $session->presenter_name }}
                                    @if($session->co_presenter_name)
                                        <br><strong>Co-Presenter:</strong> {{ $session->co_presenter_name }}
                                    @endif
                                </div>

                                
                                @if($session->location)
                                <div class="session-location">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $session->location }}
                                    </div>
                                @endif
                            </div>

                            <div class="card-back-actions">
                                <!-- Calendar Button -->
                                <div class="mb-3">
                                    <a href="{{ $session->google_calendar_url }}" target="_blank" class="calendar-button">
                                        <svg class="calendar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Add to Calendar
                                    </a>
                                </div>
                                
                                @if($isUserEnrolled)
                                    <button class="signup-btn" disabled>
                                        ✓ Enrolled
                                    </button>
                                @elseif($hasUserEnrollment)
                                    <button class="signup-btn" disabled>
                                        Already Enrolled in Another Session
                                    </button>
                                @elseif($session->isAvailableForEnrollment())
                                    <form method="POST" action="{{ route('wellness.enroll', $session) }}" id="enroll-form-{{ $session->id }}">
                                            @csrf
                                        <button type="button" 
                                               onclick="confirmEnrollment({{ $session->id }}, '{{ $session->title }}')"
                                               class="signup-btn">
                                            Sign Up
                                            </button>
                                        </form>
                                @else
                                    <button class="signup-btn" disabled>
                                        Session Full
                                    </button>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($sessions->hasPages())
            <div class="mt-8 flex justify-end">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-2">
                    <nav class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($sessions->onFirstPage())
                            <span class="page-item disabled">
                                <span class="page-link">‹</span>
                            </span>
                        @else
                            <span class="page-item">
                                <a class="page-link" href="{{ $sessions->previousPageUrl() }}" rel="prev">‹</a>
                            </span>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                            @if ($page == $sessions->currentPage())
                                <span class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </span>
                            @else
                                <span class="page-item">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </span>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($sessions->hasMorePages())
                            <span class="page-item">
                                <a class="page-link" href="{{ $sessions->nextPageUrl() }}" rel="next">›</a>
                            </span>
                        @else
                            <span class="page-item disabled">
                                <span class="page-link">›</span>
                            </span>
                        @endif
                    </nav>
                </div>
            </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                </div>
                <h3>No wellness sessions found</h3>
                @if(request()->hasAny(['search', 'category']))
                    <p>No wellness sessions match your current filters. Try adjusting your search criteria or clearing the filters.</p>
                @else
                    <p>No wellness sessions are currently available. Check back later for new opportunities to enhance your well-being.</p>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function flipCard(sessionId) {
        const card = document.querySelector(`[data-session-id="${sessionId}"]`);
        if (card && !card.classList.contains('full')) {
            card.classList.toggle('flipped');
        }
    }

    function confirmEnrollment(sessionId, sessionTitle) {
        const message = `Are you sure you want to enroll in "${sessionTitle}"?`;
        
        if (confirm(message)) {
            document.getElementById('enroll-form-' + sessionId).submit();
        }
    }

    // Close card when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.wellness-card')) {
            document.querySelectorAll('.wellness-card.flipped').forEach(card => {
                card.classList.remove('flipped');
            });
        }
    });
</script>
@endpush
@endsection