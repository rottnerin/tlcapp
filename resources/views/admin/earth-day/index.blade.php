@extends('layouts.app')

@section('title', 'Earth Day PL - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-tlc-navy">🌍 Earth Day PL Workshops</h1>
            <p class="text-sm text-gray-500 mt-0.5">Manage workshops and view participant lists</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.earth-day.export') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                <i class="fas fa-download text-xs"></i> Export CSV
            </a>
            <a href="{{ route('admin.earth-day.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold btn-orange-to-navy rounded-lg">
                <i class="fas fa-plus text-xs"></i> New Workshop
            </a>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Feature toggle --}}
    <div class="mb-6 p-4 bg-white rounded-xl border border-gray-200 shadow-sm flex items-center justify-between gap-4">
        <div>
            <p class="font-semibold text-gray-800 text-sm">Earth Day Feature Toggle</p>
            <p class="text-xs text-gray-500 mt-0.5">
                Currently: <span class="font-bold {{ $featureActive ? 'text-green-700' : 'text-red-600' }}">{{ $featureActive ? 'Active — tab visible to staff' : 'Inactive — tab hidden from staff' }}</span>
            </p>
        </div>
        <form method="POST" action="{{ route('admin.earth-day.toggle-active') }}">
            @csrf
            <button type="submit"
                    class="px-4 py-2 text-sm font-semibold rounded-lg border transition-colors
                           {{ $featureActive ? 'border-red-300 text-red-700 hover:bg-red-50' : 'border-green-300 text-green-700 hover:bg-green-50' }}">
                {{ $featureActive ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-tlc-navy">{{ $totalWorkshops }}</div>
            <div class="text-xs text-gray-500 mt-0.5 font-medium">Total Workshops</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-green-700">{{ $totalEnrolled }}</div>
            <div class="text-xs text-gray-500 mt-0.5 font-medium">Staff Registered</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-4 text-center shadow-sm">
            <div class="text-2xl font-bold text-tlc-orange">{{ $spotsRemaining }}</div>
            <div class="text-xs text-gray-500 mt-0.5 font-medium">Spots Remaining</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Workshop</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden md:table-cell">Location</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600 hidden lg:table-cell">Date / Time</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Enrolled</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($workshops as $workshop)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <div class="font-semibold text-gray-900">{{ $workshop->title }}</div>
                        @if($workshop->presenter)
                        <div class="text-xs text-gray-500">{{ $workshop->presenter }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $workshop->location ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600 hidden lg:table-cell">
                        {{ $workshop->date->format('M j, Y') }}<br>
                        <span class="text-xs">{{ \Carbon\Carbon::parse($workshop->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($workshop->end_time)->format('g:i A') }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="{{ $workshop->enrollments_count >= \App\Models\EarthDayWorkshop::CAPACITY ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                            {{ $workshop->enrollments_count }}/{{ \App\Models\EarthDayWorkshop::CAPACITY }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.earth-day.toggle-status', $workshop) }}">
                            @csrf
                            <button type="submit"
                                    class="text-xs font-semibold px-2 py-1 rounded-full border transition-colors
                                           {{ $workshop->is_active ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-gray-100 text-gray-500 border-gray-200 hover:bg-gray-200' }}">
                                {{ $workshop->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.earth-day.show', $workshop) }}"
                               class="p-1.5 text-blue-600 hover:text-blue-800 rounded" title="View participants">
                                <i class="fas fa-users text-xs"></i>
                            </a>
                            <a href="{{ route('admin.earth-day.edit', $workshop) }}"
                               class="p-1.5 text-yellow-600 hover:text-yellow-800 rounded" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.earth-day.destroy', $workshop) }}"
                                  onsubmit="return confirm('Delete this workshop? This will remove all enrollments.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 rounded" title="Delete">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400">
                        No workshops yet. <a href="{{ route('admin.earth-day.create') }}" class="text-tlc-navy underline">Add the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($workshops->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $workshops->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
