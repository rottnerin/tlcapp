@extends('layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Reports Dashboard</h1>
        <p class="text-gray-600">Comprehensive analytics and insights for your wellness program</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-3xl font-bold text-blue-600">{{ $totalUsers ?? 0 }}</div>
            <div class="text-sm text-gray-600">Total Users</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-3xl font-bold text-green-600">{{ $totalEnrollments ?? 0 }}</div>
            <div class="text-sm text-gray-600">Total Enrollments</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-3xl font-bold text-purple-600">{{ $activeSessions ?? 0 }}</div>
            <div class="text-sm text-gray-600">Active Sessions</div>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="text-3xl font-bold text-orange-600">{{ $divisions ?? 0 }}</div>
            <div class="text-sm text-gray-600">Divisions</div>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <!-- Wellness Enrollments Report -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Wellness Enrollments</h3>
                    <p class="text-sm text-gray-600">Session enrollment details</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">View all wellness session enrollments with detailed user information, ratings, and attendance tracking.</p>
            <a href="{{ route('admin.reports.wellness-enrollments') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Report
            </a>
        </div>

        <!-- Unenrolled Users Report -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Unenrolled Users</h3>
                    <p class="text-sm text-gray-600">Find inactive users</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Identify users who haven't enrolled in any sessions and track their engagement levels.</p>
            <a href="{{ route('admin.reports.unenrolled-users') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                View Report
            </a>
        </div>

        <!-- Capacity Utilization Report -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Capacity Utilization</h3>
                    <p class="text-sm text-gray-600">Session capacity analysis</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Analyze session capacity and enrollment rates to optimize resource allocation.</p>
            <a href="{{ route('admin.reports.capacity-utilization') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Report
            </a>
        </div>

        <!-- Division Summary Report -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Division Summary</h3>
                    <p class="text-sm text-gray-600">Division analytics</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">View enrollment statistics by division with participation rates and engagement metrics.</p>
            <a href="{{ route('admin.reports.division-summary') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                View Report
            </a>
        </div>

        <!-- User Activity Report -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">User Activity</h3>
                    <p class="text-sm text-gray-600">Activity tracking</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">Track user enrollment activity and engagement patterns across the platform.</p>
            <a href="{{ route('admin.reports.user-activity') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View Report
            </a>
        </div>

        <!-- Export Tools -->
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Export Tools</h3>
                    <p class="text-sm text-gray-600">Data export options</p>
                </div>
            </div>
            <p class="text-gray-700 mb-4">All reports support CSV export with filtered data and timestamped filenames.</p>
            <div class="text-sm text-gray-600">
                <div class="flex items-center mb-1">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    CSV Export Available
                </div>
                <div class="flex items-center mb-1">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Advanced Filtering
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Real-time Data
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.wellness.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-lg font-medium">🧘 Manage Sessions</div>
                <div class="text-sm opacity-90">Create and edit wellness sessions</div>
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-lg font-medium">👥 Manage Users</div>
                <div class="text-sm opacity-90">View and edit user accounts</div>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg text-center transition duration-200">
                <div class="text-lg font-medium">📊 Dashboard</div>
                <div class="text-sm opacity-90">Return to main dashboard</div>
            </a>
        </div>
    </div>
</div>
@endsection
