@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Event Calendar',
    'subtitle' => 'ASEANAPOL conferences, meetings, training programmes, and activities.',
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => 'Events & Training', 'url' => route('events-training.index',  ['locale' => app()->getLocale()])],
        ['label' => 'Event Calendar',    'url' => ''],
    ],
])
@endsection

@section('content')
@php
$events = [
    // 2025
    ['date' => '2025-10-01', 'end' => '2025-10-01', 'title' => '43rd ASEANAPOL Conference (Forthcoming)',                                        'city' => 'Bangkok',      'country' => 'Thailand',          'type' => 'Conference'],
    ['date' => '2025-07-01', 'end' => '2025-07-01', 'title' => '13th ASEANAPOL Training Cooperation Meeting (ATCM)',                              'city' => '',             'country' => '',                  'type' => 'Meeting'],
    ['date' => '2025-03-01', 'end' => '2025-03-01', 'title' => '39th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',             'city' => '',             'country' => '',                  'type' => 'Meeting'],
    // 2024
    ['date' => '2024-12-18', 'end' => '2024-12-18', 'title' => '3rd ASEANAPOL Contact Persons Quarterly Discussion (ACPQD) – Virtual',           'city' => '',             'country' => 'Virtual',           'type' => 'Meeting'],
    ['date' => '2024-10-21', 'end' => '2024-10-25', 'title' => '42nd ASEANAPOL Conference',                                                       'city' => 'Nay Pyi Taw', 'country' => 'Myanmar',           'type' => 'Conference'],
    ['date' => '2024-07-08', 'end' => '2024-07-08', 'title' => '14th ASEANAPOL Contact Persons Meeting (ACPM)',                                   'city' => 'Kuala Lumpur', 'country' => 'Malaysia',          'type' => 'Meeting'],
    ['date' => '2024-06-01', 'end' => '2024-06-01', 'title' => '12th ASEANAPOL Training Cooperation Meeting (ATCM)',                              'city' => '',             'country' => '',                  'type' => 'Meeting'],
    ['date' => '2024-02-13', 'end' => '2024-02-14', 'title' => '38th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',             'city' => 'Vientiane',    'country' => 'Lao PDR',           'type' => 'Meeting'],
    // 2023
    ['date' => '2023-10-17', 'end' => '2023-10-19', 'title' => '41st ASEANAPOL Conference',                                                       'city' => 'Vientiane',    'country' => 'Laos',              'type' => 'Conference'],
    ['date' => '2023-09-01', 'end' => '2023-09-01', 'title' => '13th ASEANAPOL Contact Persons Meeting (ACPM)',                                   'city' => 'Kuala Lumpur', 'country' => 'Malaysia',          'type' => 'Meeting'],
    ['date' => '2023-06-13', 'end' => '2023-06-15', 'title' => 'Regional Anti-Scam Conference on Countering Cyber-Scam',                         'city' => 'Singapore',    'country' => 'Singapore',         'type' => 'Conference'],
    ['date' => '2023-05-01', 'end' => '2023-05-01', 'title' => '37th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',             'city' => '',             'country' => 'Cambodia',          'type' => 'Meeting'],
    ['date' => '2023-03-22', 'end' => '2023-03-23', 'title' => '11th ASEANAPOL Training Cooperation Meeting (ATCM)',                              'city' => 'Phnom Penh',   'country' => 'Cambodia',          'type' => 'Meeting'],
    ['date' => '2023-03-15', 'end' => '2023-03-15', 'title' => '1st ASEANAPOL Contact Persons Quarterly Discussion (ACPQD) 2023 – Virtual',      'city' => '',             'country' => 'Virtual',           'type' => 'Meeting'],
    // 2022
    ['date' => '2022-09-21', 'end' => '2022-09-21', 'title' => '16th ASEAN Ministerial Meeting on Transnational Crime (AMMTC)',                   'city' => 'Phnom Penh',   'country' => 'Cambodia',          'type' => 'Meeting'],
    ['date' => '2022-09-06', 'end' => '2022-09-08', 'title' => 'ASEANAPOL Child Sexual Abuse and Exploitation Conference',                        'city' => 'Manila',       'country' => 'Philippines',       'type' => 'Conference'],
    ['date' => '2022-03-01', 'end' => '2022-03-05', 'title' => '40th ASEANAPOL Conference',                                                       'city' => 'Phnom Penh',   'country' => 'Cambodia',          'type' => 'Conference'],
    // 2019
    ['date' => '2019-09-17', 'end' => '2019-09-19', 'title' => '39th ASEANAPOL Conference',                                                       'city' => 'Hanoi',        'country' => 'Viet Nam',          'type' => 'Conference'],
    ['date' => '2019-01-22', 'end' => '2019-01-23', 'title' => '9th ASEANAPOL Training Cooperation Meeting (ATCM)',                               'city' => '',             'country' => 'Brunei Darussalam', 'type' => 'Meeting'],
    // 2018
    ['date' => '2018-09-03', 'end' => '2018-09-05', 'title' => '38th ASEANAPOL Conference',                                                       'city' => '',             'country' => 'Brunei Darussalam', 'type' => 'Conference'],
    ['date' => '2018-05-08', 'end' => '2018-05-09', 'title' => '35th ASEANAPOL Database System Technical Committee (ADSTC) Meeting',             'city' => '',             'country' => 'Brunei Darussalam', 'type' => 'Meeting'],
    // 2017
    ['date' => '2017-09-11', 'end' => '2017-09-15', 'title' => '37th ASEANAPOL Conference',                                                       'city' => '',             'country' => 'Singapore',         'type' => 'Conference'],
    ['date' => '2017-07-01', 'end' => '2017-07-01', 'title' => '8th ASEANAPOL Contact Persons Meeting (ACPM)',                                    'city' => 'Kuala Lumpur', 'country' => 'Malaysia',          'type' => 'Meeting'],
    // 2016
    ['date' => '2016-12-05', 'end' => '2016-12-09', 'title' => '7th ASEANAPOL Contact Persons Meeting (ACPM)',                                    'city' => 'Johor Bahru',  'country' => 'Malaysia',          'type' => 'Meeting'],
    ['date' => '2016-07-24', 'end' => '2016-07-29', 'title' => '36th ASEANAPOL Conference',                                                       'city' => 'Putrajaya',    'country' => 'Malaysia',          'type' => 'Conference'],
    // 2015
    ['date' => '2015-08-03', 'end' => '2015-08-07', 'title' => '35th ASEANAPOL Conference',                                                       'city' => 'Jakarta',      'country' => 'Indonesia',         'type' => 'Conference'],
    // 2014
    ['date' => '2014-09-29', 'end' => '2014-10-03', 'title' => '5th ASEANAPOL Contact Persons Meeting (ACPM)',                                    'city' => 'Kuala Lumpur', 'country' => 'Malaysia',          'type' => 'Meeting'],
    ['date' => '2014-05-12', 'end' => '2014-05-16', 'title' => '34th ASEANAPOL Conference',                                                       'city' => 'Manila',       'country' => 'Philippines',       'type' => 'Conference'],
    ['date' => '2014-03-17', 'end' => '2014-03-21', 'title' => '31st ASEANAPOL Database System Technical Committee (ADSTC) Meeting',             'city' => '',             'country' => 'Philippines',       'type' => 'Meeting'],
    // 2013
    ['date' => '2013-09-18', 'end' => '2013-09-18', 'title' => 'ASEANAPOL Secretariat Annual Shooting Exercise',                                                  'city' => '',         'country' => 'Malaysia',  'type' => 'Activity'],
    ['date' => '2013-08-01', 'end' => '2013-08-07', 'title' => '5th INTERPOL-ASEANAPOL System Integration Working Group Meeting',                                  'city' => 'Pattaya',  'country' => 'Thailand',  'type' => 'Meeting'],
    ['date' => '2013-02-19', 'end' => '2013-02-21', 'title' => '33rd ASEANAPOL Conference',                                                                        'city' => 'Pattaya',  'country' => 'Thailand',  'type' => 'Conference'],
    // 2012
    ['date' => '2012-12-04', 'end' => '2012-12-08', 'title' => '3rd ASEANAPOL Police Training Cooperation Meeting',                                                'city' => '',         'country' => 'Cambodia',  'type' => 'Training'],
    ['date' => '2012-10-16', 'end' => '2012-10-18', 'title' => '28th ADSTC Meeting',                                                                               'city' => '',         'country' => 'Singapore', 'type' => 'Meeting'],
    ['date' => '2012-10-02', 'end' => '2012-10-04', 'title' => '3rd ASEANAPOL Contact Persons Meeting',                                                            'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Meeting'],
    ['date' => '2012-05-22', 'end' => '2012-05-24', 'title' => '32nd ASEANAPOL Conference',                                                                        'city' => 'Nay Pyi Taw', 'country' => 'Myanmar', 'type' => 'Conference'],
    ['date' => '2012-02-20', 'end' => '2012-02-24', 'title' => 'ADSTC Meeting in Nay Pyi Taw',                                                                     'city' => 'Nay Pyi Taw', 'country' => 'Myanmar', 'type' => 'Meeting'],
    ['date' => '2012-02-13', 'end' => '2012-02-22', 'title' => 'JASPOC in Vientiane',                                                                              'city' => 'Vientiane','country' => 'Laos',     'type' => 'Meeting'],
    // 2011
    ['date' => '2011-12-07', 'end' => '2011-12-10', 'title' => 'ASEANAPOL Police Training Cooperation Meeting',                                                    'city' => 'Vientiane','country' => 'Laos',     'type' => 'Training'],
    ['date' => '2011-11-15', 'end' => '2011-11-18', 'title' => '2nd ASEANAPOL Contact Person Meeting',                                                             'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Meeting'],
    ['date' => '2011-10-17', 'end' => '2011-10-21', 'title' => 'ADSTC Meeting in Manila',                                                                          'city' => 'Manila',   'country' => 'Philippines', 'type' => 'Meeting'],
    ['date' => '2011-05-31', 'end' => '2011-06-02', 'title' => '31st ASEANAPOL Conference',                                                                        'city' => 'Vientiane Capital', 'country' => 'Lao PDR', 'type' => 'Conference'],
    ['date' => '2011-05-22', 'end' => '2011-05-27', 'title' => 'Fraudulent Travel Documents ASEAN Police Forces Course',                                           'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Training'],
    ['date' => '2011-03-18', 'end' => '2011-04-05', 'title' => '1st Joint Narcotics Course by Royal Malaysia Police',                                              'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Training'],
    ['date' => '2011-03-14', 'end' => '2011-03-19', 'title' => 'ADSTC Meeting',                                                                                    'city' => 'Vientiane','country' => 'Lao PDR',  'type' => 'Meeting'],
    ['date' => '2011-03-06', 'end' => '2011-03-11', 'title' => 'Counter Terrorism Radicalization & Deradicalization Course',                                       'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Training'],
    ['date' => '2011-03-05', 'end' => '2011-06-05', 'title' => 'Anti Trafficking In Person & Human Smuggling Course',                                              'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Training'],
    ['date' => '2011-02-01', 'end' => '2011-02-28', 'title' => 'C3 Workshop and Joint ASEAN Senior Police Officer\'s Course',                                      'city' => '',         'country' => 'Cambodia',  'type' => 'Training'],
    ['date' => '2011-01-01', 'end' => '2011-01-31', 'title' => '6th INTERPOL-ASEANAPOL System Integration Meeting',                                                'city' => '',         'country' => 'Thailand',  'type' => 'Meeting'],
    // 2010
    ['date' => '2010-12-07', 'end' => '2010-12-10', 'title' => 'Police Training Cooperation',                                                                      'city' => 'Hanoi',    'country' => 'Vietnam',   'type' => 'Training'],
    ['date' => '2010-11-24', 'end' => '2010-11-26', 'title' => '7th Meeting on Organized Crime in the East Asian Region',                                          'city' => '',         'country' => 'Japan',     'type' => 'Meeting'],
    ['date' => '2010-11-22', 'end' => '2010-11-25', 'title' => '24th ADSTC Meeting',                                                                               'city' => 'Chiangrai','country' => 'Thailand',  'type' => 'Meeting'],
    ['date' => '2010-10-14', 'end' => '2010-10-16', 'title' => '1st ASEANAPOL Contact Person Meeting',                                                             'city' => 'Kuala Lumpur', 'country' => 'Malaysia', 'type' => 'Meeting'],
    ['date' => '2010-10-03', 'end' => '2010-10-12', 'title' => 'Official Visit by Executive Director to INTERPOL-EUROPOL Headquarters',                            'city' => '',         'country' => 'Europe',    'type' => 'Activity'],
    ['date' => '2010-05-28', 'end' => '2010-05-28', 'title' => 'Official Launching of ASEANAPOL Website',                                                          'city' => 'Phnom Penh','country' => 'Cambodia', 'type' => 'Activity'],
    ['date' => '2010-05-24', 'end' => '2010-05-28', 'title' => '30th ASEANAPOL Conference',                                                                        'city' => 'Phnom Penh','country' => 'Cambodia', 'type' => 'Conference'],
];

