@extends('layouts.app')

@section('title', 'Manage Schedule Items')

@section('content')
<style>
    .card { background: #ffffff; border: 1px solid #e2e8f0; }
    .card-header { border-bottom: 1px solid #e2e8f0; }
    .table-header { background: #f8fafc; }
    .table-row:hover { background: #f8fafc; }
    .action-icon { transition: all 0.15s ease; }
    .action-icon:hover { transform: scale(1.1); }
</style>

<div class="min-h-screen py-8" style="background: #f1f5f9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #1e293b;">Schedule Items</h1>
                <p class="mt-1" style="color: #64748b;">Showing {{ $scheduleItems->firstItem() ?? 0 }} to {{ $scheduleItems->lastItem() ?? 0 }} of {{ $scheduleItems->total() }} items</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.schedule.by-pdday') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-colors" 
                   style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">
                    <i class="fas fa-calendar mr-2"></i>By PL Day
                </a>
                <button onclick="toggleBulkActions()" 
                        class="inline-flex items-center px-4 py-2 rounded-lg font-medium transition-colors"
                        style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">
                    <i class="fas fa-tasks mr-2"></i>Bulk Actions
                </button>
                <a href="{{ route('admin.schedule.create') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white transition-colors"
                   style="background: #2563eb;">
                    <i class="fas fa-plus mr-2"></i>Add Schedule Item
                </a>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                <i class="fas fa-check-circle mr-3"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                <i class="fas fa-exclamation-circle mr-3"></i>{{ session('error') }}
            </div>
        @endif

        <!-- Bulk Actions Bar (Hidden by default) -->
        <div id="bulkActionsBar" class="hidden mb-6 p-4 rounded-xl" style="background: #fefce8; border: 1px solid #fde047;">
            <form action="{{ route('admin.schedule.bulk-update') }}" method="POST" id="bulkForm">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium" style="color: #854d0e;">
                            <span id="selectedCount">0</span> items selected
                        </span>
                        <select name="action" required class="rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #ffffff; border: 1px solid #d1d5db; color: #374151;">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate</option>
                            <option value="deactivate">Deactivate</option>
                            <option value="delete">Delete</option>
                        </select>
                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-medium text-white" style="background: #ca8a04;">
                            Apply
                        </button>
                    </div>
                    <button type="button" onclick="toggleBulkActions()" style="color: #854d0e;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Filters Card -->
        <div class="card rounded-2xl shadow-sm mb-6 overflow-hidden">
            <div class="p-4 card-header">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold" style="color: #1e293b;">
                        <i class="fas fa-filter mr-2" style="color: #64748b;"></i>Filters
                    </h3>
                    @if(request()->hasAny(['search', 'division_id', 'session_type', 'date', 'status']))
                        <a href="{{ route('admin.schedule.index') }}" class="text-sm font-medium" style="color: #dc2626;">
                            <i class="fas fa-times mr-1"></i>Clear All
                        </a>
                    @endif
                </div>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('admin.schedule.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #475569;">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Title, presenter..."
                               class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #475569;">Division</label>
                        <select name="division_id" class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
                            <option value="">All Divisions</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                    {{ $division->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #475569;">Type</label>
                        <select name="session_type" class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('session_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #475569;">Date</label>
                        <select name="date" class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
                            <option value="">All Dates</option>
                            @foreach($availableDates as $date)
                                <option value="{{ $date }}" {{ request('date') == $date ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($date)->format('M j, Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: #475569;">Status</label>
                        <select name="status" class="w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #ffffff; border: 1px solid #e2e8f0; color: #1e293b;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 rounded-lg font-medium text-white transition-colors" style="background: #475569;">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Schedule Items Table -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" 
                                       class="h-4 w-4 rounded" style="accent-color: #2563eb;">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Item</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Division</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Date & Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background: #ffffff;">
                        @forelse($scheduleItems as $item)
                            <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                                           onchange="updateSelectedCount()"
                                           class="h-4 w-4 rounded item-checkbox" style="accent-color: #2563eb;">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-start">
                                        @if($item->color)
                                            <div class="w-1 h-12 rounded-full mr-3 flex-shrink-0" style="background-color: {{ $item->color }}"></div>
                                        @endif
                                        <div>
                                            <div class="font-medium" style="color: #1e293b;">{{ $item->title }}</div>
                                            @if($item->session_type)
                                                <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-md mt-1" style="background: #dbeafe; color: #1d4ed8;">
                                                    {{ ucfirst($item->session_type) }}
                                                </span>
                                            @endif
                                            @if($item->location)
                                                <div class="text-sm mt-1" style="color: #64748b;">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $item->location }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->divisions->count() > 0)
                                        @foreach($item->divisions as $division)
                                            <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-md mr-1 mb-1" style="background: #e0f2fe; color: #0369a1;">
                                                {{ $division->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span style="color: #64748b;">All Divisions</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium" style="color: #1e293b;">{{ \Carbon\Carbon::parse($item->start_time)->format('M j, Y') }}</div>
                                    <div class="text-sm" style="color: #64748b;">
                                        {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }} - 
                                        {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_required)
                                        <span class="inline-block px-2.5 py-1 text-xs font-medium rounded-full" style="background: #fef3c7; color: #b45309;">
                                            Required
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-1 text-xs font-medium rounded-full" style="background: #f1f5f9; color: #64748b;">
                                            Optional
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->is_active)
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" style="background: #dcfce7; color: #166534;">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" style="background: #fee2e2; color: #991b1b;">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.schedule.show', $item) }}" 
                                           class="action-icon" style="color: #2563eb;" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schedule.edit', $item) }}" 
                                           class="action-icon" style="color: #059669;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schedule.toggle-status', $item) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="action-icon" style="color: #f59e0b;" 
                                                    title="{{ $item->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $item->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.schedule.destroy', $item) }}" method="POST" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this schedule item?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon" style="color: #dc2626;" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: #f1f5f9;">
                                            <i class="fas fa-calendar-alt text-2xl" style="color: #94a3b8;"></i>
                                        </div>
                                        <p class="font-medium" style="color: #64748b;">No schedule items found</p>
                                        <a href="{{ route('admin.schedule.create') }}" class="mt-2 text-sm font-medium" style="color: #2563eb;">
                                            <i class="fas fa-plus mr-1"></i>Create your first schedule item
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
        @if($scheduleItems->hasPages())
            <div class="mt-6 flex justify-center">
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
