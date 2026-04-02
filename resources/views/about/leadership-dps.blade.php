@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Director for Police Services',
    'subtitle' => 'The Director for Police Services oversees police cooperation and operational matters within the ASEANAPOL Secretariat.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',           ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',  'url' => route('about.index',       ['locale' => app()->getLocale()])],
        ['label' => 'Leadership',       'url' => route('about.leadership',  ['locale' => app()->getLocale()])],
        ['label' => 'Director for Police Services', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        {{-- ── Current Director for Police Services ──────────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-8">Current Director for Police Services</h2>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col sm:flex-row gap-0">
                    {{-- Photo --}}
                    <div class="sm:w-56 flex-shrink-0 bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                        <img src="{{ asset('media/dps/no7.png') }}"
                             alt="Director for Police Services"
                             class="w-full sm:h-full object-cover object-top max-h-64 sm:max-h-none">
                    </div>
                    {{-- Info --}}
                    <div class="p-8 flex flex-col justify-center">
                        <span class="text-xs font-bold uppercase tracking-widest text-accent mb-2">Director for Police Services · 2025–Present</span>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-1">Police Senior Superintendent Huntal Tambunan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Indonesian National Police (INP) &nbsp;·&nbsp; 🇮🇩 Indonesia</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            Police Senior Superintendent Huntal Tambunan serves as the Director for Police Services of the ASEANAPOL Secretariat,
                            nominated by the Indonesian National Police. He is responsible for overseeing police cooperation and operational
                            programmes under the Secretariat.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Past Directors for Police Services ────────────────────── --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-2">Past Directors for Police Services</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">
                The Director for Police Services post rotates among ASEANAPOL member countries within the Secretariat.
            </p>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 w-12">#</th>
                            <th class="px-3 py-3 font-semibold text-gray-600 dark:text-gray-300 w-16"></th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">Name &amp; Rank</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300 whitespace-nowrap">Country</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                        $directors = [
                            ['no'=>'7th','name'=>'Pol. Snr. Supt. Huntal Tambunan',             'force'=>'Indonesian National Police (INP)',  'flag'=>'🇮🇩','photo'=>asset('media/dps/no7.png')],
                            ['no'=>'6th','name'=>'ACP Dr. Bakri bin Zainal Abidin',             'force'=>'Royal Malaysia Police (RMP)',       'flag'=>'🇲🇾','photo'=>asset('media/dps/no6.png')],
                            ['no'=>'5th','name'=>'Pol. Snr. Supt. Joni Getamala',               'force'=>'Indonesian National Police (INP)',  'flag'=>'🇮🇩','photo'=>asset('media/dps/no5.png')],
                            ['no'=>'4th','name'=>'DAC Jim Wee',                                 'force'=>'Singapore Police Force (SPF)',      'flag'=>'🇸🇬','photo'=>asset('media/dps/no4.png')],
                            ['no'=>'3rd','name'=>'PSSuPt. Ferdinand R. P. Bartolome',           'force'=>'Philippine National Police (PNP)',  'flag'=>'🇵🇭','photo'=>asset('media/dps/no3.png')],
                            ['no'=>'2nd','name'=>'ACP Mohamad Anil Shah bin Abdullah',          'force'=>'Royal Malaysia Police (RMP)',       'flag'=>'🇲🇾','photo'=>asset('media/dps/no2.png')],
                            ['no'=>'1st','name'=>'DAC Lim Kim Tak',                             'force'=>'Singapore Police Force (SPF)',      'flag'=>'🇸🇬','photo'=>asset('media/dps/no1.png')],
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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</section>
@endsection
