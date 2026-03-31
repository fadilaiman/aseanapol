@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Member Countries',
    'subtitle' => 'The eleven national police forces of the ASEAN member states.',
    'breadcrumbs' => [
        ['label' => 'Home',              'url' => route('landing',             ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL',   'url' => route('about.index',         ['locale' => app()->getLocale()])],
        ['label' => 'Member Countries',  'url' => ''],
    ],
])
@endsection

@section('content')
@php
$members = [
    ['name' => 'Royal Brunei Police Force (RBPF)',              'country' => 'Brunei Darussalam', 'iso' => 'bn', 'route' => 'about.member.brunei'],
    ['name' => 'Cambodian National Police (CNP)',               'country' => 'Cambodia',          'iso' => 'kh', 'route' => 'about.member.cambodia'],
    ['name' => 'Indonesia National Police (INP)',               'country' => 'Indonesia',         'iso' => 'id', 'route' => 'about.member.indonesia'],
    ['name' => 'Lao Police Force (LPF)',                        'country' => 'Lao PDR',           'iso' => 'la', 'route' => 'about.member.lao'],
    ['name' => 'Royal Malaysia Police (RMP)',                   'country' => 'Malaysia',          'iso' => 'my', 'route' => 'about.member.malaysia'],
    ['name' => 'Myanmar Police Force (MPF)',                    'country' => 'Myanmar',           'iso' => 'mm', 'route' => 'about.member.myanmar'],
    ['name' => 'Philippines National Police (PNP)',             'country' => 'Philippines',       'iso' => 'ph', 'route' => 'about.member.philippines'],
    ['name' => 'Singapore Police Force (SPF)',                  'country' => 'Singapore',         'iso' => 'sg', 'route' => 'about.member.singapore'],
    ['name' => 'Royal Thai Police (RTP)',                       'country' => 'Thailand',          'iso' => 'th', 'route' => 'about.member.thailand'],
    ['name' => 'Vietnam Police Force (VPF)',                    'country' => 'Viet Nam',          'iso' => 'vn', 'route' => 'about.member.vietnam'],
    ['name' => 'National Police of Timor-Leste (PNTL)',        'country' => 'Timor-Leste',       'iso' => 'tl', 'route' => 'about.member.timor-leste', 'new' => true],
];
@endphp

{{-- Member Countries --}}
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-3">
                <span class="material-symbols-outlined text-base">public</span>
                Member Countries
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-primary dark:text-white">Eleven ASEAN Member States</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Click on a country to view contact details and more information about each member police force.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($members as $m)
            <a href="{{ route($m['route'], ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center hover:border-primary/20 dark:hover:border-accent/30 transition-all relative">
                @isset($m['new'])
                <span class="absolute top-3 right-3 text-[10px] font-bold bg-accent text-primary px-2 py-0.5 rounded-full uppercase tracking-wide">New</span>
                @endisset
                <img src="https://flagcdn.com/w80/{{ $m['iso'] }}.png"
                     alt="{{ $m['country'] }} flag"
                     class="w-16 h-10 object-cover rounded shadow-sm mb-3 group-hover:scale-105 transition-transform">
                <div class="font-semibold text-gray-900 dark:text-white text-sm group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ $m['country'] }}</div>
                <div class="text-gray-400 dark:text-gray-500 text-xs mt-1 leading-tight">{{ $m['name'] }}</div>
                <span class="mt-3 text-primary dark:text-accent text-xs font-medium flex items-center gap-1">
                    View <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Partners --}}
<section class="py-16 bg-white dark:bg-dark-card border-t border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <div class="inline-flex items-center gap-2 bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent px-4 py-1.5 rounded-full text-sm font-semibold mb-3">
                <span class="material-symbols-outlined text-base">handshake</span>
                Partners
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-primary dark:text-white">Dialogue Partners &amp; Observers</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl">
            <a href="{{ route('about.dialogue-partners', ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-background dark:bg-dark-surface rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl mb-3 block">forum</span>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-primary dark:group-hover:text-accent transition-colors">Dialogue Partners</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">International law enforcement organisations with formal dialogue status with ASEANAPOL.</p>
                <div class="flex items-center gap-1 mt-4 text-primary dark:text-accent text-sm font-medium">
                    View <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>

            <a href="{{ route('about.observers', ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-background dark:bg-dark-surface rounded-2xl p-6 border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl mb-3 block">visibility</span>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 group-hover:text-primary dark:group-hover:text-accent transition-colors">Observers</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Organisations and countries with observer status participating in ASEANAPOL activities.</p>
                <div class="flex items-center gap-1 mt-4 text-primary dark:text-accent text-sm font-medium">
                    View <span class="material-symbols-outlined text-base">arrow_forward</span>
                </div>
            </a>
        </div>
    </div>
</section>
@endsection
