@extends('layouts.app')

@section('title', 'Manage Schedule Items')

@section('content')
<div class="min-h-screen bg-content py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Schedule Items</h1>
                <p class="text-gray-600 mt-1">Manage the professional development schedule</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedule.by-pdday') }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-md">
                    <i class="fas fa-calendar mr-2"></i>By PD Day
                </a>
                <button onclick="toggleBulkActions()" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg font-medium transition-colors shadow-md">
                    <i class="fas fa-tasks mr-2"></i>Bulk Actions
                </button>
                <a href="{{ route('admin.schedule.create') }}" 
                   class="bg-aes-blue hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors shadow-md">
                    <i class="fas fa-plus mr-2"></i>Add Schedule Item
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

        <!-- Bulk Actions Bar (Hidden by default) -->
        <div id="bulkActionsBar" class="hidden bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 shadow-content">
            <form action="{{ route('admin.schedule.bulk-update') }}" method="POST" id="bulkForm">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-gray-700">
                            <span id="selectedCount">0</span> items selected
                        </span>
                        <select name="action" required class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-aes-blue">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Apply
                        </button>
                    </div>
                    <button type="button" onclick="toggleBulkActions()" 
                            class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-card mb-8 p-6 border">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filters</h3>
            <form method="GET" action="{{ route('admin.schedule.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Title, presenter, location..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                    <select name="division_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                        <option value="">All Divisions</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="session_type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}" {{ request('session_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <select name="date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                        <option value="">All Dates</option>
                        @foreach($availableDates as $date)
                            <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-aes-blue">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Schedule Items Table -->
        <div class="bg-white rounded-lg shadow-card overflow-hidden border">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" 
                                       class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Division</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($scheduleItems as $item)
                            <tr class="hover:bg-gray-50" data-item-color="{{ $item->color }}">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                           onchange="updateSelectedCount()"
                                           class="h-4 w-4 text-aes-blue border-gray-300 rounded focus:ring-aes-blue item-checkbox">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->color)
                                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $item->color }}"></div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->title }}</div>
                                            @if($item->presenter_name)
                                                <div class="text-sm text-gray-500">{{ $item->presenter_name }}</div>
                                            @endif
                                            @if($item->location)
                                                <div class="text-sm text-gray-500">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $item->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($item->divisions->count() > 0)
                                        @foreach($item->divisions as $division)
                                            <span class="inline-block px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full mr-1 mb-1">
                                                {{ $division->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">All Divisions</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>{{ \Carbon\Carbon::parse($item->start_time)->format('M j, Y') }}</div>
                                    <div class="text-gray-500">
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                bg-blue-100 text-blue-800">
                                        {{ ucfirst($item->session_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.schedule.show', $item) }}" 
                                           class="text-aes-blue hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schedule.edit', $item) }}" 
                                           class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schedule.toggle-status', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-gray-600 hover:text-gray-900" 
                                                    title="{{ $item->is_active ? 'Hide from schedule' : 'Show in schedule' }}">
                                                <i class="fas fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.schedule.destroy', $item) }}" method="POST" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                    No schedule items found. 
                                    <a href="{{ route('admin.schedule.create') }}" class="text-aes-blue hover:underline">
                                        Create your first schedule item
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($scheduleItems->hasPages())
            <div class="mt-6">
                {{ $scheduleItems->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>

<script>
function toggleBulkActions() {
    const bulkBar = document.getElementById('bulkActionsBar');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    
    bulkBar.classList.toggle('hidden');
    
    if (bulkBar.classList.contains('hidden')) {
        // Reset all checkboxes
        checkboxes.forEach(cb => cb.checked = false);
        document.getElementById('selectAll').checked = false;
        updateSelectedCount();
    }
}

function toggleAllCheckboxes() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.item-checkbox');
    
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
    const countSpan = document.getElementById('selectedCount');
    const bulkForm = document.getElementById('bulkForm');
    
    countSpan.textContent = checkedBoxes.length;
    
    // Add hidden inputs for selected items
    const existingInputs = bulkForm.querySelectorAll('input[name="items[]"]');
    existingInputs.forEach(input => input.remove());
    
    checkedBoxes.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'items[]';
        input.value = cb.value;
        bulkForm.appendChild(input);
    });
}

// Handle bulk form submission
document.getElementById('bulkForm').addEventListener('submit', function(e) {
    const action = this.querySelector('select[name="action"]').value;
    const selectedCount = document.querySelectorAll('.item-checkbox:checked').length;
    
    if (!action) {
        e.preventDefault();
        alert('Please select an action');
        return;
    }
    
    if (selectedCount === 0) {
        e.preventDefault();
        alert('Please select at least one item');
        return;
    }
    
    const confirmMessage = `Are you sure you want to ${action} ${selectedCount} item(s)?`;
    if (!confirm(confirmMessage)) {
        e.preventDefault();
    }
});
</script>
@endsection
