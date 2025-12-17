@extends('layouts.app')

@section('title', 'Edit PL Wednesday Session')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('admin.pl-wednesday.index') }}" class="text-indigo-600 hover:text-indigo-700 mb-4 inline-block">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit PL Wednesday Session</h1>
        </div>

        <div class="bg-white rounded-lg shadow-sm border">
            <form action="{{ route('admin.pl-wednesday.update', $plWednesday) }}" method="POST" class="p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $plWednesday->title) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $plWednesday->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $plWednesday->location) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Division</label>
                    <select name="division_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">All Divisions</option>
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" {{ old('division_id', $plWednesday->division_id) == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('division_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date (Wednesday) <span class="text-red-500">*</span></label>
                        <select name="date" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Wednesday</option>
                            @foreach($wednesdayDates as $wedDate)
                                <option value="{{ $wedDate['value'] }}" {{ old('date', $plWednesday->date->format('Y-m-d')) == $wedDate['value'] ? 'selected' : '' }}>
                                    {{ $wedDate['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" 
                               value="{{ old('start_time', $plWednesday->start_time ? \Carbon\Carbon::parse($plWednesday->start_time)->format('H:i') : '15:00') }}" 
                               required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" 
                               value="{{ old('end_time', $plWednesday->end_time ? \Carbon\Carbon::parse($plWednesday->end_time)->format('H:i') : '17:00') }}" 
                               required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                    <input type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $plWednesday->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600">
                    <label for="is_active" class="ml-2 text-sm text-gray-900">Active</label>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('admin.pl-wednesday.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Update Session
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
document.addEventListener('DOMContentLoaded', function() {
    @if($plWednesday->links->count() > 0)
        @foreach($plWednesday->links as $link)
            addLink('{{ addslashes($link->title) }}', '{{ addslashes($link->url) }}', '{{ addslashes($link->description ?? '') }}');
        @endforeach
    @else
        addLink();
    @endif
});
</script>
@endsection

