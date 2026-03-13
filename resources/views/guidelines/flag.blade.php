@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Guidelines on the Use of the ASEANAPOL Flag',
    'subtitle' => 'Rules and protocols for the proper use and display of the ASEANAPOL flag.',
    'breadcrumbs' => [
        ['label' => 'Home',                             'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',                       'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => 'Use of the ASEANAPOL Flag',        'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">flag</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">ASEANAPOL Official Guidelines</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">Guidelines on the Use of the ASEANAPOL Flag</h2>
                    </div>
                </div>

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        The ASEANAPOL Flag is the official emblem of the Association of Southeast Asian Nations Chiefs of Police. Its use is governed by these guidelines to ensure consistency, dignity, and respect in all official contexts.
                    </p>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">1. Authorised Use</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>The ASEANAPOL Flag shall be displayed at all official ASEANAPOL events, including the annual Conference, Executive Committee meetings, and working committee meetings.</li>
                            <li>Member police forces may display the flag at events conducted in cooperation with or on behalf of ASEANAPOL.</li>
                            <li>The flag may be used in official publications, websites, and digital communications of ASEANAPOL.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">2. Display Protocol</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>When displayed alongside national flags of member states, the ASEANAPOL Flag shall be given a position of equal prominence.</li>
                            <li>The flag shall not be displayed in a worn, torn, or damaged state.</li>
                            <li>The flag shall not be used for commercial or advertising purposes without prior written approval from the Permanent Secretariat.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">3. Reproduction</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Any reproduction of the ASEANAPOL Flag must maintain the original proportions and colours.</li>
                            <li>Alterations, modifications, or distortions of the flag design are prohibited.</li>
                            <li>Requests for official flag files or physical flags should be directed to the Permanent Secretariat.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">4. Prohibited Uses</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>The flag shall not be used in a manner that is disrespectful, derogatory, or contrary to ASEANAPOL's values and objectives.</li>
                            <li>Unauthorised commercial use of the flag is strictly prohibited.</li>
                            <li>The flag shall not be used to imply endorsement by ASEANAPOL without explicit written approval.</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 items-center justify-between">
                    <p class="text-sm text-gray-400">For enquiries regarding flag use, contact: <a href="mailto:aseanapolsec@aseanapol.org" class="text-primary dark:text-accent hover:underline">aseanapolsec@aseanapol.org</a></p>
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
