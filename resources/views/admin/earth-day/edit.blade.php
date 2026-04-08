@extends('layouts.app')

@section('title', 'Edit — ' . $earthDay->title)

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6">
        <a href="{{ route('admin.earth-day.index') }}" class="text-gray-400 hover:text-gray-600 text-sm">← Earth Day PL</a>
        <h1 class="text-xl font-bold text-tlc-navy mt-2">Edit Workshop</h1>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
    @endif
    @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="POST" action="{{ route('admin.earth-day.update', $earthDay) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $earthDay->title) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Presenter</label>
                <input type="text" name="presenter" value="{{ old('presenter', $earthDay->presenter) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location', $earthDay->location) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">{{ old('description', $earthDay->description) }}</textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', $earthDay->date->format('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($earthDay->start_time)->format('H:i')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($earthDay->end_time)->format('H:i')) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $earthDay->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 accent-green-600">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active (visible to staff)</label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="px-5 py-2 text-sm font-semibold btn-orange-to-navy rounded-lg btn-primary-action">
                    Save Changes
                </button>
                <a href="{{ route('admin.earth-day.show', $earthDay) }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Participants (inline) --}}
    @if($earthDay->enrollments->isNotEmpty())
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 text-sm">
                Participants
                <span class="text-gray-400 font-normal">({{ $earthDay->enrollments->count() }})</span>
            </h2>
        </div>
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden sm:table-cell">Email</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Remove</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($earthDay->enrollments as $enrollment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $enrollment->user->name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden sm:table-cell">{{ $enrollment->user->email ?? '—' }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.earth-day.remove-enrollment', $earthDay) }}"
                              onsubmit="return confirm('Remove {{ addslashes($enrollment->user->name ?? 'this user') }}?')">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $enrollment->user_id }}">
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
