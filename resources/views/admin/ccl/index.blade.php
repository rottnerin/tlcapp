@extends('layouts.app')

@section('title', 'Manage CCL Sessions')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Collaborative Community Learning Sessions</h1>
                    <p class="mt-2 text-gray-600">Manage CCL sessions and track enrollments</p>
                </div>
                <div class="flex gap-3">
                    <button class="inline-flex items-center px-4 py-2 rounded-lg transition-colors shadow-sm font-medium
                                {{ $settings->is_active ? 'bg-green-600 hover:bg-green-700 text-white' : 'bg-gray-300 hover:bg-gray-400 text-gray-700' }}"
                            id="toggle-ttt-btn">
                        <i class="fas {{ $settings->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i>
                        CCL {{ $settings->is_active ? 'Active' : 'Inactive' }}
                    </button>
                    <a href="{{ route('admin.ccl.create') }}"
                       class="inline-flex items-center px-4 py-2 text-white rounded-lg transition-colors shadow-sm font-medium"
                       style="background-color: var(--tlc-orange);">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Session
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-content">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg mb-6 shadow-content">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-card p-6 border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $sessions->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-card p-6 border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $sessions->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-card p-6 border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-yellow-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">PD Days</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $sessions->whereNotNull('p_d_day_id')->unique('p_d_day_id')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-card p-6 border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: rgba(244, 211, 94, 0.3);">
                            <i class="fas fa-calendar-alt" style="color: var(--tlc-navy);"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Upcoming</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $sessions->filter(function($s) { return $s->date >= now(); })->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="bg-white rounded-lg shadow-card border overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">CCL Sessions</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($sessions->count() > 0)
                                Showing {{ $sessions->firstItem() }} to {{ $sessions->lastItem() }} of {{ $sessions->total() }} sessions
                            @else
                                No sessions found
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presenter</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PD Day</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $session->title }}</div>
                                        @if($session->location)
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $session->location }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ $session->date->format('M j, Y') }}</div>
                                    <div class="text-gray-500">
                                        @if($session->start_time && $session->end_time)
                                            {{ $session->start_time->format('g:i A') }} - {{ $session->end_time->format('g:i A') }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>
                                        <div class="font-medium">{{ $session->presenter_name ?: 'TBD' }}</div>
                                        @if($session->co_presenter_name)
                                            <div class="text-gray-500 text-xs mt-1">
                                                Co: {{ $session->co_presenter_name }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($session->pdDay)
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            {{ $session->pdDay->title }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $session->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $session->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.ccl.show', $session) }}" 
                                           class="text-aes-blue hover:text-blue-900 transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.ccl.edit', $session) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ccl.toggle-status', $session) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors" 
                                                    title="{{ $session->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $session->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.ccl.destroy', $session) }}" method="POST" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-chalkboard-teacher text-6xl text-gray-300 mb-6"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No CCL sessions found</h3>
                                        <p class="text-gray-500 mb-6">Get started by creating your first CCL session.</p>
                                        <a href="{{ route('admin.ccl.create') }}" 
                                           class="inline-flex items-center px-4 py-2 text-white rounded-lg transition-colors" style="background-color: var(--tlc-orange);" onmouseover="this.style.backgroundColor='#0d3b66'" onmouseout="this.style.backgroundColor='#ee964b'">
                                            <i class="fas fa-plus mr-2"></i>
                                            Add New Session
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
            <div class="mt-6">
                {{ $sessions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.getElementById('toggle-ttt-btn').addEventListener('click', function() {
    const btn = this;
    fetch('{{ route('admin.ccl.toggle-active') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.is_active) {
                btn.className = 'inline-flex items-center px-4 py-2 rounded-lg transition-colors shadow-sm font-medium bg-green-600 hover:bg-green-700 text-white';
                btn.innerHTML = '<i class="fas fa-toggle-on mr-2"></i> CCL Active';
            } else {
                btn.className = 'inline-flex items-center px-4 py-2 rounded-lg transition-colors shadow-sm font-medium bg-gray-300 hover:bg-gray-400 text-gray-700';
                btn.innerHTML = '<i class="fas fa-toggle-off mr-2"></i> CCL Inactive';
            }
        }
    });
});
</script>
@endpush
@endsection
