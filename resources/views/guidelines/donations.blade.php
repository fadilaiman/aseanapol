@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Guidelines for Accepting Donations and Sponsorships',
    'subtitle' => 'Framework and criteria for receiving financial contributions and sponsorships.',
    'breadcrumbs' => [
        ['label' => 'Home',                         'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',                   'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => 'Donations and Sponsorships',   'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">volunteer_activism</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">ASEANAPOL Official Guidelines</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">Guidelines for Accepting Donations and Sponsorships</h2>
                    </div>
                </div>

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        These guidelines establish the framework for ASEANAPOL to accept donations and sponsorships from external parties, ensuring transparency, accountability, and alignment with the organisation's mission and values.
                    </p>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">1. Eligibility of Donors and Sponsors</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Donations and sponsorships may be accepted from governments, international organisations, non-governmental organisations, and private sector entities.</li>
                            <li>Donors and sponsors must not have interests conflicting with ASEANAPOL's mandate or the laws of any member state.</li>
                            <li>Anonymous donations above a specified threshold may not be accepted without due diligence.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">2. Acceptance Criteria</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>Donations and sponsorships must support ASEANAPOL's stated objectives and programmes.</li>
                            <li>No conditions may be attached that would compromise ASEANAPOL's independence, integrity, or impartiality.</li>
                            <li>The Executive Committee must approve donations exceeding defined financial thresholds.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">3. Transparency and Accountability</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>All accepted donations and sponsorships shall be recorded in ASEANAPOL's financial records.</li>
                            <li>An annual report of donations and sponsorships received shall be presented to the Conference.</li>
                            <li>Donors/sponsors shall receive acknowledgment of their contribution in accordance with agreed terms.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">4. Application Process</h3>
                        <p>Organisations wishing to make a donation or sponsor ASEANAPOL programmes should submit a formal proposal to the Permanent Secretariat. The Secretariat will assess the proposal against these guidelines and present recommendations to the Executive Committee.</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 items-center justify-between">
                    <p class="text-sm text-gray-400">Enquiries: <a href="mailto:aseanapolsec@aseanapol.org" class="text-primary dark:text-accent hover:underline">aseanapolsec@aseanapol.org</a></p>
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
