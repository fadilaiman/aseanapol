@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about-people.ob-lme', 'hero.title'),
    'subtitle' => cms('about-people.ob-lme', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',         ['locale' => app()->getLocale()])],
        ['label' => cms('about-people.ob-lme', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Explainer --}}
        @if(cms_on('about-people.ob-lme', 'intro'))
        <div class="max-w-3xl">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                {{ cms('about-people.ob-lme', 'intro.p1') }}
            </p>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('about-people.ob-lme', 'intro.p2') }}
            </p>
        </div>
        @endif

        {{-- LME Grid --}}
        @if(cms_on('about-people.ob-lme', 'entities'))
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
            @foreach(cms('about-people.ob-lme', 'entities.items') as $lme)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center gap-3">
                <div class="w-full h-20 flex items-center justify-center">
                    @if(cms_url($lme['logo'] ?? ''))
                    <img src="{{ cms_url($lme['logo'] ?? '') }}"
                         alt="{{ $lme['short'] }} logo"
                         class="max-h-20 max-w-full object-contain">
                    @endif
                </div>
                <div>
                    <p class="font-bold text-xs text-primary dark:text-accent">{{ $lme['short'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-snug mt-0.5">{{ $lme['name'] }}</p>
                </div>
                @if(!empty($lme['website']))
                <a href="{{ $lme['website'] }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1 text-[11px] font-semibold text-gray-500 dark:text-gray-400 hover:text-primary dark:hover:text-accent hover:underline">
                    <span class="material-symbols-outlined text-sm">language</span> {{ cms('about-people.ob-lme', 'entities.website_label') }}
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endsection
