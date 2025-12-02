@extends('layouts.app')

@section('title', 'Manage Wellness Sessions')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Wellness Sessions</h1>
                    <p class="mt-2 text-gray-600">Manage wellness sessions and track enrollments</p>
                </div>
                <a href="{{ route('admin.wellness.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-aes-blue text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Session
                </a>
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
                            <i class="fas fa-spa text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Sessions</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalSessions ?? 0 }}</p>
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
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeSessions ?? 0 }}</p>
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
                        <p class="text-sm font-medium text-gray-500">Total Enrollments</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalEnrollments ?? 0 }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-card p-6 border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Avg. Enrollment</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $avgEnrollment ?? '0%' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-card mb-8 p-6 border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
            <form method="GET" action="{{ route('admin.wellness.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Title, presenter, category..."
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <select name="date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent">
                        <option value="">All Dates</option>
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
                
                <div class="flex items-end">
                    <a href="{{ route('admin.wellness.index') }}" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors text-center font-medium">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Sessions Table -->
        <div class="bg-white rounded-lg shadow-card border overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Wellness Sessions</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($sessions->count() > 0)
                                Showing {{ $sessions->firstItem() }} to {{ $sessions->lastItem() }} of {{ $sessions->total() }} sessions
                            @else
                                No sessions found
                            @endif
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <button class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-download mr-2"></i>Export
                        </button>
                        <button class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-cog mr-2"></i>Bulk Actions
                        </button>
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollment</th>
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
                                        @if($session->category && is_array($session->category) && count($session->category) > 0)
                                            <div class="text-sm text-gray-500 mt-1">
                                                @foreach($session->category as $category)
                                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full mr-1">
                                                        {{ $category }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @elseif($session->category)
                                            <div class="text-sm text-gray-500 mt-1">
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                                    {{ ucfirst($session->category) }}
                                                </span>
                                            </div>
                                        @endif
                                        @if($session->location)
                                            <div class="text-sm text-gray-500 mt-1">
                                                <i class="fas fa-map-marker-alt mr-1"></i>{{ $session->location }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium">{{ \Carbon\Carbon::parse($session->date)->format('M j, Y') }}</div>
                                    <div class="text-gray-500">
                                        2:30 PM - 3:30 PM
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
                                    <div class="flex items-center">
                                        <span class="mr-3 font-medium">{{ $session->user_sessions_count }}/{{ $session->max_participants }}</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="bg-aes-blue h-2 rounded-full transition-all" 
                                                 style="width: {{ $session->max_participants > 0 ? ($session->user_sessions_count / $session->max_participants) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $session->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $session->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a href="{{ route('admin.wellness.show', $session) }}" 
                                           class="text-aes-blue hover:text-blue-900 transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.wellness.edit', $session) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($session->user_sessions_count > 0)
                                            <a href="{{ route('admin.wellness.transfer', $session) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors" title="Transfer Users">
                                                <i class="fas fa-exchange-alt"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('admin.wellness.toggle-status', $session) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-600 hover:text-gray-900 transition-colors" 
                                                    title="{{ $session->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $session->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        @if($session->user_sessions_count == 0)
                                            <form action="{{ route('admin.wellness.destroy', $session) }}" method="POST" 
                                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this session?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-spa text-6xl text-gray-300 mb-6"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">No wellness sessions found</h3>
                                        <p class="text-gray-500 mb-6">Get started by creating your first wellness session.</p>
                                        <a href="{{ route('admin.wellness.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-aes-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
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
@endsection
