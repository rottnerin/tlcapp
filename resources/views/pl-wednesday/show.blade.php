@extends('layouts.user')

@section('title', $session->title . ' - AES Professional Learning Days')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('pl-wednesday.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Professional Learning
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $session->title }}</h1>
        
        <div class="flex flex-wrap gap-4 mb-6 text-sm">
            <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ $session->date->format('l, F j, Y') }}
            </div>
            <div class="flex items-center text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ $session->formatted_time }}
            </div>
            @if($session->location)
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $session->location }}
                </div>
            @endif
        </div>

        @if($session->description)
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                <div class="text-gray-700 whitespace-pre-wrap">{{ $session->description }}</div>
            </div>
        @endif

        @if($session->links->count() > 0)
            <div class="border-t pt-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-3">Resources & Links</h2>
                <div class="space-y-3">
                    @foreach($session->links as $link)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                            <a href="{{ $link->formatted_url }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between group">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">{{ $link->title }}</h3>
                                    @if($link->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $link->description }}</p>
                                    @endif
                                </div>
                                <svg class="w-5 h-5 ml-4 text-gray-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

