@extends('layouts.app')

@section('title', 'Manage PL Wednesday Sessions')

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
                <h1 class="text-2xl font-bold" style="color: #1e293b;">PL Wednesday Sessions</h1>
                <p class="mt-1" style="color: #64748b;">Manage Professional Learning Wednesday sessions</p>
            </div>
            <a href="{{ route('admin.pl-wednesday.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white transition-colors"
               style="background: #2563eb;">
                <i class="fas fa-plus mr-2"></i>Add New Session
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

        <!-- Feature Settings Card -->
        <div class="card rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold" style="color: #1e293b;">PL Wednesday Feature</h3>
                    <p class="text-sm mt-1" style="color: #64748b;">
                        @if($settings)
                            Active from {{ $settings->start_date->format('M j, Y') }} to {{ $settings->end_date->format('M j, Y') }}
                        @endif
                    </p>
                </div>
                <form action="{{ route('admin.pl-wednesday.toggle-active') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 rounded-lg font-medium transition-colors text-white"
                            style="background: {{ $settings && $settings->is_active ? '#10b981' : '#94a3b8' }};">
                        <i class="fas fa-{{ $settings && $settings->is_active ? 'check' : 'times' }} mr-2"></i>
                        {{ $settings && $settings->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="card rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="table-header">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Division</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Location</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Links</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: #64748b;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background: #ffffff;">
                        @forelse($sessions as $session)
                            <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #1e293b;">
                                    <i class="fas fa-calendar-alt mr-2" style="color: #64748b;"></i>
                                    {{ $session->date->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-medium" style="color: #1e293b;">{{ $session->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #64748b;">
                                    {{ $session->division ? $session->division->name : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #64748b;">
                                    <i class="fas fa-clock mr-1" style="color: #64748b;"></i>
                                    {{ $session->formatted_time }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #64748b;">
                                    <i class="fas fa-map-marker-alt mr-1" style="color: #64748b;"></i>
                                    {{ $session->location ?? '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: #64748b;">
                                    <i class="fas fa-link mr-1" style="color: #64748b;"></i>
                                    {{ $session->links->count() }} link(s)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('admin.pl-wednesday.toggle-status', $session) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                                                style="background: {{ $session->is_active ? '#dcfce7' : '#f1f5f9' }}; color: {{ $session->is_active ? '#166534' : '#64748b' }};">
                                            <i class="fas fa-{{ $session->is_active ? 'check' : 'times' }} mr-1"></i>
                                            {{ $session->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-3">
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.pl-wednesday.edit', $session) }}" 
                                           class="action-icon inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                                           style="background: #dbeafe; color: #1d4ed8;"
                                           title="Edit Session">
                                            <i class="fas fa-edit mr-1.5"></i>Edit
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{ route('admin.pl-wednesday.destroy', $session) }}" 
                                              method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this PL Wednesday session? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-icon inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                                                    style="background: #fee2e2; color: #991b1b;"
                                                    title="Delete Session">
                                                <i class="fas fa-trash mr-1.5"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background: #f1f5f9;">
                                            <i class="fas fa-book text-2xl" style="color: #94a3b8;"></i>
                                        </div>
                                        <p class="font-medium" style="color: #64748b;">No sessions found</p>
                                        <a href="{{ route('admin.pl-wednesday.create') }}" 
                                           class="mt-2 text-sm font-medium" style="color: #2563eb;">
                                            <i class="fas fa-plus mr-1"></i>Create one
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($sessions->hasPages())
                <div class="px-6 py-4" style="border-top: 1px solid #e2e8f0;">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

