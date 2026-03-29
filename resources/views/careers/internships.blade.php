@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Internships',
    'subtitle' => 'Attachment and internship opportunities for students and early-career professionals in regional policing cooperation.',
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => 'Careers & Opportunities', 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => 'Internships',             'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Programme overview --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-8">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">co_present</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">ASEANAPOL Internship Programme</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Short-term attachment opportunities at the ASEANAPOL Permanent Secretariat</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                The ASEANAPOL Internship Programme offers students and early-career professionals the opportunity to work alongside the Permanent Secretariat on regional policing cooperation, transnational crime analysis, communications, and administrative functions.
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Internships are unpaid attachments typically lasting 8–12 weeks, based in Kuala Lumpur, Malaysia. Candidates are expected to be enrolled in or recently graduated from an accredited university programme in criminology, law, international relations, public policy, or a related field.
            </p>
        </div>

        {{-- What interns do --}}
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Areas of Work</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            @php
            $areas = [
                ['icon' => 'analytics',      'title' => 'Research & Intelligence',  'desc' => 'Supporting transnational crime analysis, trend reports, and ASEANAPOL Bulletin contributions.'],
                ['icon' => 'campaign',       'title' => 'Communications',           'desc' => 'Assisting with press releases, website content, social media, and event documentation.'],
                ['icon' => 'event',          'title' => 'Events & Training',        'desc' => 'Supporting the ATCM, ASEANAPOL Conference logistics, and training coordination.'],
                ['icon' => 'folder_managed', 'title' => 'Administration',           'desc' => 'General secretariat support including records management and stakeholder correspondence.'],
            ];
            @endphp
            @foreach($areas as $a)
            <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $a['icon'] }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $a['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $a['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- How to apply --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6">
            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">How to Apply</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                To apply for an internship or attachment, send your CV and a brief cover letter indicating your area of interest and preferred dates to the ASEANAPOL Permanent Secretariat.
            </p>
            <a href="{{ route('contact-us', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">mail</span>
                Contact the Secretariat
            </a>
        </div>

    </div>
</section>
@endsection
