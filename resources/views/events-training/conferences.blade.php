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
    ['num' => '46th', 'year' => 2026, 'dates' => '22–26 July 2026',           'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'upcoming'],
    ['num' => '45th', 'year' => 2025, 'dates' => '3–7 November 2025',         'city' => 'Bangkok',        'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'upcoming'],
    // Past
    ['num' => '44th', 'year' => 2024, 'dates' => '21–25 October 2024',        'city' => 'Nay Pyi Taw',    'country' => 'Myanmar',           'flag' => '🇲🇲', 'theme' => 'Strengthening Regional and Global Partnerships to Combat Transnational Crimes',            'status' => 'past'],
    ['num' => '43rd', 'year' => 2023, 'dates' => '17–19 October 2023',        'city' => 'Vientiane',      'country' => 'Lao PDR',           'flag' => '🇱🇦', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '42nd', 'year' => 2022, 'dates' => '1–5 March 2022',            'city' => 'Phnom Penh',     'country' => 'Cambodia',          'flag' => '🇰🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '41st', 'year' => 2021, 'dates' => null,                        'city' => null,             'country' => null,                'flag' => null,  'theme' => null,                                                                                        'status' => 'suspended'],
    ['num' => '40th', 'year' => 2020, 'dates' => null,                        'city' => null,             'country' => null,                'flag' => null,  'theme' => null,                                                                                        'status' => 'suspended'],
    ['num' => '39th', 'year' => 2019, 'dates' => '16–20 September 2019',      'city' => 'Hanoi',          'country' => 'Viet Nam',          'flag' => '🇻🇳', 'theme' => 'Honoured By Partnerships, Sustained By Unity',                                             'status' => 'past'],
    ['num' => '38th', 'year' => 2018, 'dates' => '3–5 September 2018',        'city' => 'Brunei',         'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '37th', 'year' => 2017, 'dates' => '12–14 September 2017',      'city' => 'Singapore',      'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '36th', 'year' => 2016, 'dates' => '24–29 July 2016',           'city' => 'Kuala Lumpur',   'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '35th', 'year' => 2015, 'dates' => '3–7 August 2015',           'city' => 'Jakarta',        'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '34th', 'year' => 2014, 'dates' => '12–16 May 2014',            'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '33rd', 'year' => 2013, 'dates' => '21–23 February 2013',       'city' => 'Pattaya',        'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '32nd', 'year' => 2012, 'dates' => '22–24 May 2012',            'city' => 'Nay Pyi Taw',    'country' => 'Myanmar',           'flag' => '🇲🇲', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '31st', 'year' => 2011, 'dates' => '31 May – 2 June 2011',      'city' => 'Vientiane',      'country' => 'Lao PDR',           'flag' => '🇱🇦', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '30th', 'year' => 2010, 'dates' => '24–28 May 2010',            'city' => 'Phnom Penh',     'country' => 'Cambodia',          'flag' => '🇰🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '29th', 'year' => 2009, 'dates' => '13–15 May 2009',            'city' => 'Hanoi',          'country' => 'Viet Nam',          'flag' => '🇻🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '28th', 'year' => 2008, 'dates' => '25–29 May 2008',            'city' => 'Brunei',         'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '27th', 'year' => 2007, 'dates' => '2–7 June 2007',             'city' => 'Singapore',      'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '26th', 'year' => 2006, 'dates' => '22–26 May 2006',            'city' => 'Kuala Lumpur',   'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '25th', 'year' => 2005, 'dates' => '16–20 May 2005',            'city' => 'Bali',           'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '24th', 'year' => 2004, 'dates' => '16–20 August 2004',         'city' => 'Chiang Mai',     'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '23rd', 'year' => 2003, 'dates' => '8–12 September 2003',       'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '22nd', 'year' => 2002, 'dates' => '28–30 May 2002',            'city' => 'Phnom Penh',     'country' => 'Cambodia',          'flag' => '🇰🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '21st', 'year' => 2001, 'dates' => '23–25 May 2001',            'city' => 'Vientiane',      'country' => 'Lao PDR',           'flag' => '🇱🇦', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '20th', 'year' => 2000, 'dates' => '8–10 May 2000',             'city' => 'Yangon',         'country' => 'Myanmar',           'flag' => '🇲🇲', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '19th', 'year' => 1999, 'dates' => '26–28 April 1999',          'city' => 'Hanoi',          'country' => 'Viet Nam',          'flag' => '🇻🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '18th', 'year' => 1998, 'dates' => '24–27 May 1998',            'city' => 'Brunei',         'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '17th', 'year' => 1997, 'dates' => '1–5 June 1997',             'city' => 'Singapore',      'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '16th', 'year' => 1996, 'dates' => '26–30 May 1996',            'city' => 'Kuala Lumpur',   'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '15th', 'year' => 1995, 'dates' => '21–25 May 1995',            'city' => 'Jakarta',        'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '14th', 'year' => 1994, 'dates' => '1–5 May 1994',              'city' => 'Phuket',         'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '13th', 'year' => 1993, 'dates' => '19–21 April 1993',          'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '12th', 'year' => 1992, 'dates' => '3–5 August 1992',           'city' => 'Brunei',         'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '11th', 'year' => 1991, 'dates' => '5–9 May 1991',              'city' => 'Singapore',      'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '10th', 'year' => 1990, 'dates' => '25–28 July 1990',           'city' => 'Kuala Lumpur',   'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '9th',  'year' => 1989, 'dates' => '1–6 November 1989',         'city' => 'Bali',           'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '8th',  'year' => 1988, 'dates' => '14–16 November 1988',       'city' => 'Chiang Mai',     'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '7th',  'year' => 1987, 'dates' => '16–18 September 1987',      'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '6th',  'year' => 1986, 'dates' => '11–13 November 1986',       'city' => 'Brunei',         'country' => 'Brunei Darussalam', 'flag' => '🇧🇳', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '5th',  'year' => 1985, 'dates' => '7–9 November 1985',         'city' => 'Singapore',      'country' => 'Singapore',         'flag' => '🇸🇬', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '4th',  'year' => 1984, 'dates' => '21–24 November 1984',       'city' => 'Kuala Lumpur',   'country' => 'Malaysia',          'flag' => '🇲🇾', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '3rd',  'year' => 1983, 'dates' => '16–19 November 1983',       'city' => 'Jakarta',        'country' => 'Indonesia',         'flag' => '🇮🇩', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '2nd',  'year' => 1982, 'dates' => '18–20 June 1982',           'city' => 'Pattaya',        'country' => 'Thailand',          'flag' => '🇹🇭', 'theme' => null,                                                                                        'status' => 'past'],
    ['num' => '1st',  'year' => 1981, 'dates' => '21–23 October 1981',        'city' => 'Manila',         'country' => 'Philippines',       'flag' => '🇵🇭', 'theme' => null,                                                                                        'status' => 'past'],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- COVID notice --}}
        <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-xl px-5 py-4 flex gap-3 items-start text-sm text-amber-800 dark:text-amber-300">
            <span class="material-symbols-outlined text-xl flex-shrink-0 mt-0.5">info</span>
            <span>The <strong>40th and 41st ASEANAPOL Conferences</strong> (2020–2021) were suspended due to the COVID-19 pandemic. The <strong>42nd Conference</strong> in Phnom Penh, Cambodia was the first to resume.</span>
        </div>

        {{-- Conference list --}}
        <div class="space-y-4">
            @foreach($conferences as $conf)
            @php
                $loc = trim(implode(', ', array_filter([$conf['city'], $conf['country']])));
                $isUpcoming = $conf['status'] === 'upcoming';
                $isSuspended = $conf['status'] === 'suspended';
            @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border {{ $isUpcoming ? 'border-primary/30 dark:border-primary/40' : ($isSuspended ? 'border-gray-200 dark:border-gray-700 opacity-60' : 'border-gray-100 dark:border-gray-700') }} flex gap-5 items-start">
                {{-- Year badge --}}
                <div class="flex-shrink-0 w-16 text-center">
                    @if($conf['flag'])
                    <div class="text-2xl leading-none mb-0.5">{{ $conf['flag'] }}</div>
                    @else
                    <div class="text-2xl leading-none mb-0.5 text-gray-300 dark:text-gray-600">—</div>
                    @endif
                    <div class="text-lg font-extrabold text-primary dark:text-accent leading-none">{{ $conf['year'] }}</div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded-full {{ $isUpcoming ? 'bg-primary text-white' : ($isSuspended ? 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' : 'bg-primary/10 dark:bg-primary/20 text-primary dark:text-accent') }}">
                            {{ $conf['num'] }} Conference
                        </span>
                        @if($isUpcoming)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/20 text-accent uppercase tracking-wider">Forthcoming</span>
                        @elseif($isSuspended)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900/30 text-red-500 dark:text-red-400 uppercase tracking-wider">Suspended</span>
                        @endif
                    </div>
                    @if($isSuspended)
                    <h3 class="font-bold text-gray-400 dark:text-gray-500 text-sm leading-snug">Not held — COVID-19 Pandemic</h3>
                    @else
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $conf['dates'] }}</h3>
                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                        {{ $loc }}
                    </p>
                    @if($conf['theme'])
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 italic">&ldquo;{{ $conf['theme'] }}&rdquo;</p>
                    @endif
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
