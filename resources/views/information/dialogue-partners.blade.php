@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Dialogue Partners',
    'subtitle' => 'International organisations with formal dialogue status with ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',               'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Information',        'url' => route('information.index', ['locale' => app()->getLocale()])],
        ['label' => 'Dialogue Partners',  'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL's Dialogue Partners are international law enforcement organisations and bodies that have established formal cooperative relationships with ASEANAPOL. These partnerships enhance the organisation's capacity to address transnational crime through shared expertise, resources, and joint initiatives.
            </p>
        </div>

        @php
        $partners = [
            [
                'name'    => 'INTERPOL',
                'full'    => 'International Criminal Police Organization',
                'icon'    => 'travel_explore',
                'website' => 'https://www.interpol.int',
                'desc'    => 'The world\'s largest international police organisation, enabling cross-border police co-operation and supporting and assisting all organisations, authorities and services whose mission is to prevent or combat international crime.',
            ],
            [
                'name'    => 'Europol',
                'full'    => 'European Union Agency for Law Enforcement Cooperation',
                'icon'    => 'shield',
                'website' => 'https://www.europol.europa.eu',
                'desc'    => 'The EU\'s law enforcement agency supporting EU member states in preventing and combating organised crime, terrorism, and other forms of serious crime.',
            ],
            [
                'name'    => 'UNODC',
                'full'    => 'United Nations Office on Drugs and Crime',
                'icon'    => 'balance',
                'website' => 'https://www.unodc.org',
                'desc'    => 'A UN agency that assists member states in the fight against illicit drugs, crime, and terrorism, providing technical assistance and promoting the rule of law.',
            ],
            [
                'name'    => 'ACCBP',
                'full'    => 'ASEAN Chiefs of Police Cooperation Bureau Programme',
                'icon'    => 'groups',
                'website' => null,
                'desc'    => 'A cooperative programme supporting ASEAN police forces in developing capacity and enhancing inter-agency cooperation across the region.',
            ],
        ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($partners as $partner)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $partner['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-primary dark:text-white">{{ $partner['name'] }}</h3>
                        <p class="text-gray-400 dark:text-gray-500 text-xs">{{ $partner['full'] }}</p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">{{ $partner['desc'] }}</p>
                @if($partner['website'])
                <a href="{{ $partner['website'] }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-medium hover:underline">
                    <span class="material-symbols-outlined text-base">open_in_new</span>
                    Visit Website
                </a>
                @endif
            </div>
            @endforeach
        </div>

        <div class="mt-10">
            <a href="{{ route('information.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to Information
            </a>
        </div>
    </div>
</section>
@endsection
