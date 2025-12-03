@extends('layouts.app')

@section('title', 'Transfer Users - ' . $wellness->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Transfer Users</h1>
                    <p class="mt-2 text-gray-600">Move users from "{{ $wellness->title }}" to another session</p>
                    <div class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        {{ \Carbon\Carbon::parse($wellness->date)->format('M j, Y') }} • 
                        <i class="fas fa-clock mr-1"></i>
                        2:30 PM - 3:30 PM
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.wellness.show', $wellness) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Session
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Current Session Enrollments -->
            <div class="bg-white rounded-lg shadow-card border">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Current Enrollments</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $wellness->userSessions->count() }} confirmed participants</p>
                </div>
                
                <div class="p-6">
                    @if($wellness->userSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($wellness->userSessions as $enrollment)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-aes-blue text-white rounded-full flex items-center justify-center text-sm font-medium">
                                            {{ substr($enrollment->user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $enrollment->user->email }}</div>
                                        </div>
                                    </div>
                                    <button onclick="openTransferModal({{ $enrollment->user->id }}, '{{ $enrollment->user->name }}')" 
                                            class="px-3 py-1 text-sm bg-aes-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-exchange-alt mr-1"></i>
                                        Transfer
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
                            <p>No confirmed enrollments in this session.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Transfer Sessions -->
            <div class="bg-white rounded-lg shadow-card border">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Available Transfer Sessions</h3>
                    <p class="text-sm text-gray-600 mt-1">Other sessions on {{ \Carbon\Carbon::parse($wellness->date)->format('M j, Y') }}</p>
                </div>
                
                <div class="p-6">
                    @if($otherSessions->count() > 0)
                        <div class="space-y-3">
                            @foreach($otherSessions as $session)
                                <div class="p-4 border border-gray-200 rounded-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $session->title }}</h4>
                                            @if($session->category && is_array($session->category) && count($session->category) > 0)
                                                <div class="mt-1">
                                                    @foreach($session->category as $category)
                                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full mr-1">
                                                            {{ $category }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if($session->presenter_name)
                                                <p class="text-sm text-gray-500 mt-1">
                                                    <i class="fas fa-user mr-1"></i>{{ $session->presenter_name }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $session->user_sessions_count }}/{{ $session->max_participants }}
                                            </div>
                                            <div class="text-xs text-gray-500">enrolled</div>
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-aes-blue h-2 rounded-full transition-all" 
                                                     style="width: {{ $session->max_participants > 0 ? ($session->user_sessions_count / $session->max_participants) * 100 : 0 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                            <p>No other sessions available on this date.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div id="transferModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Transfer User</h3>
                <button onclick="closeTransferModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="transferForm" method="POST" action="{{ route('admin.wellness.transfer-user', $wellness) }}">
                @csrf
                <input type="hidden" id="transferUserId" name="user_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">User</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <span id="transferUserName" class="text-sm font-medium text-gray-900"></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="to_session_id" class="block text-sm font-medium text-gray-700 mb-2">Transfer to Session</label>
                    <select name="to_session_id" id="to_session_id" required 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent">
                        <option value="">Select a session...</option>
                        @foreach($otherSessions as $session)
                            <option value="{{ $session->id }}" 
                                    data-capacity="{{ $session->max_participants - $session->user_sessions_count }}">
                                {{ $session->title }} ({{ $session->user_sessions_count }}/{{ $session->max_participants }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-6">
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason (Optional)</label>
                    <textarea name="reason" id="reason" rows="3" 
                              placeholder="Enter reason for transfer..."
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue focus:border-transparent"></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeTransferModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-aes-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Transfer User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openTransferModal(userId, userName) {
    document.getElementById('transferUserId').value = userId;
    document.getElementById('transferUserName').textContent = userName;
    document.getElementById('transferModal').classList.remove('hidden');
}

function closeTransferModal() {
    document.getElementById('transferModal').classList.add('hidden');
    document.getElementById('transferForm').reset();
}

// Close modal when clicking outside
document.getElementById('transferModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTransferModal();
    }
});
</script>
@endsection
