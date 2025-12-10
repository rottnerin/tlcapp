@extends('layouts.app')

@section('title', 'Copy Schedule')

@section('content')
<style>
    .card { background: #ffffff; border: 1px solid #e2e8f0; }
    .form-input { 
        background: #ffffff; 
        border: 1px solid #e2e8f0; 
        color: #1e293b;
        transition: all 0.15s ease;
    }
    .form-input:focus { 
        border-color: #2563eb; 
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }
</style>

<div class="min-h-screen py-8" style="background: #f1f5f9;">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.schedule.by-pdday') }}" 
               class="inline-flex items-center text-sm font-medium mb-4" style="color: #2563eb;">
                <i class="fas fa-arrow-left mr-2"></i>Back to Schedule by PL Days
            </a>
            <h1 class="text-2xl font-bold" style="color: #1e293b;">Copy Schedule Items</h1>
            <p class="mt-1" style="color: #64748b;">Copy schedule items from another PL day to {{ $pdDay->title }}</p>
        </div>

        <!-- Form Card -->
        <div class="card rounded-2xl shadow-sm p-6">
            @if($sourcePdDays->isEmpty())
                <div class="text-center py-12">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background: #f1f5f9;">
                        <i class="fas fa-check-circle text-2xl" style="color: #94a3b8;"></i>
                    </div>
                    <p class="text-sm" style="color: #64748b;">No other PL days with schedule items available to copy from.</p>
                    <a href="{{ route('admin.schedule.by-pdday') }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-white"
                       style="background: #2563eb;">
                        Back to Schedule
                    </a>
                </div>
            @else
                <form method="POST" action="{{ route('admin.schedule.copy', $pdDay) }}">
                    @csrf

                    <!-- Target PL Day Info -->
                    <div class="mb-6 p-4 rounded-xl" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                        <p class="text-sm font-medium" style="color: #1e40af;">Target PL Day</p>
                        <p class="text-lg font-bold mt-1" style="color: #1e3a8a;">{{ $pdDay->title }}</p>
                        <p class="text-sm" style="color: #3b82f6;">{{ $pdDay->date_range }}</p>
                    </div>

                    <!-- Source PL Day Selection -->
                    <div class="mb-6">
                        <label for="source_pd_day_id" class="block text-sm font-medium mb-2" style="color: #475569;">
                            Copy From <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="source_pd_day_id" id="source_pd_day_id" required
                                onchange="updateSourceInfo()"
                                class="w-full rounded-lg px-4 py-2.5 form-input @error('source_pd_day_id') border-red-500 @enderror">
                            <option value="">Select a PL day to copy from...</option>
                            @foreach($sourcePdDays as $source)
                                <option value="{{ $source->id }}" data-schedule-count="{{ $source->scheduleItems()->count() }}">
                                    {{ $source->title }} ({{ $source->date_range }}) - {{ $source->scheduleItems()->count() }} items
                                </option>
                            @endforeach
                        </select>
                        @error('source_pd_day_id')
                            <p class="mt-1 text-sm" style="color: #dc2626;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Source Info Preview -->
                    <div id="source-info" class="mb-6 p-4 rounded-xl hidden" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <p class="text-sm font-medium" style="color: #1e293b;">
                            <i class="fas fa-clipboard-list mr-2" style="color: #64748b;"></i>Source Schedule Preview
                        </p>
                        <p class="text-sm mt-2" style="color: #64748b;">Will copy <strong id="item-count" style="color: #1e293b;">0</strong> schedule items</p>
                    </div>

                    <!-- Info Box -->
                    <div class="mb-6 p-4 rounded-xl" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle mr-3 mt-0.5" style="color: #3b82f6;"></i>
                            <p class="text-sm" style="color: #1e40af;">
                                All schedule items from the selected PL day will be copied with the same details. Links and divisions will also be copied.
                            </p>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.schedule.by-pdday') }}" 
                           class="px-6 py-2.5 rounded-lg font-medium transition-colors"
                           style="background: #ffffff; border: 1px solid #e2e8f0; color: #475569;">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2.5 rounded-lg font-medium text-white transition-colors"
                                style="background: #2563eb;">
                            <i class="fas fa-copy mr-2"></i>Copy Schedule
                        </button>
                    </div>
                </form>

                <script>
                    function updateSourceInfo() {
                        const select = document.getElementById('source_pd_day_id');
                        const option = select.options[select.selectedIndex];
                        const sourceInfo = document.getElementById('source-info');
                        const itemCount = document.getElementById('item-count');

                        if (select.value) {
                            const count = option.getAttribute('data-schedule-count');
                            itemCount.textContent = count;
                            sourceInfo.classList.remove('hidden');
                        } else {
                            sourceInfo.classList.add('hidden');
                        }
                    }
                </script>
            @endif
        </div>
    </div>
</div>
@endsection
