@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Exchange Programs',
    'subtitle' => 'Officer exchange and secondment programmes between ASEANAPOL member police forces.',
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => 'Careers & Opportunities', 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => 'Exchange Programs',       'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Programme intro --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-8">
            <div class="flex items-start gap-4 mb-5">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">swap_horiz</span>
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white mb-1">Officer Exchange &amp; Secondment</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Building inter-agency relationships across ASEAN police forces</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                ASEANAPOL's exchange and secondment programmes facilitate the placement of police officers from member countries into partner agencies and the ASEANAPOL Permanent Secretariat. These programmes strengthen inter-agency trust, build shared operational knowledge, and deepen personal networks across the region.
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                Exchange assignments typically run from six months to two years and are coordinated bilaterally between member police forces or through the ASEANAPOL Secretariat.
            </p>
        </div>

        {{-- Programme types --}}
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Programme Types</h2>
        <div class="space-y-4 mb-8">
            @php
            $programs = [
                [
                    'icon'  => 'domain',
                    'title' => 'Secretariat Secondment',
                    'badge' => 'Secretariat',
                    'desc'  => 'Officers from member police forces are seconded to serve at the ASEANAPOL Permanent Secretariat in Kuala Lumpur, contributing to daily operations, intelligence sharing, and programme coordination. Positions are allocated to member countries on a rotational basis.',
                ],
                [
                    'icon'  => 'share',
                    'title' => 'Bilateral Police Exchange',
                    'badge' => 'Bilateral',
                    'desc'  => 'Direct officer exchange agreements between two ASEANAPOL member police forces, enabling officers to experience policing practices, investigative techniques, and cultural contexts in partner countries.',
                ],
                [
                    'icon'  => 'school',
                    'title' => 'Training Attachments',
                    'badge' => 'Training',
                    'desc'  => 'Short-term attachments to partner police training academies or specialist units. Participants gain practical exposure to specialised areas such as forensic investigation, cybercrime, counter-trafficking, and financial crime.',
                ],
                [
                    'icon'  => 'public',
                    'title' => 'International Partner Placements',
                    'badge' => 'International',
                    'desc'  => 'Placements with ASEANAPOL\'s international partner organisations — including INTERPOL, Europol, and UNODC — arranged for senior officers and specialists to broaden their global policing perspective.',
                ],
            ];
            @endphp
            @foreach($programs as $p)
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $p['icon'] }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $p['title'] }}</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-primary/8 dark:bg-primary/20 text-primary dark:text-accent uppercase tracking-wider">{{ $p['badge'] }}</span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Nominations --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6">
            <p class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Nominations &amp; Enquiries</p>
            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-3">
                Nominations for exchange and secondment positions are made through each member country's designated ASEANAPOL national focal point. For general enquiries about programme availability and eligibility, contact the ASEANAPOL Permanent Secretariat.
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
