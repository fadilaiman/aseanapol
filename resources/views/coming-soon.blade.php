@extends('layouts.app')

@section('page_header')
<div class="bg-primary dark:bg-dark-card pt-24 pb-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-accent text-sm font-semibold uppercase tracking-widest mb-2">{{ $section }}</p>
        <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
    </div>
</div>
@endsection

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-20 px-4">
    <div class="text-center max-w-lg">
        <div class="w-20 h-20 bg-accent/10 dark:bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-4xl text-accent">construction</span>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Coming Soon</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-8">
            The <strong class="text-gray-700 dark:text-gray-200">{{ $title }}</strong> section is currently being prepared.
            Please check back soon.
        </p>
        <a href="{{ route('landing', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Home
        </a>
    </div>
</div>
@endsection
