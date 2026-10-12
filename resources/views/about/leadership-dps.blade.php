@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('about-people.leadership-dps', 'hero.title'),
    'subtitle' => cms('about-people.leadership-dps', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',           ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',       ['locale' => app()->getLocale()])],
        ['label' => 'Leadership',       'url' => route('about.leadership',  ['locale' => app()->getLocale()])],
        ['label' => cms('about-people.leadership-dps', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 lightbox-zone">

        {{-- ── Current post holder ──────────────────── --}}
        @if(cms_on('about-people.leadership-dps', 'current'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-8">{{ cms('about-people.leadership-dps', 'current.heading') }}</h2>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col sm:flex-row gap-0">
                    {{-- Photo --}}
                    @if(cms_img('about-people.leadership-dps', 'current.photo'))
                    <div class="sm:w-56 flex-shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ cms_img('about-people.leadership-dps', 'current.photo') }}"
                             alt="{{ cms('about-people.leadership-dps', 'current.alt') }}"
                             class="w-full sm:h-full object-cover object-top max-h-64 sm:max-h-none">
                    </div>
                    @endif
                    {{-- Info --}}
                    <div class="p-8 flex flex-col justify-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-accent mb-2">{{ cms('about-people.leadership-dps', 'current.badge') }}</span>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">{{ cms('about-people.leadership-dps', 'current.name') }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ cms('about-people.leadership-dps', 'current.force') }} &nbsp;·&nbsp; {{ cms('about-people.leadership-dps', 'current.country') }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ cms('about-people.leadership-dps', 'current.bio') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Past holders ────────────────────────────────── --}}
        @if(cms_on('about-people.leadership-dps', 'past'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2">{{ cms('about-people.leadership-dps', 'past.heading') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
                {{ cms('about-people.leadership-dps', 'past.intro') }}
            </p>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 w-12">{{ cms('about-people.leadership-dps', 'past.col_no') }}</th>
                            <th class="px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16"></th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">{{ cms('about-people.leadership-dps', 'past.col_name') }}</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">{{ cms('about-people.leadership-dps', 'past.col_country') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach(cms('about-people.leadership-dps', 'past.rows') as $d)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors">
                            <td class="px-5 py-3 font-bold text-primary dark:text-accent">{{ $d['no'] }}</td>
                            <td class="px-3 py-3">
                                @if(cms_url($d['photo'] ?? ''))
                                <img src="{{ cms_url($d['photo'] ?? '') }}" alt="{{ $d['name'] }}" class="w-10 h-12 object-cover object-top rounded-lg shadow-sm">
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $d['name'] }}</span>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $d['flag'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
