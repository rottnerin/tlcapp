@extends('layouts.user')

@section('title', 'Schedule - AES Professional Learning Days')

@section('content')
<style>
body {
  background-color: #f9fafb; /* light gray, not pure white */
  color: #1f2937; /* dark gray text for contrast */
}

.session-card {
  background: #ffffff;
  border-radius: 1rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  padding: 1.5rem;
  margin-bottom: 1rem;
  transition: all 0.3s ease;
}

/* Current time highlighting */
.event-card.current-time {
  background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
  border: 3px solid #f59e0b;
  box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
  transform: scale(1.02);
  animation: pulse-glow 2s infinite;
}

.event-card.current-time .time-bubble {
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: white;
  font-weight: 700;
  box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
}

.event-card.current-time .event-title {
  color: #92400e;
  font-weight: 700;
}

.event-card.current-time .event-description {
  color: #a16207;
}

@keyframes pulse-glow {
  0%, 100% {
    box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
  }
  50% {
    box-shadow: 0 12px 35px rgba(245, 158, 11, 0.5);
  }
}

.session-card h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
}

.session-card p {
  color: #374151;
}

.section-header {
  background: #facc15; /* amber-400 yellow */
  color: #1f2937; /* dark gray text */
  padding: 0.75rem;
  border-radius: 0.75rem;
  font-weight: 600;
}

