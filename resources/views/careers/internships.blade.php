@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('careers.internships', 'hero.title'),
    'subtitle' => cms('careers.internships', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => cms('careers.internships', 'hero.parent_label'), 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => cms('careers.internships', 'hero.title'),        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Programme overview --}}
        @if(cms_on('careers.internships', 'overview'))
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-8">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ cms('careers.internships', 'overview.icon') }}</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ cms('careers.internships', 'overview.heading') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ cms('careers.internships', 'overview.subhead') }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                {{ cms('careers.internships', 'overview.para1') }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('careers.internships', 'overview.para2') }}
            </p>
        </div>
        @endif

        {{-- Current internship openings (shown only when rows exist) --}}
        @include('careers._openings', ['key' => 'careers.internships'])

        {{-- What interns do --}}
        @if(cms_on('careers.internships', 'areas'))
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">{{ cms('careers.internships', 'areas.heading') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            @foreach(cms('careers.internships', 'areas.items') as $a)
            <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $a['icon'] }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $a['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $a['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- How to apply --}}
        @if(cms_on('careers.internships', 'apply'))
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6">
            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ cms('careers.internships', 'apply.heading') }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                {{ cms('careers.internships', 'apply.text') }}
            </p>
            <a href="{{ cms('careers.internships', 'apply.button_url') ?: route('contact-us', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">{{ cms('careers.internships', 'apply.button_icon') }}</span>
                {{ cms('careers.internships', 'apply.button_label') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
