@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Vacancies',
    'subtitle' => 'Current job openings at the ASEANAPOL Permanent Secretariat.',
    'breadcrumbs' => [
        ['label' => 'Home',                    'url' => route('landing',          ['locale' => app()->getLocale()])],
        ['label' => 'Careers & Opportunities', 'url' => route('careers.index',    ['locale' => app()->getLocale()])],
        ['label' => 'Vacancies',               'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- No active openings notice --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center mb-10">
            <div class="w-16 h-16 rounded-2xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-primary dark:text-accent text-3xl">work_outline</span>
            </div>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-2">No Active Openings at This Time</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                There are currently no open positions at the ASEANAPOL Permanent Secretariat. New vacancies will be posted here as they become available.
            </p>
        </div>

        {{-- Position types --}}
        <h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">Types of Positions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-10">
            @php
            $types = [
                ['icon' => 'manage_accounts', 'title' => 'Seconded Officers',    'desc' => 'Police officers seconded from member countries to serve at the ASEANAPOL Permanent Secretariat in Kuala Lumpur.'],
                ['icon' => 'groups_2',        'title' => 'Secretariat Staff',    'desc' => 'Administrative, communications, and operational support roles within the ASEANAPOL Permanent Secretariat.'],
                ['icon' => 'analytics',       'title' => 'Research & Analysis',  'desc' => 'Analysts working on transnational crime intelligence, reporting, and capacity-building programme support.'],
                ['icon' => 'laptop_mac',      'title' => 'Technology & Systems', 'desc' => 'ICT and information management specialists supporting ASEANAPOL\'s digital infrastructure and systems.'],
            ];
            @endphp
            @foreach($types as $t)
            <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $t['icon'] }}</span>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $t['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $t['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Stay updated --}}
        <div class="bg-primary/5 dark:bg-primary/15 border border-primary/10 dark:border-primary/25 rounded-2xl p-6 flex items-start gap-4">
            <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0">notifications</span>
            <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Stay Updated</p>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                    To be notified of future openings or to submit a general enquiry, contact the ASEANAPOL Permanent Secretariat at
                    <a href="mailto:info@aseanapol.org" class="text-primary dark:text-accent font-semibold hover:underline">info@aseanapol.org</a>.
                    Nominations for seconded officer positions are coordinated through member country focal points.
                </p>
            </div>
        </div>

    </div>
</section>
@endsection