$typeColors = [
    'Conference' => 'bg-primary text-white',
    'Meeting'    => 'bg-blue-600 text-white',
    'Training'   => 'bg-teal-600 text-white',
    'Activity'   => 'bg-accent text-primary',
];

// Group by year
$byYear = [];
foreach ($events as $e) {
    $year = substr($e['date'], 0, 4);
    $byYear[$year][] = $e;
}
krsort($byYear); // latest year first
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Legend --}}
        <div class="flex flex-wrap gap-3 mb-10">
            @foreach($typeColors as $type => $cls)
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full {{ $cls }}">
                {{ $type }}
            </span>
            @endforeach
        </div>

        {{-- Timeline by year --}}
        @foreach($byYear as $year => $yearEvents)
        <div class="mb-12">
            <div class="flex items-center gap-4 mb-6">
                <h2 class="text-2xl font-extrabold text-primary dark:text-white">{{ $year }}</h2>
                <div class="flex-1 h-px bg-gray-200 dark:bg-gray-700"></div>
                <span class="text-sm text-gray-400">{{ count($yearEvents) }} event{{ count($yearEvents) !== 1 ? 's' : '' }}</span>
            </div>

            <div class="space-y-4">
                @foreach($yearEvents as $event)
                @php
                    $start  = \Carbon\Carbon::parse($event['date']);
                    $end    = \Carbon\Carbon::parse($event['end']);
                    $sameDay = $start->isSameDay($end);
                    $loc    = trim(implode(', ', array_filter([$event['city'], $event['country']])));
                @endphp
                <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-5 items-start">
                    {{-- Date badge --}}
                    <div class="flex-shrink-0 w-16 text-center">
                        <div class="text-2xl font-extrabold text-primary dark:text-white leading-none">{{ $start->format('d') }}</div>
                        <div class="text-xs font-semibold uppercase text-accent tracking-wider">{{ $start->format('M') }}</div>
                        @if(!$sameDay)
                        <div class="text-[10px] text-gray-400 mt-1">to {{ $end->format('d M') }}</div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start gap-2 mb-1">
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full flex-shrink-0 {{ $typeColors[$event['type']] ?? 'bg-gray-200 text-gray-700' }}">
                                {{ $event['type'] }}
                            </span>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm leading-snug">{{ $event['title'] }}</h3>
                        @if($loc)
                        <p class="text-xs text-gray-400 mt-1.5 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm text-accent">location_on</span>
                            {{ $loc }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</section>
@endsection
