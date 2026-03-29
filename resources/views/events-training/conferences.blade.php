@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'ASEANAPOL Conferences',
    'subtitle' => 'The annual ASEANAPOL Conference is the principal decision-making body of ASEANAPOL, held each year in a member country.',
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',                  ['locale' => app()->getLocale()])],
        ['label' => 'Events & Training', 'url' => route('events-training.index',    ['locale' => app()->getLocale()])],
        ['label' => 'Conferences',       'url' => ''],
    ],
])
@endsection

@section('content')
@php
$conferences = [
    // Forthcoming
    ['num' => '43rd', 'year' => 2025, 'dates' => 'Forthcoming 2025',       'city' => 'Bangkok',        'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                       'status' => 'upcoming'],
    // Past
    ['num' => '42nd', 'year' => 2024, 'dates' => '21–25 October 2024',     'city' => 'Nay Pyi Taw',    'country' => 'Myanmar',           'flag' => '🇲🇲', 'theme' => 'Strengthening Regional and Global Partnerships to Combat Transnational Crimes', 'status' => 'past'],
    ['num' => '41st', 'year' => 2023, 'dates' => '17–19 October 2023',     'city' => 'Vientiane',      'country' => 'Laos',              'flag' => '🇱🇦', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '40th', 'year' => 2022, 'dates' => '1–5 March 2022',         'city' => 'Phnom Penh',     'country' => 'Cambodia',          'flag' => '🇰🇭', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '39th', 'year' => 2019, 'dates' => '17–19 September 2019',   'city' => 'Hanoi',          'country' => 'Viet Nam',          'flag' => '🇻🇳', 'theme' => 'Honoured By Partnerships, Sustained By Unity',                             'status' => 'past'],
    ['num' => '38th', 'year' => 2018, 'dates' => '3–5 September 2018',     'city' => '',               'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '37th', 'year' => 2017, 'dates' => '11–15 September 2017',   'city' => '',               'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '36th', 'year' => 2016, 'dates' => '24–29 July 2016',        'city' => 'Putrajaya',      'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '35th', 'year' => 2015, 'dates' => '3–7 August 2015',        'city' => 'Jakarta',        'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '34th', 'year' => 2014, 'dates' => '12–16 May 2014',         'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '33rd', 'year' => 2013, 'dates' => '19–21 February 2013',    'city' => 'Pattaya',        'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '32nd', 'year' => 2012, 'dates' => '22–24 May 2012',         'city' => 'Nay Pyi Taw',    'country' => 'Myanmar',           'flag' => '🇲🇲', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '31st', 'year' => 2011, 'dates' => '31 May – 2 June 2011',   'city' => 'Vientiane Capital','country' => 'Lao PDR',         'flag' => '🇱🇦', 'theme' => null,                                                                       'status' => 'past'],
    ['num' => '30th', 'year' => 2010, 'dates' => '24–28 May 2010',         'city' => 'Phnom Penh',     'country' => 'Cambodia',          'flag' => '🇰🇭', 'theme' => null,                                                                       'status' => 'past'],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- COVID notice --}}
        <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl px-5 py-4 flex gap-3 items-start text-sm text-amber-800 dark:text-amber-300">
            <span class="material-symbols-outlined text-xl flex-shrink-0 mt-0.5">info</span>
            <span>The <strong>40th ASEANAPOL Conference</strong> was the first to be held after a two-year suspension (2020–2021) due to the COVID-19 pandemic.</span>
        </div>

        {{-- Conference list --}}
        <div class="space-y-4">
            @foreach($conferences as $conf)
            @php
                $loc = trim(implode(', ', array_filter([$conf['city'], $conf['country']])));
                $isUpcoming = $conf['status'] === 'upcoming';
            @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border {{ $isUpcoming ? 'border-primary/30 dark:border-primary/40' : 'border-gray-100 dark:border-gray-700' }} flex gap-5 items-start">
                {{-- Year badge --}}
                <div class="flex-shrink-0 w-16 text-center">
                    <div class="text-2xl leading-none mb-0.5">{{ $conf['flag'] }}</div>
                    <div class="text-lg font-extrabold text-primary dark:text-accent leading-none">{{ $conf['year'] }}</div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $isUpcoming ? 'bg-primary text-white' : 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-accent' }}">
                            {{ $conf['num'] }} Conference
                        </span>
                        @if($isUpcoming)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/20 text-accent uppercase tracking-wider">Forthcoming</span>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $conf['dates'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                        {{ $loc }}
                    </p>
                    @if($conf['theme'])
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">&ldquo;{{ $conf['theme'] }}&rdquo;</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Back link --}}
        <div class="mt-10">
            <a href="{{ route('events-training.calendar', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-primary dark:text-accent hover:underline">
                <span class="material-symbols-outlined text-base">calendar_month</span>
                View full Event Calendar
            </a>
        </div>

    </div>
</section>
@endsection
