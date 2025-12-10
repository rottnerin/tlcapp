@extends('layouts.app')

@section('title', 'Schedule by PL Days')

@section('content')
<style>
    .card { background: #ffffff; border: 1px solid #e2e8f0; }
    .pdday-header { background: linear-gradient(135deg, #1e293b 0%, #334155 100%); }
</style>

<div class="min-h-screen py-8" style="background: #f1f5f9;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #1e293b;">Schedule by PL Days</h1>
                <p class="mt-1" style="color: #64748b;">Manage schedule items grouped by PL day events</p>
            </div>
            <a href="{{ route('admin.schedule.create') }}" 
               class="inline-flex items-center px-4 py-2 rounded-lg font-medium text-white transition-colors"
               style="background: #2563eb;">
                <i class="fas fa-plus mr-2"></i>Add Schedule Item
            </a>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
                <i class="fas fa-check-circle mr-3"></i>{{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl flex items-center" style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                <i class="fas fa-exclamation-circle mr-3"></i>{{ session('error') }}
            </div>
        @endif

        <!-- PL Days Schedule Sections -->
        <div class="space-y-6">
            @forelse($pdDaysWithCounts as $item)
                @php
                    $pdDay = $item['pdDay'];
                    $scheduleCount = $item['scheduleCount'];
                    $scheduleItems = $item['scheduleItems'];
                @endphp
                
                <div class="card rounded-2xl shadow-sm overflow-hidden">
                    <!-- PL Day Header -->
                    <div class="pdday-header px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-white">{{ $pdDay->title }}</h2>
                                <p class="text-gray-300 text-sm">{{ $pdDay->date_range }}</p>
                                @if($pdDay->description)
                                    <p class="text-gray-300 text-sm mt-1">{{ $pdDay->description }}</p>
                                @endif
                            </div>
                            <div class="text-right flex items-center space-x-2">
                                <span class="inline-block px-3 py-1 bg-white text-gray-800 rounded-full text-sm font-semibold">
                                    {{ $scheduleCount }} items
                                </span>
                                @if($pdDay->is_active)
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background: #dcfce7; color: #166534;">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- PL Day Content -->
                    <div class="p-6">
                        @if($scheduleCount > 0)
                            <!-- Schedule Items List -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-4" style="color: #1e293b;">Schedule Items</h3>
                                <div class="space-y-3">
                                    @foreach($scheduleItems as $schedule)
                                        <div class="flex items-start justify-between p-4 rounded-xl" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-medium" style="color: #1e293b;">{{ $schedule->title }}</p>
                                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-md
                                                        @if($schedule->session_type === 'Wellness')
                                                            " style="background: #dbeafe; color: #1d4ed8;">
                                                        @else
                                                            " style="background: #ede9fe; color: #6d28d9;">
                                                        @endif
                                                        {{ $schedule->session_type ?? 'Fixed' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm" style="color: #64748b;">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $schedule->location ?? 'No location' }}
                                                </p>
                                                @if($schedule->wellnessSession)
                                                    <p class="text-xs mt-1" style="color: #059669;">
                                                        <i class="fas fa-link mr-1"></i>Linked to: {{ $schedule->wellnessSession->title }}
                                                    </p>
                                                @elseif($schedule->session_type === 'Wellness')
                                                    <p class="text-xs mt-1" style="color: #f59e0b;">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>Not linked to a wellness session
                                                    </p>
                                                @endif
                                                @if($schedule->presenter_primary)
                                                    <p class="text-xs mt-1" style="color: #64748b;">
                                                        <i class="fas fa-user mr-1"></i>{{ $schedule->presenter_primary }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                @if($schedule->is_active)
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background: #dcfce7; color: #166534;">Active</span>
                                                @else
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full" style="background: #f1f5f9; color: #64748b;">Inactive</span>
                                                @endif
                                                <a href="{{ route('admin.schedule.edit', $schedule) }}" style="color: #2563eb;" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-wrap gap-3 pt-4" style="border-top: 1px solid #e2e8f0;">
                                <a href="{{ route('admin.schedule.copy-form', $pdDay) }}" 
                                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium" 
                                   style="background: #dbeafe; color: #1d4ed8;">
                                    <i class="fas fa-copy mr-2"></i>Copy Schedule
                                </a>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: #f1f5f9;">
                                    <i class="fas fa-file-alt text-2xl" style="color: #94a3b8;"></i>
                                </div>
                                <p class="text-sm" style="color: #64748b;">No schedule items for this PL day yet</p>
                                <div class="mt-4 flex flex-col sm:flex-row justify-center gap-3">
                                    <a href="{{ route('admin.schedule.create', ['pd_day_id' => $pdDay->id]) }}" 
                                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white"
                                       style="background: #2563eb;">
                                        <i class="fas fa-plus mr-2"></i>Create Manually
                                    </a>
                                    @if($pdDays->count() > 1)
                                        <a href="{{ route('admin.schedule.copy-form', $pdDay) }}" 
                                           class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white"
                                           style="background: #0ea5e9;">
                                            <i class="fas fa-copy mr-2"></i>Copy from Other PL Day
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card rounded-2xl shadow-sm p-12 text-center">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: #f1f5f9;">
                        <i class="fas fa-calendar-alt text-2xl" style="color: #94a3b8;"></i>
                    </div>
                    <p class="text-sm" style="color: #64748b;">No PL days configured yet. Create a PL day first.</p>
                    <a href="{{ route('admin.pddays.create') }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white"
                       style="background: #2563eb;">
                        <i class="fas fa-plus mr-2"></i>Create PL Day
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
