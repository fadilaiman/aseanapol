@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'ASEANAPOL & Europol',
    'subtitle' => 'Cross-regional law enforcement cooperation targeting shared threats in financial crime and transnational organised crime.',
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => 'International Cooperation', 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => 'Europol',                   'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Overview --}}
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                ASEANAPOL maintains a working relationship with <strong>Europol</strong>, the European Union's law
                enforcement agency, focused on areas of mutual interest including financial crime, cybercrime,
                and the fight against transnational organised crime. Cross-regional cooperation between ASEANAPOL
                and Europol reflects the shared recognition that major criminal networks operate across both regions.
            </p>
        </div>

        {{-- Cooperation areas --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">Areas of Cooperation</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @php
                $areas = [
                    ['icon' => 'credit_card',   'title' => 'Payment Card Fraud',       'desc' => 'Joint strategic meetings on payment card fraud, identifying criminal networks operating between Europe and Southeast Asia.'],
                    ['icon' => 'computer',      'title' => 'Cybercrime',               'desc' => 'Collaboration on cybercrime threats affecting both regions, including cooperation through shared intelligence platforms.'],
                    ['icon' => 'account_balance','title' => 'Organised Crime',         'desc' => 'Information exchange on transnational organised crime groups with activities spanning both the European Union and ASEAN regions.'],
                ];
                @endphp
                @foreach($areas as $a)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $a['icon'] }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $a['title'] }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $a['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Key engagements --}}
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">Key Engagements</h2>
            <div class="space-y-4">
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">2018</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Courtesy Visit by Head of the Europol Office</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                The Head of the Europol Office, Benoit Godart, paid a courtesy call on the ASEANAPOL Secretariat in
                                Kuala Lumpur in April 2018, meeting with the Executive Director and senior directors to explore
                                enhanced cooperation frameworks between both organisations.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">2015</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">Strategic Meeting on Payment Card Fraud</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                ASEANAPOL participated in a joint Europol–INTERPOL strategic meeting on payment card fraud
                                held at the INTERPOL Global Complex for Innovation (IGCI) in Singapore, contributing Southeast
                                Asian law enforcement perspectives to the global effort against financial fraud networks.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
