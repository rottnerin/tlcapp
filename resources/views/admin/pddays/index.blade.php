@extends('layouts.app')

@section('title', 'PL Days Management')

@section('content')
<style>
    .card { background: #ffffff; border: 1px solid #e2e8f0; }
    .table-header { background: #f8fafc; }
    .table-row:hover { background: #f8fafc; }
    .action-icon { transition: all 0.15s ease; }
    .action-icon:hover { transform: scale(1.1); }
</style>

<div class="min-h-screen py-8" style="background: #f1f5f9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #1e293b;">PL Days Management</h1>
                <p class="mt-1" style="color: #64748b;">Configure professional learning day events</p>
            </div>
            <a href="{{ route('admin.pddays.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white transition-colors"
               style="background: #2563eb;">
                <i class="fas fa-plus mr-2"></i>Add New PL Day
            </a>
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

        <!-- PL Days Table -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Date Range</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Sessions</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background: #ffffff;">
                        @forelse($pdDays as $pdDay)
                            <tr class="table-row" style="border-bottom: 1px solid #f1f5f9; {{ $pdDay->is_active ? 'background: #f0fdf4;' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($pdDay->is_active)
                                            <span class="flex-shrink-0 mr-2">
                                                <i class="fas fa-check-circle" style="color: #22c55e;"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <div class="font-medium" style="color: #1e293b;">{{ $pdDay->title }}</div>
                                            @if($pdDay->description)
                                                <div class="text-sm" style="color: #64748b;">{{ Str::limit($pdDay->description, 50) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: #64748b;">
                                    {{ $pdDay->date_range }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($pdDay->is_active)
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" style="background: #dcfce7; color: #166534;">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full" style="background: #f1f5f9; color: #64748b;">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: #64748b;">
                                    <div class="flex flex-col">
                                        <span><i class="fas fa-calendar-alt mr-1"></i> Schedule: {{ $pdDay->schedule_items_count }}</span>
                                        <span><i class="fas fa-heart mr-1"></i> Wellness: {{ $pdDay->wellness_sessions_count }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Toggle Active -->
                                        <form method="POST" action="{{ route('admin.pddays.toggle-active', $pdDay) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="action-icon" style="color: {{ $pdDay->is_active ? '#f59e0b' : '#22c55e' }};" title="{{ $pdDay->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $pdDay->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.pddays.edit', $pdDay) }}" class="action-icon" style="color: #2563eb;" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form method="POST" action="{{ route('admin.pddays.destroy', $pdDay) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this PL Day? This action cannot be undone.');">
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
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: #f1f5f9;">
                                            <i class="fas fa-calendar-check text-2xl" style="color: #94a3b8;"></i>
                                        </div>
                                        <p class="font-medium" style="color: #64748b;">No PL Days configured yet</p>
                                        <a href="{{ route('admin.pddays.create') }}" class="mt-2 text-sm font-medium" style="color: #2563eb;">
                                            <i class="fas fa-plus mr-1"></i>Create Your First PL Day
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
        @if($pdDays->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $pdDays->links() }}
            </div>
        @endif

        <!-- Help Info -->
        <div class="mt-6 p-4 rounded-xl" style="background: #eff6ff; border: 1px solid #bfdbfe;">
            <div class="flex items-start">
                <i class="fas fa-info-circle mr-3 mt-0.5" style="color: #3b82f6;"></i>
                <p class="text-sm" style="color: #1e40af;">
                    <strong>Note:</strong> Only one PL Day can be active at a time. The active PL Day determines which events are displayed to users on the public-facing site. When you activate a PL Day, all other PL Days will be automatically deactivated.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
