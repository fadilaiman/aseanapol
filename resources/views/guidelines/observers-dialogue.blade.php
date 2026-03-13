@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Policy Guidelines for Accepting Observers and Dialogue Partners',
    'subtitle' => 'Eligibility requirements and process for organisations seeking partnership status with ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',                         'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Guidelines',                   'url' => route('guidelines.index', ['locale' => app()->getLocale()])],
        ['label' => 'Observers and Dialogue Partners', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-symbols-outlined text-accent text-3xl">policy</span>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">ASEANAPOL Official Guidelines</p>
                        <h2 class="text-xl font-bold text-primary dark:text-white">Policy Guidelines for Accepting Observers and Dialogue Partners with ASEANAPOL</h2>
                    </div>
                </div>

                <div class="space-y-6 text-gray-600 dark:text-gray-300 leading-relaxed">
                    <p>
                        These policy guidelines govern the process by which external organisations and countries may be admitted as Observers or Dialogue Partners of ASEANAPOL. Both statuses offer distinct levels of engagement with the organisation.
                    </p>

                    {{-- Distinction between Observer and Dialogue Partner --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-primary/5 dark:bg-primary/15 rounded-xl p-5 border border-primary/10 dark:border-primary/30">
                            <h4 class="font-bold text-primary dark:text-accent mb-2">Observer Status</h4>
                            <p class="text-sm">Observers may attend ASEANAPOL Conference sessions as specified, participate in working committees as invited, and receive ASEANAPOL publications and communications. Observers do not have voting rights.</p>
                        </div>
                        <div class="bg-accent/5 dark:bg-accent/10 rounded-xl p-5 border border-accent/15 dark:border-accent/25">
                            <h4 class="font-bold text-accent mb-2">Dialogue Partner Status</h4>
                            <p class="text-sm">Dialogue Partners have a higher level of engagement, including formal dialogue sessions at the Conference, joint programme participation, and closer operational cooperation with ASEANAPOL.</p>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">1. Eligibility Criteria</h3>
                        <ul class="space-y-2 list-disc list-inside">
                            <li>The applicant must be a recognised international or regional organisation with a mandate relevant to law enforcement, crime prevention, or public safety.</li>
                            <li>The applicant must demonstrate a clear basis for cooperation with ASEANAPOL that benefits the ASEAN region.</li>
                            <li>The applicant must not have objectives or activities in conflict with ASEANAPOL's mission or any ASEAN member state's laws.</li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">2. Application Process</h3>
                        <ol class="space-y-3">
                            @php
                            $steps = [
                                'Submit a formal letter of application to the ASEANAPOL Permanent Secretariat, stating the basis for the request and proposed areas of cooperation.',
                                'The Permanent Secretariat reviews the application against eligibility criteria and prepares a recommendation.',
                                'The recommendation is presented to the Executive Committee for initial endorsement.',
                                'The Executive Committee\'s endorsement is submitted to the full ASEANAPOL Conference for approval.',
                                'Upon Conference approval, the applicant is formally notified and an appropriate arrangement is concluded.',
                            ];
                            @endphp
                            @foreach($steps as $i => $step)
                            <li class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-primary text-white text-xs font-bold rounded-full flex items-center justify-center mt-0.5">{{ $i + 1 }}</span>
                                <p>{{ $step }}</p>
                            </li>
                            @endforeach
                        </ol>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg mb-3">3. Review and Termination</h3>
                        <p>Observer and Dialogue Partner status is subject to periodic review. Status may be suspended or terminated by decision of the ASEANAPOL Conference if the organisation no longer meets eligibility criteria or acts contrary to ASEANAPOL's values and objectives.</p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-4 items-center justify-between">
                    <p class="text-sm text-gray-400">Applications: <a href="mailto:aseanapolsec@aseanapol.org" class="text-primary dark:text-accent hover:underline">aseanapolsec@aseanapol.org</a></p>
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
