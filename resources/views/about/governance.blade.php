@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about.governance', 'hero.title'),
    'subtitle' => cms('about.governance', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('about.index', 'hero.title'), 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => cms('about.governance', 'hero.title'),      'url' => ''],
    ],
])
@endsection

@section('content')
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
@php
$rt = function (string $path) {
    $h = (string) cms_html('about.governance', $path);
    return new \Illuminate\Support\HtmlString(preg_replace('#</(p|ul|ol)>(?=<)#', "</$1>\n", $h));
};
@endphp

{{-- ── Organisational Chart ────────────────────────────────────────────── --}}
@if(cms_on('about.governance', 'chart'))
<section class="py-14 bg-white dark:bg-dark-card border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center lightbox-zone">
        <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2 tracking-tight uppercase">{{ cms('about.governance', 'chart.heading') }}</h2>
        <div class="w-16 h-1 bg-accent mx-auto rounded mb-8"></div>
        @if(cms_img('about.governance', 'chart.image'))
        <img src="{{ cms_img('about.governance', 'chart.image') }}"
             alt="{{ cms('about.governance', 'chart.alt') }}"
             class="mx-auto w-full max-w-4xl rounded-lg shadow">
        @endif
    </div>
</section>
@endif

{{-- ── Main content ────────────────────────────────────────────────────── --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 lightbox-zone">

        {{-- ASEANAPOL Conference --}}
        @if(cms_on('about.governance', 'conference'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">{{ cms('about.governance', 'conference.heading') }}</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {{ $rt('conference.body') }}
            </div>
        </div>
        @endif

        {{-- ASEANAPOL Executive Committee --}}
        @if(cms_on('about.governance', 'exco'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">{{ cms('about.governance', 'exco.heading') }}</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {{ $rt('exco.body') }}
            </div>
        </div>
        @endif

        {{-- ASEANAPOL Secretariat Directors --}}
        @if(cms_on('about.governance', 'directors'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-5 uppercase">{{ cms('about.governance', 'directors.heading') }}</h2>
            <div class="text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {{ $rt('directors.body') }}
            </div>
        </div>
        @endif

        {{-- ASEANAPOL Conference since 1981 --}}
        @if(cms_on('about.governance', 'conferences'))
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-6 uppercase">{{ $fx(cms('about.governance', 'conferences.heading')) }}</h2>
            @if(count(cms('about.governance', 'conferences.rows')))
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="px-4 py-3 font-semibold text-center w-12">{{ cms('about.governance', 'conferences.col_num') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ cms('about.governance', 'conferences.col_dates') }}</th>
                            <th class="px-4 py-3 font-semibold text-center">{{ cms('about.governance', 'conferences.col_year') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ cms('about.governance', 'conferences.col_city') }}</th>
                            <th class="px-4 py-3 font-semibold">{{ cms('about.governance', 'conferences.col_country') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach(cms('about.governance', 'conferences.rows') as $i => $c)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-dark-card' : 'bg-gray-50 dark:bg-dark-surface' }}">
                            <td class="px-4 py-2 text-center font-semibold text-primary dark:text-accent">{{ $c['num'] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c['dates'] }}</td>
                            <td class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">{{ $c['year'] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c['city'] }}</td>
                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ $c['country'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif

        @if(cms_on('about.governance', 'back'))
        <div class="mt-4 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                {{ cms('about.governance', 'back.label') }}
            </a>
        </div>
        @endif

    </div>
</section>
@endsection
