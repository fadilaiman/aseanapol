@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Guidelines',
    'subtitle' => 'Official ASEANAPOL guidelines governing operations, conduct, and partnerships.',
    'breadcrumbs' => [
        ['label' => 'Home',       'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines', 'url' => ''],
    ],
])
@endsection

@section('content')
@php
$guidelines = [
    [
        'title'  => 'Guidelines on the Use of the ASEANAPOL Flag',
        'route'  => 'guidelines.flag',
        'icon'   => 'flag',
        'desc'   => 'Provides rules and protocols for the proper display, use, and handling of the ASEANAPOL flag at official events, premises, and publications.',
    ],
    [
        'title'  => 'Guidelines on the Roles and Functions of ASEANAPOL Contact Persons',
        'route'  => 'guidelines.contact-persons',
        'icon'   => 'contact_phone',
        'desc'   => 'Defines the responsibilities, duties, and operational procedures for designated ASEANAPOL Contact Persons within each member police force.',
    ],
    [
        'title'  => 'Guidelines for Accepting Donations and Sponsorships',
        'route'  => 'guidelines.donations',
        'icon'   => 'volunteer_activism',
        'desc'   => 'Establishes the framework and criteria for ASEANAPOL to accept financial contributions, donations, and sponsorships from external parties.',
    ],
    [
        'title'  => 'Policy Guidelines for Accepting Observers and Dialogue Partners',
        'route'  => 'guidelines.observers-dialogue',
        'icon'   => 'policy',
        'desc'   => 'Sets out the eligibility requirements, application process, and terms for organisations seeking observer or dialogue partner status with ASEANAPOL.',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL has established a set of guidelines to ensure consistent and principled operations across its member states, partner organisations, and stakeholders. These documents provide clear frameworks for key operational and governance matters.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl">
            @foreach($guidelines as $g)
            <a href="{{ route($g['route'], ['locale' => app()->getLocale()]) }}"
               class="card-hover group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:border-primary/20 dark:hover:border-accent/30 transition-all flex flex-col">
                <div class="flex items-start gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary/8 dark:bg-primary/20 rounded-xl flex items-center justify-center group-hover:bg-primary dark:group-hover:bg-accent transition-all">
                        <span class="material-symbols-outlined text-primary dark:text-accent group-hover:text-white dark:group-hover:text-primary text-2xl transition-colors">{{ $g['icon'] }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-primary dark:group-hover:text-accent transition-colors leading-snug">{{ $g['title'] }}</h3>
                    </div>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed flex-1">{{ $g['desc'] }}</p>
                <div class="flex items-center gap-1 mt-5 text-primary dark:text-accent text-sm font-medium">
                    <span class="material-symbols-outlined text-base">description</span>
                    Read Guidelines
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
