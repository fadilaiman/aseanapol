@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Permanent Secretariat',
    'subtitle' => 'The administrative hub of ASEANAPOL, based in Kuala Lumpur, Malaysia.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Permanent Secretariat', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">About the Secretariat</h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                        <p>
                            The ASEANAPOL Permanent Secretariat serves as the administrative and operational hub of the Association of Southeast Asian Nations Chiefs of Police. Established to provide continuity of organisational functions between annual conferences, the Secretariat ensures the smooth coordination of ASEANAPOL's programmes, initiatives, and communications.
                        </p>
                        <p>
                            The Secretariat is hosted by the Royal Malaysia Police (RMP) and is located in Kuala Lumpur, Malaysia. It is staffed by officers from ASEAN member states and is headed by an Executive Director, a senior officer nominated by the host country.
                        </p>
                        <p>
                            The Permanent Secretariat is responsible for maintaining ASEANAPOL's records, facilitating inter-agency communication, coordinating working committees, and supporting the planning of annual conferences and other cooperative activities.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-primary dark:text-white mb-5">Functions</h2>
                    <ul class="space-y-3">
                        @php
                        $functions = [
                            ['icon' => 'admin_panel_settings', 'text' => 'Coordinate and implement decisions made at the ASEANAPOL Conference'],
                            ['icon' => 'groups',               'text' => 'Facilitate communication between member police forces'],
                            ['icon' => 'folder_open',          'text' => 'Maintain records, archives, and documentation of ASEANAPOL activities'],
                            ['icon' => 'calendar_month',       'text' => 'Plan and organise the annual ASEANAPOL Conference'],
                            ['icon' => 'hub',                  'text' => 'Coordinate working committees and cooperative programmes'],
                            ['icon' => 'public',               'text' => 'Manage external relations with partner organisations and dialogue partners'],
                        ];
                        @endphp
                        @foreach($functions as $fn)
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">{{ $fn['icon'] }}</span>
                            <span class="text-gray-600 dark:text-gray-300">{{ $fn['text'] }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Sidebar: Contact --}}
            <div class="space-y-6">
                <div class="bg-primary dark:bg-dark-card rounded-2xl p-6 text-white shadow-lg">
                    <h3 class="font-semibold text-lg mb-5 text-accent">Contact Information</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex gap-3">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">location_on</span>
                            <p class="text-white/80 leading-relaxed">Level 13, Tower 2, Bank Rakyat Twin Tower,<br>No 33, Jalan Rakyat,<br>50470 Kuala Lumpur, Malaysia</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">phone</span>
                            <a href="tel:+60322602222" class="text-white/80 hover:text-accent transition-colors">+603-22602222</a>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">fax</span>
                            <p class="text-white/80">+603-22602205</p>
                        </div>
                        <div class="flex gap-3 items-center">
                            <span class="material-symbols-outlined text-accent text-xl flex-shrink-0">mail</span>
                            <a href="mailto:aseanapolsec@aseanapol.org" class="text-white/80 hover:text-accent transition-colors break-all">aseanapolsec@aseanapol.org</a>
                        </div>
                    </div>
                </div>

                {{-- Back to About --}}
                <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
                   class="flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Back to About ASEANAPOL
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
