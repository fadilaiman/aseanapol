@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Guidelines on the Roles and Functions of ASEANAPOL Contact Persons',
    'subtitle' => 'Defining responsibilities of designated ASEANAPOL Contact Persons in each member police force.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',       'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => 'Contact Persons',  'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">contact_phone</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">ASEANAPOL Official Guidelines</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">Guidelines on the Roles and Functions of ASEANAPOL Contact Persons</h2>
                    </div>
                </div>

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        Each ASEAN member police force designates an ASEANAPOL Contact Person who serves as the primary liaison between their organisation and the ASEANAPOL Permanent Secretariat. These guidelines define the roles, responsibilities, and operational requirements of this position.
                    </p>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">1. Designation</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Each member police force shall designate one primary ASEANAPOL Contact Person and one alternate.</li>
                            <li>Contact Persons shall be officers of sufficient seniority to facilitate prompt decision-making and communication.</li>
                            <li>Changes in Contact Person designation must be communicated to the Permanent Secretariat within 14 days.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">2. Primary Roles</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Serve as the official liaison between the member police force and the ASEANAPOL Permanent Secretariat.</li>
                            <li>Coordinate the dissemination and response to ASEANAPOL communications within their organisation.</li>
                            <li>Facilitate participation in ASEANAPOL conferences, working committees, and training events.</li>
                            <li>Maintain and update member country information in ASEANAPOL databases.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">3. Operational Functions</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Process and respond to ASEANAPOL requests for information, intelligence, and assistance.</li>
                            <li>Coordinate joint operations and investigative support with other member forces via ASEANAPOL channels.</li>
                            <li>Submit periodic activity reports to the Permanent Secretariat as required.</li>
                            <li>Ensure compliance with ASEANAPOL resolutions and programme commitments.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">4. Confidentiality</h3>
                        <p>Contact Persons are bound by confidentiality obligations regarding all sensitive information exchanged through ASEANAPOL channels. Information classified at any level must be handled in accordance with the relevant member state's security protocols and ASEANAPOL information security guidelines.</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <a href="{{ route('guidelines.index', ['locale' => app()->getLocale()]) }}"
                       class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Back to Guidelines
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
