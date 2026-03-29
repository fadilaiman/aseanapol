@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Crime Statistics',
    'subtitle' => 'Public crime data covering transnational crime trends across ASEAN member states.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                    ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources', 'url' => route('data-resources.index',       ['locale' => app()->getLocale()])],
        ['label' => 'Crime Statistics', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$categories = [
    [
        'icon'  => 'account_balance',
        'title' => 'Cybercrime',
        'desc'  => 'Online fraud, ransomware, phishing, and digital financial crimes across ASEAN member states.',
        'trend' => 'Increasing',
        'color' => 'text-red-500 dark:text-red-400',
        'bg'    => 'bg-red-50 dark:bg-red-900/20',
    ],
    [
        'icon'  => 'local_shipping',
        'title' => 'Drug Trafficking',
        'desc'  => 'Cross-border narcotics trafficking including synthetic drugs, methamphetamine, and precursor chemicals.',
        'trend' => 'Persistent',
        'color' => 'text-amber-600 dark:text-amber-400',
        'bg'    => 'bg-amber-50 dark:bg-amber-900/20',
    ],
    [
        'icon'  => 'person_off',
        'title' => 'Human Trafficking',
        'desc'  => 'Trafficking in persons including labour exploitation and forced migration across borders.',
        'trend' => 'Monitored',
        'color' => 'text-orange-500 dark:text-orange-400',
        'bg'    => 'bg-orange-50 dark:bg-orange-900/20',
    ],
    [
        'icon'  => 'forest',
        'title' => 'Wildlife Crime',
        'desc'  => 'Illegal trade in protected species, timber, and natural resources across the region.',
        'trend' => 'Monitored',
        'color' => 'text-green-600 dark:text-green-400',
        'bg'    => 'bg-green-50 dark:bg-green-900/20',
    ],
    [
        'icon'  => 'attach_money',
        'title' => 'Money Laundering',
        'desc'  => 'Financial crimes including proceeds of crime, trade-based laundering, and terrorist financing.',
        'trend' => 'Increasing',
        'color' => 'text-blue-600 dark:text-blue-400',
        'bg'    => 'bg-blue-50 dark:bg-blue-900/20',
    ],
    [
        'icon'  => 'no_encryption',
        'title' => 'Online Scams',
        'desc'  => 'Job scams, investment fraud, romance fraud, and other digital deception targeting ASEAN citizens.',
        'trend' => 'Increasing',
        'color' => 'text-purple-600 dark:text-purple-400',
        'bg'    => 'bg-purple-50 dark:bg-purple-900/20',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Notice banner --}}
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 rounded-2xl p-5 mb-10 flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-xl flex-shrink-0 mt-0.5">construction</span>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-1">Dashboard Under Development</p>
                <p class="text-sm text-amber-700 dark:text-amber-400">
                    The public crime statistics dashboard is currently being developed. Aggregated crime data across ASEAN member states will be published here once validated. For data enquiries, contact
                    <a href="mailto:info@aseanapol.org" class="font-semibold underline hover:no-underline">info@aseanapol.org</a>.
                </p>
            </div>
        </div>

        {{-- Category overview --}}
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Priority Crime Categories</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-12">
            @foreach($categories as $cat)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $cat['bg'] }} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ $cat['color'] }} text-xl">{{ $cat['icon'] }}</span>
                    </div>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $cat['bg'] }} {{ $cat['color'] }} uppercase tracking-wider">{{ $cat['trend'] }}</span>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ $cat['title'] }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $cat['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Data note --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary dark:text-accent text-xl flex-shrink-0 mt-0.5">info</span>
                <div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">About ASEANAPOL Crime Data</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        ASEANAPOL collects and analyses crime data contributed voluntarily by its ten member police forces. Data covers transnational crimes affecting the ASEAN region and informs joint operational responses and policy recommendations. Statistical releases are reviewed and approved by the ASEANAPOL Conference before publication.
                    </p>
                </div>
            </div>
        </div>

        {{-- Digital Library CTA --}}
        <div class="mt-10 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Published reports and analysis documents are available in the Digital Library.
            </p>
            <a href="{{ route('data-resources.digital-library', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">library_books</span>
                Open Digital Library
            </a>
        </div>

    </div>
</section>
@endsection
