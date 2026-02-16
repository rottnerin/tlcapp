@extends('layouts.user')

@section('title', $scheduleItem->title . ' - NTS Sessions')

@section('content')
<div class="min-h-screen" style="background-color: var(--tlc-cream);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('spring.nts') }}" class="inline-flex items-center text-tlc-navy hover:text-tlc-orange mb-6 font-medium">
            ← Back to NTS Sessions
        </a>

        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-6 sm:p-8" style="background: linear-gradient(135deg, #9E9E9E 0%, #BDBDBD 100%); color: white;">
                <h1 class="text-2xl font-bold">{{ $scheduleItem->title }}</h1>
                <p class="mt-2 opacity-90">
                    {{ $scheduleItem->date->format('l, F j, Y') }} &bull;
                    {{ $scheduleItem->start_time->format('g:i A') }} &ndash; {{ $scheduleItem->end_time->format('g:i A') }}
                </p>
            </div>
            <div class="p-6 sm:p-8">
                @if($scheduleItem->description)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-tlc-navy mb-2">Description</h2>
                        <p class="text-gray-700">{{ $scheduleItem->description }}</p>
                    </div>
                @endif
                @if($scheduleItem->location)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-tlc-navy mb-2">Location</h2>
                        <p class="text-gray-700">{{ $scheduleItem->location }}</p>
                    </div>
                @endif
                @if($scheduleItem->hasLink())
                    <a href="{{ $scheduleItem->formatted_link_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center px-4 py-2 bg-tlc-orange hover:bg-orange-600 text-white rounded-lg font-medium">
                        {{ $scheduleItem->link_title ?: 'Learn More' }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
