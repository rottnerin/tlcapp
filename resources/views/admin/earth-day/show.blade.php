@extends('layouts.app')

@section('title', $earthDay->title . ' — Earth Day PL')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('admin.earth-day.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">
            ← Earth Day PL
        </a>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Workshop details --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-tlc-navy">{{ $earthDay->title }}</h1>
                @if($earthDay->presenter)
                <p class="text-sm text-gray-500 mt-0.5">{{ $earthDay->presenter }}</p>
                @endif
            </div>
            <a href="{{ route('admin.earth-day.edit', $earthDay) }}"
               class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                Edit
            </a>
        </div>
        <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
            @if($earthDay->location)
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-0.5">Location</div>
                <div class="text-gray-800">{{ $earthDay->location }}</div>
            </div>
            @endif
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-0.5">Date</div>
                <div class="text-gray-800">{{ $earthDay->date->format('F j, Y') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-0.5">Time</div>
                <div class="text-gray-800">{{ \Carbon\Carbon::parse($earthDay->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($earthDay->end_time)->format('g:i A') }}</div>
            </div>
            <div>
                <div class="text-xs text-gray-400 font-semibold uppercase tracking-wide mb-0.5">Enrollment</div>
                <div class="font-semibold {{ $earthDay->isFull() ? 'text-red-600' : 'text-green-700' }}">
                    {{ $earthDay->current_enrollment }} / {{ \App\Models\EarthDayWorkshop::CAPACITY }}
                </div>
            </div>
        </div>
        @if($earthDay->description)
        <div class="px-6 pb-4 text-sm text-gray-600">{{ $earthDay->description }}</div>
        @endif
    </div>

    {{-- Participants --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">
                Participants
                <span class="ml-1.5 text-xs font-normal text-gray-400">({{ $earthDay->enrollments->count() }})</span>
            </h2>
        </div>

        @if($earthDay->enrollments->isEmpty())
        <div class="px-6 py-8 text-center text-gray-400 text-sm">No one has enrolled yet.</div>
        @else
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden sm:table-cell">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden md:table-cell">Division</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden lg:table-cell">Enrolled</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Remove</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($earthDay->enrollments as $enrollment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $enrollment->user->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $enrollment->user->email ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $enrollment->user->division->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs hidden lg:table-cell">
                        {{ $enrollment->enrolled_at?->format('M j, g:i A') ?? '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.earth-day.remove-enrollment', $earthDay) }}"
                              onsubmit="return confirm('Remove {{ addslashes($enrollment->user->name ?? 'this user') }} from this workshop?')">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $enrollment->user_id }}">
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection
