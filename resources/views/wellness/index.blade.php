@extends('layouts.user')

@section('title', 'Wellness Sessions - AES Professional Learning Days')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto py-4 sm:py-8 px-4 sm:px-6 lg:px-8">
        <!-- Modern Header -->
        <div class="text-center mb-6 sm:mb-12">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-2xl mb-4 sm:mb-6" style="background-color: #E6B800;">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-2 sm:mb-4">
                Wellness Sessions
            </h1>
            <p class="text-sm sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                Choose from a variety of wellness activities to enhance your professional learning experience.
            </p>
            <div class="mt-4 max-w-2xl mx-auto px-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-sm text-blue-800">
                        <strong>Note:</strong> You can only enroll in one wellness session. If you're already enrolled in a session, you'll need to cancel that enrollment before enrolling in a different session.
                    </p>
                </div>
            </div>
        </div>


        <!-- Modern Sessions Grid -->
        @if($sessions->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($sessions as $session)
                    <div class="group bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 hover:shadow-2xl transition-all duration-300 overflow-hidden {{ $session->status === 'full' ? 'opacity-60' : '' }}">
                        <div class="p-4 sm:p-6">
                            <!-- Status Badge -->
                            <div class="flex justify-between items-start mb-3 sm:mb-4">
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 pr-2">{{ $session->title }}</h3>
                                <span class="px-2 py-1 text-xs font-medium rounded-full flex-shrink-0
                                    {{ $session->status === 'available' ? 'bg-green-100 text-green-800' : 
                                       'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($session->status) }}
                                </span>
                            </div>

                            <!-- Session Details -->
                            <div class="space-y-2 mb-3 sm:mb-4">
                                <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $session->start_time->format('M j, g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                </div>
                                
                                @if($session->location)
                                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="truncate">{{ $session->location }}</span>
                                    </div>
                                @endif

                                @if($session->presenter_name)
                                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="truncate">{{ $session->presenter_name }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Capacity Info -->
                            <div class="mb-3 sm:mb-4">
                                @if($session->status === 'available')
                                    <div class="text-xs sm:text-sm text-green-600 font-medium">
                                        {{ $session->available_spots }} spots available
                                    </div>
                                @else
                                    <div class="text-xs sm:text-sm text-red-600 font-medium">
                                        Full
                                    </div>
                                @endif
                            </div>

                            <!-- Description -->
                            @if($session->description)
                                <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4 line-clamp-3">{{ $session->description }}</p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('wellness.show', $session) }}" 
                                   class="flex-1 text-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    View Details
                                </a>
                                
                                @if($session->isAvailableForEnrollment())
                                    @if($session->userSessions->where('user_id', $user->id)->where('status', '!=', 'cancelled')->count() > 0)
                                        <span class="flex-1 text-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-500 bg-gray-100 rounded-lg">
                                            Enrolled
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('wellness.enroll', $session) }}" class="flex-1" id="enroll-form-{{ $session->id }}">
                                            @csrf
                                            <button type="button" 
                                                   onclick="confirmEnrollment({{ $session->id }}, '{{ $session->title }}')"
                                                    class="w-full px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                Enroll
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="flex-1 text-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-500 bg-gray-100 rounded-lg">
                                        Not Available
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6 sm:mt-8">
                {{ $sessions->links() }}
            </div>
        @else
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 mb-6 sm:mb-8 overflow-hidden">
                <div class="text-center py-12 sm:py-16">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gradient-to-r from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">No wellness sessions found</h3>
                    <p class="text-sm sm:text-base text-gray-600 max-w-md mx-auto px-4">No wellness sessions are currently available. Check back later.</p>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmEnrollment(sessionId, sessionTitle, status) {
        const action = status === 'waitlist' ? 'join the waitlist for' : 'enroll in';
        const message = `Are you sure you want to ${action} "${sessionTitle}"?`;
        
        if (confirm(message)) {
            document.getElementById('enroll-form-' + sessionId).submit();
        }
    }
</script>
@endpush
@endsection
