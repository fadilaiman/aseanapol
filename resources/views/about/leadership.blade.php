@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Leadership',
    'subtitle' => 'The ASEANAPOL Secretariat is led by an Executive Director nominated by a member country on a rotational two-year basis.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',              ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',          ['locale' => app()->getLocale()])],
        ['label' => 'Leadership',       'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- ── Current Executive Director ────────────────────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-8">Current Executive Director</h2>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col sm:flex-row gap-0">
                    {{-- Photo --}}
                    <div class="sm:w-56 flex-shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ asset('media/ed/9.jpg') }}"
                             alt="Executive Director"
                             class="w-full sm:h-full object-cover object-top max-h-64 sm:max-h-none">
                    </div>
                    {{-- Info --}}
                    <div class="p-8 flex flex-col justify-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-accent mb-2">9th Executive Director · 2026–2027</span>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Police Senior Colonel Kongkrissada Kittithiraphong</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Royal Thai Police (RTP) &nbsp;·&nbsp; 🇹🇭 Thailand</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Police Senior Colonel Kongkrissada Kittithiraphong serves as the 9th Executive Director of the ASEANAPOL Secretariat,
                            nominated by the Royal Thai Police for the term 2026–2027. He oversees the operations of the
                            Secretariat and leads ASEANAPOL's efforts in strengthening regional cooperation to combat transnational crime.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Secretariat Directors ──────────────────────────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-8">Secretariat Directors</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">shield_person</span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent mb-1">Director for Police Services</p>
                    <h3 class="font-bold text-gray-900 dark:text-white">Police Senior Superintendent Huntal Tambunan</h3>
                    <p class="text-xs text-gray-400 mt-1">Indonesia National Police &nbsp;·&nbsp; 🇮🇩 Indonesia</p>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">analytics</span>
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-accent mb-1">Director for Plans and Programmes</p>
                    <h3 class="font-bold text-gray-900 dark:text-white">Police Colonel Jean Mary A. Mangahis</h3>
                    <p class="text-xs text-gray-400 mt-1">Philippines National Police &nbsp;·&nbsp; 🇵🇭 Philippines</p>
                </div>
            </div>
        </div>

        {{-- ── Past Executive Directors ────────────────────────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2">Past Executive Directors</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
                The Executive Director post rotates among ASEANAPOL member countries on a two-year term,
                beginning with the establishment of the Secretariat on 1 January 2010.
            </p>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 w-12">#</th>
                            <th class="px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16"></th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Name &amp; Rank</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">Country</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">Term</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                        $directors = [
                            ['no'=>'9th','name'=>'Police Senior Colonel Kongkrissada Kittithiraphong',             'force'=>'Royal Thai Police (RTP)',           'flag'=>'🇹🇭','term'=>'2026–2027','photo'=>asset('media/ed/9.jpg')],
                            ['no'=>'8th','name'=>'Pol. Col. David Martinez Vinluan',                              'force'=>'Philippines National Police (PNP)', 'flag'=>'🇵🇭','term'=>'2024–2025','photo'=>asset('media/ed/8.jpg')],
                            ['no'=>'7th','name'=>'Police Brigadier General Zaw Lin Tun',                          'force'=>'Myanmar Police Force (MPF)',         'flag'=>'🇲🇲','term'=>'2022–2023','photo'=>asset('media/ed/7.jpg')],
                            ['no'=>'6th','name'=>'DAC Jim Wee',                                                   'force'=>'Singapore Police Force (SPF)',       'flag'=>'🇸🇬','term'=>'2020–2021','photo'=>asset('media/ed/6.jpg')],
                            ['no'=>'5th','name'=>'Pol. Col. Kenechanh Phommachack',                               'force'=>'Lao Police Force (LPF)',             'flag'=>'🇱🇦','term'=>'2018–2019','photo'=>asset('media/ed/5.jpg')],
                            ['no'=>'4th','name'=>'Police Inspector General Yohanes Agus Mulyono',                  'force'=>'Indonesia National Police (INP)',    'flag'=>'🇮🇩','term'=>'2016–2017','photo'=>asset('media/ed/4.jpg')],
                            ['no'=>'3rd','name'=>'SAC Pengiran Dato\' Paduka Hj Abdul Wahab bin Pengiran Hj Omar', 'force'=>'Royal Brunei Police Force (RBPF)',   'flag'=>'🇧🇳','term'=>'2014–2015','photo'=>asset('media/ed/3.png')],
                            ['no'=>'2nd','name'=>'Lt. Gen. Sar Moline',                                           'force'=>'Cambodian National Police (CNP)',    'flag'=>'🇰🇭','term'=>'2012–2013','photo'=>asset('media/ed/2.jpg')],
                            ['no'=>'1st','name'=>'ACP Mohd Nadzri b. Zainal Abidin',                             'force'=>'Royal Malaysia Police (RMP)',        'flag'=>'🇲🇾','term'=>'2010–2011','photo'=>asset('media/ed/1.jpg')],
                        ];
                        @endphp
                        @foreach($directors as $d)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors">
                            <td class="px-5 py-3 font-bold text-primary dark:text-accent">{{ $d['no'] }}</td>
                            <td class="px-3 py-3">
                                <img src="{{ $d['photo'] }}" alt="{{ $d['name'] }}" class="w-10 h-12 object-cover object-top rounded-lg shadow-sm">
                            </td>
                            <td class="px-5 py-3">
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $d['name'] }}</span>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $d['force'] }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $d['flag'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $d['term'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
