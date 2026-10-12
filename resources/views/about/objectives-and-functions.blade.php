@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.objectives-and-functions', 'hero.title'),
    'subtitle' => cms('about.objectives-and-functions', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.objectives-and-functions', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Objectives --}}
            @if(cms_on('about.objectives-and-functions', 'objectives'))
            <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">{{ cms('about.objectives-and-functions', 'objectives.icon') }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white">{{ cms('about.objectives-and-functions', 'objectives.heading') }}</h2>
                </div>
                <ol class="space-y-4">
                    @foreach(cms('about.objectives-and-functions', 'objectives.items') as $i => $obj)
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-7 h-7 bg-primary/10 dark:bg-primary/30 text-primary dark:text-accent font-bold text-sm rounded-full flex items-center justify-center">{{ $i + 1 }}</span>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $obj['text'] }}</p>
                    </li>
                    @endforeach
                </ol>
            </div>
            @endif

            {{-- Functions --}}
            @if(cms_on('about.objectives-and-functions', 'functions'))
            <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-xl">{{ cms('about.objectives-and-functions', 'functions.icon') }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white">{{ cms('about.objectives-and-functions', 'functions.heading') }}</h2>
                </div>
                <ul class="space-y-4">
                    @foreach(cms('about.objectives-and-functions', 'functions.items') as $fn)
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">{{ $fn['icon'] }}</span>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $fn['text'] }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        @if(cms_on('about.objectives-and-functions', 'back'))
        <div class="mt-8 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about.objectives-and-functions', 'back.label') }}
            </a>
        </div>
        @endif
    </div>
</section>
@endsection
