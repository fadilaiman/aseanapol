@extends('layouts.app')

@section('content')
@php $loc = in_array(app()->getLocale(), ['en','ms','id','th','vi','km','lo','my','tl','zh','es','ru'], true) ? app()->getLocale() : 'en'; @endphp
<div class="min-h-[70vh] flex items-center justify-center py-24 px-4" data-testid="not-found">
    <div class="text-center max-w-lg">
        <p class="text-7xl font-extrabold text-accent mb-4">{{ cms('site.not-found', 'content.code') }}</p>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ cms('site.not-found', 'content.heading') }}</h1>
        <p class="text-gray-500 dark:text-gray-400 mb-8">{{ cms('site.not-found', 'content.message') }}</p>
        <a href="{{ route('landing', ['locale' => $loc]) }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            {{ cms('site.not-found', 'content.button') }}
        </a>
    </div>
</div>
@endsection
