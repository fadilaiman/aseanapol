@extends('layouts.app')

@section('page_header')
@php
// Shared-fact tokens ({founded_year}, {member_count_word}, ...) in page text; values come from Shared facts > Organisation.
$fx = function ($t) {
    $k = \App\Support\Shared::all('organisation')['key_dates'] ?? [];
    $c = \App\Support\Shared::all('organisation')['counts'] ?? [];
    $fc = explode(',', (string) ($k['first_conference_label'] ?? ''), 2);
    return strtr((string) $t, [
        '{founded_year}' => (string) ($k['founded_year'] ?? ''),
        '{founded_date}' => (string) ($k['founded_date_label'] ?? ''),
        '{first_conference_date}' => trim($fc[0]),
        '{first_conference_place}' => trim($fc[1] ?? ''),
        '{secretariat_established}' => (string) ($k['secretariat_established_label'] ?? ''),
        '{conference_count}' => (string) ($k['conference_count_label'] ?? ''),
        '{member_count_word}' => (string) ($c['member_count_word'] ?? ''),
        '{member_count_num}' => (string) ($c['member_count_num'] ?? ''),
    ]);
};
@endphp
@include('partials.page-hero', [
    'title'    => cms('about.index', 'hero.title'),
    'subtitle' => $fx(cms('about.index', 'hero.subtitle')),
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Intro --}}
        @if(cms_on('about.index', 'intro'))
        <div class="max-w-3xl mb-12">
            <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
                {{ $fx(cms('about.index', 'intro.text')) }}
            </p>
        </div>
        @endif

        {{-- Subpage Cards --}}
        @if(cms_on('about.index', 'cards'))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(cms('about.index', 'cards.items') as $page)
            <a href="{{ route($page['route'], ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-white dark:bg-dark-card border border-gray-100 dark:border-gray-700 rounded-2xl p-6 shadow-sm hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/5 dark:bg-primary/20 flex items-center justify-center group-hover:bg-primary dark:group-hover:bg-accent transition-all">
                        <span class="material-symbols-outlined text-primary dark:text-accent group-hover:text-white dark:group-hover:text-primary text-2xl transition-colors">{{ $page['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-1 group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ $page['title'] }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">{{ $fx($page['desc']) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4 text-primary dark:text-accent text-sm font-medium gap-1">
                    {{ cms('about.index', 'cards.learn_more') }} <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
