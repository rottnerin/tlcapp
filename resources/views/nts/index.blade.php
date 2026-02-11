@extends('layouts.user')

@section('title', 'NTS Sessions - TLC Professional Learning')

@section('content')
<style>
:root {
    --tlc-navy: #0d3b66;
    --tlc-cream: #faf0ca;
    --tlc-gold: #f4d35e;
    --tlc-orange: #ee964b;
}

body {
    background-color: var(--tlc-cream);
    color: var(--tlc-navy);
}

.section-header {
    background: var(--tlc-gold);
    color: var(--tlc-navy);
    padding: 0.75rem;
    border-radius: 0.75rem;
    font-weight: 600;
}

.optional-signup-card {
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
    margin-bottom: 0.75rem;
    border: 2px solid var(--tlc-gold);
    transition: all 0.3s ease;
}

.optional-signup-time {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--tlc-navy) 0%, #164773 100%);
    color: white;
    padding: 0.6rem 1rem;
    border-radius: 0.75rem;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 0.75rem;
    box-shadow: 0 4px 12px rgba(13, 59, 102, 0.3);
}
.optional-signup-time .time-day { opacity: 0.9; font-size: 0.85rem; font-weight: 600; }
.optional-signup-time .time-slot { font-size: 1.15rem; letter-spacing: 0.02em; }

.optional-signup-card.joined {
    background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(129, 199, 132, 0.1) 100%);
    border-color: #4CAF50;
}
</style>

<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold text-tlc-navy mb-2">NTS Sessions</h1>
        <p class="text-gray-600 mb-8">Optional Sign-up for Non-Teaching Staff</p>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">{{ session('info') }}</div>
        @endif

        {{-- Optional Sign-up Section --}}
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="section-header">
                <div class="flex items-center justify-center">
                    <div class="text-3xl mr-4">📝</div>
                    <div class="text-center">
                        <h2 class="text-2xl font-bold">Optional Sign-up</h2>
                        <p class="text-sm mt-1 opacity-90">Choose one time slot. After you sign up, other options will be hidden.</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if($userOptionalEnrollment)
                    <div class="optional-signup-card joined">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">✓</span>
                            <span class="font-semibold text-green-800">You're enrolled</span>
                        </div>
                        <div class="optional-signup-time">
                            <span class="time-day">{{ $userOptionalEnrollment->scheduleItem->date->format('l, F j') }}</span>
                            <span class="time-slot">{{ $userOptionalEnrollment->scheduleItem->start_time->format('g:i A') }} – {{ $userOptionalEnrollment->scheduleItem->end_time->format('g:i A') }}</span>
                        </div>
                        <p class="text-gray-700">You are enrolled in this session.</p>
                        @if($user->isAdmin())
                            <form method="POST" action="{{ route('spring.nts.optional.unjoin', $userOptionalEnrollment->scheduleItem) }}" class="mt-4">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium">Unjoin</button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($optionalSignupItems as $optItem)
                            <div class="optional-signup-card">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <div class="optional-signup-time">
                                            <span class="time-day">{{ $optItem->date->format('l, M j') }}</span>
                                            <span class="time-slot">{{ $optItem->start_time->format('g:i A') }} – {{ $optItem->end_time->format('g:i A') }}</span>
                                        </div>
                                        <h3 class="font-semibold text-tlc-navy">{{ $optItem->title }}</h3>
                                        @if($optItem->description)
                                            <p class="text-sm text-gray-500 mt-2">{{ $optItem->description }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('spring.nts.optional.join', $optItem) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-tlc-navy hover:bg-blue-800 text-white rounded-lg font-medium">Join</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                        @if($optionalSignupItems->isEmpty())
                            <p class="text-gray-500 py-4">No Optional Sign-up sessions available.</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
