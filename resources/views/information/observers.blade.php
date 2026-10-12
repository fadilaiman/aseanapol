@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about-people.observers', 'hero.title'),
    'subtitle' => cms('about-people.observers', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Information', 'url' => route('information.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about-people.observers', 'hero.title'),   'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(cms_on('about-people.observers', 'intro'))
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('about-people.observers', 'intro.text') }}
            </p>
        </div>
        @endif

        {{-- Admission Criteria --}}
        @if(cms_on('about-people.observers', 'admission'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8 max-w-3xl">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-4">{{ cms('about-people.observers', 'admission.heading') }}</h2>
            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                {{ cms('about-people.observers', 'admission.text') }}
            </p>
            <a href="{{ route('guidelines.observers-dialogue', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">description</span>
                {{ cms('about-people.observers', 'admission.button') }}
            </a>
        </div>
        @endif

        {{-- Observer list --}}
        @if(cms_on('about-people.observers', 'list'))
        <h2 class="text-xl font-bold text-primary dark:text-white mb-5">{{ cms('about-people.observers', 'list.heading') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
            @foreach(cms('about-people.observers', 'list.items') as $obs)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-4">
                <div class="w-10 h-10 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    @if(!empty($obs['flag']))
                    <span class="text-2xl leading-none">{{ $obs['flag'] }}</span>
                    @else
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">{{ $obs['name'] }}</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 mb-2">{{ cms('about-people.observers', 'list.since_label') }} {{ $obs['since'] }} &middot; {{ $obs['conference'] }}</p>
                    <div class="flex items-center gap-3">
                        @if(!empty($obs['q']))
                        <a href="{{ route('news.index', ['locale' => app()->getLocale(), 'q' => $obs['q']]) }}"
                           class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary dark:text-accent hover:underline">
                            <span class="material-symbols-outlined text-sm">open_in_new</span> {{ cms('about-people.observers', 'list.activities_label') }}
                        </a>
                        @endif
                        @if(!empty($obs['url']))
                        <a href="{{ $obs['url'] }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-accent hover:underline">
                            <span class="material-symbols-outlined text-sm">language</span> {{ cms('about-people.observers', 'list.website_label') }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-10">
            <a href="{{ route('information.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about-people.observers', 'back.label') }}
            </a>
        </div>
    </div>
</section>
@endsection
