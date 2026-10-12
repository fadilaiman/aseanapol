@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('careers.exchange-programs', 'hero.title'),
    'subtitle' => cms('careers.exchange-programs', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => cms('careers.exchange-programs', 'hero.parent_label'), 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => cms('careers.exchange-programs', 'hero.title'),        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Programme intro --}}
        @if(cms_on('careers.exchange-programs', 'intro'))
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-8">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ cms('careers.exchange-programs', 'intro.icon') }}</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">{{ cms('careers.exchange-programs', 'intro.heading') }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ cms('careers.exchange-programs', 'intro.subhead') }}</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                {{ cms('careers.exchange-programs', 'intro.para1') }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms('careers.exchange-programs', 'intro.para2') }}
            </p>
        </div>
        @endif

        {{-- Current exchange opportunities (shown only when rows exist) --}}
        @include('careers._openings', ['key' => 'careers.exchange-programs'])

        {{-- Programme types --}}
        @if(cms_on('careers.exchange-programs', 'programs'))
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">{{ cms('careers.exchange-programs', 'programs.heading') }}</h2>
        <div class="space-y-4 mb-8">
            @foreach(cms('careers.exchange-programs', 'programs.items') as $p)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $p['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $p['title'] }}</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent uppercase tracking-wider">{{ $p['badge'] }}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Nominations --}}
        @if(cms_on('careers.exchange-programs', 'nominations'))
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6">
            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">{{ cms('careers.exchange-programs', 'nominations.heading') }}</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                {{ cms('careers.exchange-programs', 'nominations.text') }}
            </p>
            <a href="{{ cms('careers.exchange-programs', 'nominations.button_url') ?: route('contact-us', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">{{ cms('careers.exchange-programs', 'nominations.button_icon') }}</span>
                {{ cms('careers.exchange-programs', 'nominations.button_label') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
