@extends('layouts.app')

@section('title', 'Create PL Wednesday Session')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('admin.pl-wednesday.index') }}" class="text-indigo-600 hover:text-indigo-700 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create PL Wednesday Session</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <form action="{{ route('admin.pl-wednesday.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location') }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date (Wednesday) <span class="text-red-500">*</span></label>
                        <select name="date" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Wednesday</option>
                            @foreach($wednesdayDates as $wedDate)
                                <option value="{{ $wedDate['value'] }}" {{ old('date') == $wedDate['value'] ? 'selected' : '' }}>
                                    {{ $wedDate['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock mr-1 text-gray-400"></i>Start Time <span class="text-red-500">*</span>
                        </label>
                        <div class="time-picker-group">
                            @php
                                $startTime = old('start_time', '15:00');
                                $startHour24 = (int) substr($startTime, 0, 2);
                                $startHour12 = $startHour24 % 12 || 12;
                                $startMinute = substr($startTime, 3, 2);
                                $startAmPm = $startHour24 >= 12 ? 'PM' : 'AM';
                            @endphp
                            <input type="hidden" name="start_time" id="start_time_hidden" value="{{ $startTime }}" required>
                            <select class="time-hour border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @for($h = 1; $h <= 12; $h++)
                                    <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}" {{ $startHour12 == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endfor
                            </select>
                            <select class="time-minute border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ (int)$startMinute == $m ? 'selected' : '' }}>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select class="time-ampm border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="AM" {{ $startAmPm == 'AM' ? 'selected' : '' }}>AM</option>
                                <option value="PM" {{ $startAmPm == 'PM' ? 'selected' : '' }}>PM</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clock mr-1 text-gray-400"></i>End Time <span class="text-red-500">*</span>
                        </label>
                        <div class="time-picker-group">
                            @php
                                $endTime = old('end_time', '17:00');
                                $endHour24 = (int) substr($endTime, 0, 2);
                                $endHour12 = $endHour24 % 12 || 12;
                                $endMinute = substr($endTime, 3, 2);
                                $endAmPm = $endHour24 >= 12 ? 'PM' : 'AM';
                            @endphp
                            <input type="hidden" name="end_time" id="end_time_hidden" value="{{ $endTime }}" required>
                            <select class="time-hour border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @for($h = 1; $h <= 12; $h++)
                                    <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}" {{ $endHour12 == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endfor
                            </select>
                            <select class="time-minute border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                @for($m = 0; $m < 60; $m += 5)
                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ (int)$endMinute == $m ? 'selected' : '' }}>{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            <select class="time-ampm border border-gray-300 rounded-lg px-3 py-3 bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="AM" {{ $endAmPm == 'AM' ? 'selected' : '' }}>AM</option>
                                <option value="PM" {{ $endAmPm == 'PM' ? 'selected' : '' }}>PM</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Resources & Links</h3>
                        <button type="button" onclick="addLink()" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            <i class="fas fa-plus mr-2"></i>Add Link
                        </button>
                    </div>
                    <div id="links-container" class="space-y-4"></div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600">
                    <label for="is_active" class="ml-2 text-sm text-gray-900">Active</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.pl-wednesday.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let linkCount = 0;
function addLink(title = '', url = '', description = '') {
    const container = document.getElementById('links-container');
    container.insertAdjacentHTML('beforeend', `
        <div class="link-item border border-gray-200 rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between mb-4">
                <h4 class="text-sm font-medium text-gray-900">Link ${linkCount + 1}</h4>
                <button type="button" onclick="this.closest('.link-item').remove()" class="text-red-600 text-sm">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link Title</label>
                    <input type="text" name="links[${linkCount}][title]" value="${title}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="url" name="links[${linkCount}][url]" value="${url}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900" placeholder="https://...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                    <textarea name="links[${linkCount}][description]" rows="2" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900">${description}</textarea>
                </div>
            </div>
        </div>
    `);
    linkCount++;
}
document.addEventListener('DOMContentLoaded', () => addLink());
</script>
@endsection

