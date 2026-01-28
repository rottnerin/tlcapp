@extends('layouts.app')

@section('title', 'Session Participant Lists')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Session Participant Lists</h1>
                <p class="text-gray-600">Participant lists by session for presenter distribution</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reports') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg">
                    Back to Reports
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => '1']) }}" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg">
                    Export All to PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
        <form method="GET">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Session Type</label>
                    <select name="session_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="all" {{ $sessionType == 'all' ? 'selected' : '' }}>All Sessions</option>
                        <option value="wellness" {{ $sessionType == 'wellness' ? 'selected' : '' }}>Wellness Only</option>
                        <option value="ccl" {{ $sessionType == 'ccl' ? 'selected' : '' }}>CCL Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="confirmed" {{ $status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="" {{ $status == '' ? 'selected' : '' }}>All</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="{{ $dateFrom }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-lg" value="{{ $dateTo }}">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-lg" style="background-color: var(--tlc-orange);">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Session Cards -->
    <div class="space-y-6">
        @forelse($sessionData as $session)
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Session Header -->
            <div class="px-6 py-4" style="background-color: var(--tlc-navy);">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-xl font-bold" style="color: var(--tlc-cream);">{{ $session['title'] }}</h3>
                        <div class="mt-2 text-sm" style="color: var(--tlc-cream); opacity: 0.9;">
                            <p><strong>Type:</strong> {{ $session['type'] }}</p>
                            <p><strong>Date:</strong> {{ $session['date']->format('l, F j, Y') }}</p>
                            <p><strong>Time:</strong> {{ $session['start_time']->format('g:i A') }} - {{ $session['end_time']->format('g:i A') }}</p>
                            <p><strong>Location:</strong> {{ $session['location'] ?? 'TBD' }}</p>
                            <p><strong>Presenter:</strong> {{ $session['presenter'] ?? 'TBD' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="px-3 py-1 rounded-lg" style="background-color: var(--tlc-gold); color: var(--tlc-navy);">
                            <span class="font-bold">{{ $session['enrolled'] }}</span> / {{ $session['capacity'] ?? '∞' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants Table -->
            <div class="p-6">
                @if($session['participants']->count() > 0)
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-700">Name</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-700">Email</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-700">Division</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-700">Enrolled</th>
                            <th class="text-left py-2 px-3 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($session['participants'] as $participant)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-3 text-sm">{{ $participant->user->name }}</td>
                            <td class="py-3 px-3 text-sm">{{ $participant->user->email }}</td>
                            <td class="py-3 px-3 text-sm">{{ $participant->user->division->name ?? 'N/A' }}</td>
                            <td class="py-3 px-3 text-sm">{{ $participant->enrolled_at->format('M d, Y') }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $participant->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($participant->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-500 text-center py-4">No participants enrolled</p>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <p class="text-gray-500">No sessions found matching the selected filters.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
