@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Observers',
    'subtitle' => 'Organisations and countries with observer status in ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Information', 'url' => route('information.index', ['locale' => app()->getLocale()])],
        ['label' => 'Observers',   'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                Observer status in ASEANAPOL allows organisations and countries to participate in ASEANAPOL activities and conferences as observers, facilitating broader engagement and information exchange while fostering future cooperative relationships.
            </p>
        </div>

        {{-- Admission Criteria --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 mb-8 max-w-3xl">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-4">Observer Admission</h2>
            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4">
                The admission of observers is governed by the Policy Guidelines for Accepting Observers and Dialogue Partners with ASEANAPOL. Interested organisations must meet eligibility criteria and receive approval from the ASEANAPOL Conference.
            </p>
            <a href="{{ route('guidelines.observers-dialogue', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 bg-primary hover:bg-primary-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                <span class="material-symbols-outlined text-lg">description</span>
                View Policy Guidelines
            </a>
        </div>

        {{-- Observer list placeholder --}}
        <div class="bg-primary/5 dark:bg-primary/10 rounded-2xl p-8 border border-primary/10 dark:border-primary/30 max-w-3xl">
            <div class="flex items-start gap-3">
                <span class="material-symbols-outlined text-primary dark:text-accent text-2xl flex-shrink-0">info</span>
                <div>
                    <h3 class="font-semibold text-primary dark:text-white mb-2">Current Observers</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                        The current list of observer organisations is maintained by the ASEANAPOL Permanent Secretariat and updated following each annual conference. For the most current listing, please contact the Secretariat at
                        <a href="mailto:aseanapolsec@aseanapol.org" class="text-primary dark:text-accent hover:underline font-medium">aseanapolsec@aseanapol.org</a>.
                    </p>
                </div>
            </div>
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
