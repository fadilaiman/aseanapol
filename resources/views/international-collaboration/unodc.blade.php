@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'ASEANAPOL & UNODC',
    'subtitle' => 'Working together to strengthen justice systems, combat drug trafficking, and reduce transnational organised crime in Southeast Asia.',
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => 'International Cooperation', 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => 'UNODC',                     'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Overview --}}
        <div>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL cooperates with the <strong>United Nations Office on Drugs and Crime (UNODC)</strong> on
                issues affecting the Southeast Asian region, including drug trafficking, wildlife crime, human
                trafficking, and justice system strengthening. This cooperation draws on UNODC's regional
                presence across ASEAN member countries and its specialised expertise in criminal justice reform.
            </p>
        </div>

        {{-- Focus areas --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">Focus Areas</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @php
                $areas = [
                    ['icon' => 'medication_liquid', 'title' => 'Drug Trafficking',          'desc' => 'Southeast Asia remains a key region in global illicit drug markets. ASEANAPOL and UNODC share intelligence and coordinate law enforcement responses to combat drug trafficking networks.'],
                    ['icon' => 'forest',            'title' => 'Wildlife & Forest Crime',    'desc' => 'Collaboration through UNODC\'s Global Programme for Combating Wildlife and Forest Crime to strengthen law enforcement capacity against illegal trade in protected species.'],
                    ['icon' => 'gavel',             'title' => 'Justice System Strengthening','desc' => 'UNODC supports ASEAN law enforcement through the South East Asia Justice Network (SEAJust), fostering cross-border judicial and prosecutorial cooperation.'],
                    ['icon' => 'hub',               'title' => 'Transnational Organised Crime','desc' => 'Coordination with UNODC\'s Regional Advisor on Transnational Organized Crime to align ASEANAPOL\'s operational priorities with the broader UN framework on organised crime.'],
                ];
                @endphp
                @foreach($areas as $a)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $a['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ $a['title'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $a['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent engagements --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">Recent Engagements</h2>
            <div class="space-y-4">
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">Aug 2024</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Working Visit to UNODC — Vientiane, Lao PDR</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Executive Director David Martinez Vinluan and Director for Plans and Programmes Nguyen Huu Ngoc
                                conducted a working visit to the UNODC office in Vientiane to strengthen operational collaboration
                                on transnational crime priorities in the region.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">Aug 2024</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">SEAJust Plenary Meeting</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                ASEANAPOL participated as a panellist in UNODC's South East Asia Justice Network (SEAJust) Plenary
                                Meeting in Vientiane, Lao PDR, contributing to discussions on cross-border justice and law
                                enforcement cooperation frameworks in Southeast Asia.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">May 2024</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Virtual Meeting with UNODC & PCIP</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                ASEANAPOL held a virtual coordination meeting with UNODC and the Police Chiefs International
                                Programme (PCIP) to align priorities and explore joint programming in transnational crime prevention.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">May 2017</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">UNODC & USAID Wildlife Crime Visit</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                UNODC's Regional Coordinator for the Global Programme on Wildlife and Forest Crime, together with
                                USAID's Wildlife Asia Law Enforcement Specialist, visited the ASEANAPOL Secretariat to discuss
                                joint approaches to combating wildlife trafficking in the region.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
