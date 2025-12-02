@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <p class="mt-2 text-gray-600">User account details and activity</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}">
                            @csrf
                            <button type="submit" 
                                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition duration-200"
                                    onclick="return confirm('Are you sure you want to {{ $user->is_admin ? 'revoke' : 'grant' }} admin privileges for {{ $user->name }}?')">
                                {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Information -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">User Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Basic Information</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                @if($user->id_card_code)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">ID Card Code</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $user->id_card_code }}</dd>
                                    </div>
                                @endif
                                @if($user->google_id)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Google ID</dt>
                                        <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $user->google_id }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>

                        <!-- Account Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Account Status</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if($user->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Admin Status</dt>
                                    <dd class="mt-1">
                                        @if($user->is_admin)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Admin User
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Regular User
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Division</dt>
                                    <dd class="mt-1">
                                        @if($user->division)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $user->division->name }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">No division assigned</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->format('M j, Y g:i A') }}
                                            <span class="text-gray-500">({{ $user->last_login_at->diffForHumans() }})</span>
                                        @else
                                            Never logged in
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Account Timeline</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Account Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->created_at->format('M j, Y g:i A') }}
                                    <span class="text-gray-500">({{ $user->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $user->updated_at->format('M j, Y g:i A') }}
                                    <span class="text-gray-500">({{ $user->updated_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Password Management (for Admin Login) -->
                @if($user->is_admin)
                <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Admin Password</h2>
                    <p class="text-sm text-gray-600 mb-6">Set a password for this admin user to enable login via /admin/login</p>
                    
                    <form method="POST" action="{{ route('admin.users.update-password', $user) }}" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required
                                    minlength="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
                                    placeholder="Minimum 8 characters"
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    required
                                    minlength="8"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    placeholder="Confirm password"
                                >
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4">
                            <p class="text-xs text-gray-500">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Password will be securely encrypted
                            </p>
                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                            >
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
                @endif

                <!-- Enrollment History -->
                <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Enrollment History</h2>
                    
                    @if($enrollmentHistory->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrolled</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($enrollmentHistory as $enrollment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    @if($enrollment->wellnessSession)
                                                        {{ $enrollment->wellnessSession->title }}
                                                    @elseif($enrollment->scheduleItem)
                                                        {{ $enrollment->scheduleItem->title }}
                                                    @else
                                                        Unknown Session
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($enrollment->wellnessSession)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Wellness
                                                    </span>
                                                @elseif($enrollment->scheduleItem)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Schedule
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($enrollment->wellnessSession)
                                                    {{ $enrollment->wellnessSession->date->format('M j, Y') }}
                                                @elseif($enrollment->scheduleItem)
                                                    {{ $enrollment->scheduleItem->date->format('M j, Y') }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($enrollment->status === 'confirmed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Confirmed
                                                    </span>
                                                @elseif($enrollment->status === 'waitlist')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Waitlist
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($enrollment->status) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $enrollment->created_at->format('M j, Y g:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No enrollments</h3>
                            <p class="mt-1 text-sm text-gray-500">This user hasn't enrolled in any sessions yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- User Avatar -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile</h3>
                    <div class="text-center">
                        @if($user->avatar)
                            <img class="mx-auto h-24 w-24 rounded-full" src="{{ $user->avatar }}" alt="{{ $user->name }}">
                        @else
                            <div class="mx-auto h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-2xl">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h4 class="mt-4 text-lg font-medium text-gray-900">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Enrollments</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $enrollmentHistory->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Confirmed Sessions</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $enrollmentHistory->where('status', 'confirmed')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Waitlist Sessions</dt>
                            <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $enrollmentHistory->where('status', 'waitlist')->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create a simple success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            notification.textContent = '{{ session('success') }}';
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    </script>
@endif
@endsection
