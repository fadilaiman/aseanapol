@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Governance',
    'subtitle' => 'The organisational structure and decision-making bodies of ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Governance',      'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Governance Bodies --}}
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-primary dark:text-white mb-8 text-center">Governance Structure</h2>

            @php
            $bodies = [
                [
                    'level' => '1',
                    'title' => 'ASEANAPOL Conference',
                    'icon'  => 'groups',
                    'color' => 'bg-primary',
                    'desc'  => 'The highest decision-making body of ASEANAPOL. Convened annually and attended by the Chiefs of Police or their authorised representatives from all ten member states. The Conference reviews activities, sets policy directions, and approves resolutions.',
                ],
                [
                    'level' => '2',
                    'title' => 'Executive Committee',
                    'icon'  => 'manage_accounts',
                    'color' => 'bg-primary/80',
                    'desc'  => 'Responsible for implementing Conference resolutions and managing ASEANAPOL\'s affairs between annual conferences. The Executive Committee comprises senior representatives from member states and is chaired by the host country of the most recent Conference.',
                ],
                [
                    'level' => '3',
                    'title' => 'Working Committees',
                    'icon'  => 'workspaces',
                    'color' => 'bg-primary/60',
                    'desc'  => 'Specialised committees addressing specific areas of police cooperation, including counter-terrorism, drug enforcement, cybercrime, and capacity building. Each committee is led by a member state and reports to the Executive Committee.',
                ],
                [
                    'level' => '4',
                    'title' => 'Permanent Secretariat',
                    'icon'  => 'domain',
                    'color' => 'bg-primary/40',
                    'desc'  => 'The administrative arm of ASEANAPOL, based in Kuala Lumpur, Malaysia. The Secretariat provides logistical and administrative support, maintains records, and facilitates communication among member states and partner organisations.',
                ],
            ];
            @endphp

            <div class="space-y-4 max-w-3xl mx-auto">
                @foreach($bodies as $body)
                <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-5">
                    <div class="flex-shrink-0 w-14 h-14 {{ $body['color'] }} rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl">{{ $body['icon'] }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-accent uppercase tracking-wider">Level {{ $body['level'] }}</span>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2">{{ $body['title'] }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">{{ $body['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Chairmanship --}}
        <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 max-w-3xl mx-auto">
            <h2 class="text-xl font-bold text-primary dark:text-white mb-4">Rotating Chairmanship</h2>
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                The chairmanship of ASEANAPOL rotates among member states in alphabetical order. The host country of the annual ASEANAPOL Conference assumes the chairmanship for that year and leads the organisation's direction until the following conference. This principle ensures equal participation and shared leadership among all ASEAN member states.
            </p>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('about.index', ['locale' => app()->getLocale()]) }}"
               class="inline-flex items-center gap-2 text-primary dark:text-accent hover:underline text-sm font-medium">
                <span class="material-symbols-outlined text-base">arrow_back</span>
                Back to About ASEANAPOL
            </a>
        </div>
    </div>
</section>
@endsection