.tag {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

.tag-all { background: #f3f4f6; color: #111827; }
.tag-elementary { background: #d1fae5; color: #065f46; }
.tag-middle { background: #dbeafe; color: #1e40af; }
.tag-high { background: #ede9fe; color: #5b21b6; }

.day-card {
  background: linear-gradient(135deg, #fde68a, #facc15);
  color: #111827;
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 6px 16px rgba(0,0,0,0.08);
  transition: all 0.3s ease;
}

.day-card.selected {
  background: linear-gradient(135deg, #f59e0b, #d97706);
  box-shadow: 0 8px 24px rgba(245, 158, 11, 0.4);
  transform: scale(1.02);
}

/* Time badge system-level fixes with beautiful gradients */
.time-badge {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important; /* Amber gradient to match theme */
  color: #ffffff !important; /* White text */
  border-radius: 0.75rem;
  padding: 1rem;
  text-align: center;
  min-width: 120px;
  box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.time-badge.wellness {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important; /* Green gradient for wellness */
  box-shadow: 0 4px 12px rgba(17, 153, 142, 0.25);
}

.time-badge .time-start {
  font-size: 1.125rem;
  font-weight: 700;
  color: #ffffff !important;
  display: block;
  margin-bottom: 0.25rem;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.time-badge .time-end {
  font-size: 0.875rem;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.9) !important; /* Slightly transparent white */
  display: block;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.time-badge .wellness-tag {
  background: rgba(255, 255, 255, 0.25) !important;
  color: #ffffff !important;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-top: 0.5rem;
  display: inline-block;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Modern event card layout */
.event-card {
  background: #ffffff;
  border-radius: 1.5rem;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
  padding: 2rem;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.event-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.12);
}

.event-card-grid {
  display: grid;
  grid-template-columns: auto 1fr auto;
  gap: 2rem;
  align-items: start;
}

/* Time bubble */
.time-bubble {
  background: linear-gradient(135deg, #fde68a 0%, #facc15 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  width: 160px;
  max-width: 160px;
  min-width: 140px;
  box-shadow: 0 8px 24px rgba(250, 204, 21, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.2);
  flex-shrink: 0;
}

.time-bubble.wellness {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  box-shadow: 0 8px 24px rgba(17, 153, 142, 0.3);
}

.time-bubble .time-start {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  display: block;
  margin-bottom: 0.5rem;
}

.time-bubble .time-end {
  font-size: 0.875rem;
  font-weight: 500;
  color: #374151;
  display: block;
}

.time-bubble .wellness-tag {
  background: rgba(255, 255, 255, 0.2);
  color: #1f2937;
  padding: 0.375rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  margin-top: 0.75rem;
  display: inline-block;
  border: 1px solid rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
}

/* Content bubble */
.content-bubble {
  background: #f8fafc;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid rgba(0, 0, 0, 0.05);
  min-width: 0;
  flex: 1;
}

.content-bubble h3 {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 0.75rem;
  line-height: 1.3;
}

.content-bubble .description {
  font-size: 1rem;
  color: #64748b;
  line-height: 1.6;
  margin-bottom: 1rem;
}

.content-bubble .presenter {
  display: flex;
  align-items: center;
  font-size: 0.875rem;
  color: #64748b;
  margin-bottom: 1rem;
}

.content-bubble .presenter svg {
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
  color: #94a3b8;
}

.content-bubble .divisions {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.content-bubble .link-button {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
  color: #ffffff;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.875rem;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25);
}

.content-bubble .link-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(251, 191, 36, 0.4);
}

.content-bubble .link-button svg {
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
}

/* Google Calendar button styling */
.content-bubble .calendar-button {
  display: inline-flex;
  align-items: center;
  background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
  color: #ffffff;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.875rem;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(66, 133, 244, 0.25);
  margin-left: 0.75rem;
}

.content-bubble .calendar-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(66, 133, 244, 0.4);
}

.content-bubble .calendar-button svg {
  width: 1rem;
  height: 1rem;
  margin-right: 0.5rem;
}

/* Button container for proper spacing */
.content-bubble .button-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  align-items: center;
}

/* Location bubble */
.location-bubble {
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  border-radius: 1rem;
  padding: 1.5rem;
  text-align: center;
  width: 160px;
  max-width: 160px;
  min-width: 140px;
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  flex-shrink: 0;
}

.location-bubble .location-icon {
  width: 2rem;
  height: 2rem;
  margin: 0 auto 0.75rem;
  color: #64748b;
}

.location-bubble .location-text {
  font-size: 0.875rem;
  font-weight: 600;
  color: #475569;
  line-height: 1.4;
  word-wrap: break-word;
  overflow-wrap: break-word;
  hyphens: auto;
  text-align: center;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
  .event-card-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .time-bubble,
  .location-bubble {
    min-width: auto;
  }
}

@media (max-width: 768px) {
  .event-card {
    padding: 1rem;
    margin-bottom: 0.75rem;
  }
  
  .time-bubble,
  .location-bubble {
    width: 100%;
    max-width: none;
    min-width: auto;
    margin-bottom: 0.75rem;
    padding: 1rem;
  }
  
  .event-card-grid {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }
  
  .content-bubble h3 {
    font-size: 1.125rem;
    margin-bottom: 0.5rem;
  }
  
  .content-bubble .description {
    font-size: 0.875rem;
    margin-bottom: 0.75rem;
  }
  
  .content-bubble .presenter {
    font-size: 0.8125rem;
    margin-bottom: 0.75rem;
  }
  
  .content-bubble .divisions {
    gap: 0.375rem;
    margin-bottom: 0.75rem;
  }
  
  /* Improve touch targets on mobile */
  .tag {
    padding: 0.375rem 0.875rem;
    font-size: 0.8125rem;
  }
  
  .link-button {
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
  }
  
  .calendar-button {
    padding: 0.75rem 1.25rem;
    font-size: 0.875rem;
    margin-left: 0;
  }
  
  .button-container {
    gap: 0.5rem;
  }
  
  .day-card {
    padding: 1rem;
    font-size: 0.875rem;
  }
  
  .time-badge {
    min-width: 100px;
    padding: 0.75rem;
  }
  
  .time-badge .time-start {
    font-size: 1rem;
  }
  
  .time-badge .time-end {
    font-size: 0.75rem;
  }
}

@media (max-width: 480px) {
  .event-card {
    padding: 0.75rem;
    border-radius: 0.75rem;
    margin-bottom: 0.5rem;
  }
  
  .time-bubble,
  .location-bubble {
    padding: 0.75rem;
    margin-bottom: 0.5rem;
  }
  
  .time-badge {
    min-width: 70px;
    padding: 0.375rem;
  }
  
  .time-badge .time-start {
    font-size: 0.8125rem;
  }
  
  .time-badge .time-end {
    font-size: 0.5625rem;
  }
  
  .content-bubble h3 {
    font-size: 1rem;
    margin-bottom: 0.375rem;
  }
  
  .content-bubble .description {
    font-size: 0.8125rem;
    margin-bottom: 0.5rem;
  }
  
  .content-bubble .presenter {
    font-size: 0.75rem;
    margin-bottom: 0.5rem;
  }
  
  .content-bubble .divisions {
    gap: 0.25rem;
    margin-bottom: 0.5rem;
  }
  
  .day-card {
    padding: 0.75rem;
    font-size: 0.75rem;
  }
  
  .section-header {
    padding: 0.75rem;
    font-size: 0.875rem;
  }
  
  /* Improve touch targets on small screens */
  .tag {
    padding: 0.25rem 0.5rem;
    font-size: 0.6875rem;
  }
  
  .link-button {
    padding: 0.5rem 0.875rem;
    font-size: 0.75rem;
  }
  
  .calendar-button {
    padding: 0.5rem 0.875rem;
    font-size: 0.75rem;
    margin-left: 0;
  }
  
  .button-container {
    gap: 0.375rem;
  }
  
  
  .content-bubble .description {
    font-size: 0.875rem;
    line-height: 1.5;
  }
}

/* System-level contrast fixes with beautiful gradients */
.bg-gradient-to-r {
  background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
  box-shadow: 0 4px 12px rgba(251, 191, 36, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.bg-gradient-to-r .text-lg,
.bg-gradient-to-r .text-sm,
.bg-gradient-to-r .font-bold {
  color: #ffffff !important;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Global text contrast rules */
.text-white {
  color: #ffffff !important;
}

.text-gray-900 {
  color: #111827 !important;
}

.text-gray-800 {
  color: #1f2937 !important;
}

.text-gray-700 {
  color: #374151 !important;
}

/* Ensure all text on white backgrounds is dark */
.bg-white * {
  color: inherit;
}

.bg-white h1,
.bg-white h2,
.bg-white h3,
.bg-white h4,
.bg-white h5,
.bg-white h6 {
  color: #111827 !important;
}

.bg-white p,
.bg-white span,
.bg-white div {
  color: #374151 !important;
}

/* Fix any remaining gradient text issues */
.from-blue-500 .text-white,
.from-blue-600 .text-white,
.from-indigo-600 .text-white,
.from-emerald-500 .text-white,
.from-emerald-600 .text-white,
.from-amber-400 .text-white,
.from-amber-500 .text-white {
  color: #ffffff !important;
}
</style>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Day Selector Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div class="day-card hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 {{ $activeTab === 'day1' ? 'selected' : '' }}">
                <a href="{{ route('dashboard', ['day' => 'day1'] + request()->query()) }}" class="block">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-2xl font-bold mb-2">Day 1</h3>
                        <p class="text-lg">
                            {{ $eventDates[0]->format('l, F j, Y') }}
                        </p>
                    </div>
                </a>
            </div>

            <div class="day-card hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 {{ $activeTab === 'day2' ? 'selected' : '' }}">
                <a href="{{ route('dashboard', ['day' => 'day2'] + request()->query()) }}" class="block">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📅</div>
                        <h3 class="text-2xl font-bold mb-2">Day 2</h3>
                        <p class="text-lg">
                            {{ $eventDates[1]->format('l, F j, Y') }}
                        </p>
                    </div>
                </a>
            </div>
    </div>

        <!-- Filter Section -->
    @if($divisions->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-8 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
                    <div class="text-3xl mr-4">🔍</div>
                    <div class="text-center">
                        <h2 class="text-xl font-bold">Filter by Division</h2>
                        <p class="mt-1 opacity-90 text-sm">Click divisions to filter your schedule view instantly</p>
                    </div>
                </div>
            </div>
            
            <div class="p-3">
                <form method="GET" class="space-y-3">
                    <input type="hidden" name="day" value="{{ $activeTab }}">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($divisions as $division)
                        <label class="group cursor-pointer">
                          <input type="checkbox"
                                name="divisions[]"
                                value="{{ $division->id }}"
                                {{ in_array($division->id, $selectedDivisions) ? 'checked' : '' }}
                                    class="sr-only division-checkbox">
                              <div class="relative p-4 rounded-lg border-2 transition-all duration-200 group-hover:shadow-md
                                  {{ in_array($division->id, $selectedDivisions) 
                                      ? 'border-amber-400 bg-gradient-to-br from-amber-50 to-yellow-50 shadow-md' 
                                      : 'border-gray-200 bg-white hover:border-amber-300 hover:shadow-sm hover:bg-gradient-to-br hover:from-amber-50 hover:to-yellow-50' }}">
                                  <div class="flex items-center justify-center space-x-3">
                                      
                                      <div class="text-center">
                                          <span class="font-semibold text-sm
                                              {{ in_array($division->id, $selectedDivisions) ? 'text-amber-800' : 'text-gray-800 group-hover:text-amber-700' }}">
                                              {{ $division->full_name == "All School (K-12)" ? "All School (PreK-12)" : $division->full_name }}
                                          </span>
                                      </div>
                                  </div>
                              </div>
                        </label>
                    @endforeach
                    </div>
                    
            </form>
            </div>
        </div>
    @endif

        <!-- Schedule Content -->
    @if($scheduleItems->count() > 0)
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 mb-12 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
                    <div class="text-3xl mr-4">📋</div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold">{{ $selectedDate->format('l, F j, Y') }}</h2>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8">
                <div class="space-y-3 sm:space-y-6">
                    @foreach($scheduleItems as $item)
                        @php
                            $isWellnessSession = isset($item->session_type) && $item->session_type === 'wellness';
                            $timeColor = $isWellnessSession ? 'from-green-500 to-emerald-600' : 'from-blue-500 to-indigo-600';
                        @endphp
                        
                        <div class="event-card {{ $isWellnessSession ? 'ring-2 ring-green-200' : '' }}" 
                             data-start-time="{{ $item->start_time->format('H:i') }}" 
                             data-end-time="{{ $item->end_time->format('H:i') }}"
                             data-item-id="{{ $item->id }}">
                            <div class="event-card-grid">
                                <!-- Time Bubble -->
                                <div class="time-bubble {{ $isWellnessSession ? 'wellness' : '' }}">
                                    <div class="time-start">{{ $item->start_time->format('g:i A') }}</div>
                                    <div class="time-end">{{ $item->end_time->format('g:i A') }}</div>
                                    @if($isWellnessSession)
                                        <div class="mt-2">
                                            <span class="wellness-tag">
                                                🌿 Wellness
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content Bubble -->
                                <div class="content-bubble">
                                    <h3>{{ $item->title }}</h3>

                                            @if($item->description)
                                    <div class="description">{{ $item->description }}</div>
                                    @endif
                                    
                                    @if($item->presenter_primary)
                                    <div class="presenter">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>{{ $item->presenter_primary }}</span>
                                    </div>
                                            @endif

                                            <!-- Divisions -->
                                            @if($item->divisions->count() > 0)
                                    <div class="divisions">
                                                    @foreach($item->divisions as $division)
                                            <span class="tag
                                                {{ strtolower($division->name) === 'es' ? 'tag-elementary' :
                                                   (strtolower($division->name) === 'ms' ? 'tag-middle' : 
                                                   (strtolower($division->name) === 'hs' ? 'tag-high' : 'tag-all')) }}">
                                                            {{ $division->full_name == "All School (K-12)" ? "All School (PreK-12)" : $division->full_name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                    <!-- Buttons Container -->
                                    <div class="button-container">
                                        <!-- Link -->
                                        @if($item->hasLink())
                                                <a href="{{ $item->formatted_link_url }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                           class="link-button">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                    {{ $item->link_title ?: 'Learn More' }}
                                                </a>
                                        @endif
                                        
                                        <!-- Add to Calendar Button -->
                                        <a href="{{ $item->google_calendar_url }}" target="_blank" class="calendar-button">
                                            <svg class="calendar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Add to Calendar
                                        </a>
                                    </div>
                                </div>

                                <!-- Location Bubble -->
                                @if($item->location)
                                <div class="location-bubble">
                                    <svg class="location-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div class="location-text">{{ $item->location }}</div>
                                </div>
                                @else
                                <div class="location-bubble">
                                    <svg class="location-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <div class="location-text">TBD</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 text-center py-16">
            <div class="text-6xl mb-6">📅</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">No Sessions Found</h3>
            <p class="text-gray-700 max-w-md mx-auto text-lg">
                @if($selectedDivisions)
                    No sessions found for the selected divisions. Try selecting different divisions to see more options.
                @else
                    The schedule hasn't been published yet. Check back later for updates.
                @endif
            </p>
        </div>
    @endif
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced division filter highlighting
    const divisionCheckboxes = document.querySelectorAll('.division-checkbox');
    
    divisionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            const card = label.querySelector('div');
            
            if (this.checked) {
                card.classList.add('border-amber-400', 'bg-gradient-to-br', 'from-amber-50', 'to-yellow-50', 'shadow-md');
                card.classList.remove('border-gray-200', 'bg-white');
            } else {
                card.classList.remove('border-amber-400', 'bg-gradient-to-br', 'from-amber-50', 'to-yellow-50', 'shadow-md');
                card.classList.add('border-gray-200', 'bg-white');
            }
            
            // Auto-submit the form when checkbox changes
            const form = this.closest('form');
            if (form) {
                form.submit();
            }
        });
    });
    
    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
    
    
    // Add subtle animations on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe schedule items for animation
    document.querySelectorAll('.bg-gradient-to-r.from-white.to-gray-50').forEach(item => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(item);
    });
    
    // Time highlighting functionality
    function highlightCurrentTime() {
        const now = new Date();
        const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        
        // Remove current-time class from all cards
        document.querySelectorAll('.event-card').forEach(card => {
            card.classList.remove('current-time');
        });
        
        // Find and highlight cards that match current time
        document.querySelectorAll('.event-card').forEach(card => {
            const startTime = card.getAttribute('data-start-time');
            const endTime = card.getAttribute('data-end-time');
            
            if (startTime && endTime && currentTime >= startTime && currentTime <= endTime) {
                card.classList.add('current-time');
            }
        });
    }
    
    // Run highlighting on page load
    highlightCurrentTime();
    
    // Update highlighting every minute
    setInterval(highlightCurrentTime, 60000);
});
</script>
@endsection