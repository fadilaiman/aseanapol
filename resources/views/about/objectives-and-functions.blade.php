@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Objectives and Functions',
    'subtitle' => 'The core mandate driving ASEANAPOL\'s work across the region.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'About ASEANAPOL', 'url' => route('about.index', ['locale' => app()->getLocale()])],
        ['label' => 'Objectives and Functions', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Objectives --}}
            <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-xl">flag</span>
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white">Objectives</h2>
                </div>
                @php
                $objectives = [
                    'Promote and enhance police cooperation among ASEAN member states to combat transnational and cross-border crime.',
                    'Facilitate the exchange of criminal intelligence and information among member police forces.',
                    'Strengthen the capacity and professional standards of police forces in the ASEAN region.',
                    'Foster mutual assistance in training, education, and the development of police expertise.',
                    'Coordinate joint operations and investigations targeting organised crime, terrorism, and other serious threats to regional security.',
                    'Build and maintain strategic partnerships with international law enforcement organisations.',
                ];
                @endphp
                <ol class="space-y-4">
                    @foreach($objectives as $i => $obj)
                    <li class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-7 h-7 bg-primary/10 dark:bg-primary/30 text-primary dark:text-accent font-bold text-sm rounded-full flex items-center justify-center">{{ $i + 1 }}</span>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $obj }}</p>
                    </li>
                    @endforeach
                </ol>
            </div>

            {{-- Functions --}}
            <div class="bg-white dark:bg-dark-card rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary text-xl">settings</span>
                    </div>
                    <h2 class="text-2xl font-bold text-primary dark:text-white">Functions</h2>
                </div>
                @php
                $functions = [
                    ['icon' => 'forum',            'text' => 'Convene the annual ASEANAPOL Conference for policy discussion and decision-making.'],
                    ['icon' => 'hub',              'text' => 'Coordinate working committees on specific crime types and cooperation areas.'],
                    ['icon' => 'share',            'text' => 'Facilitate secure information and intelligence sharing among member states.'],
                    ['icon' => 'school',           'text' => 'Organise training programmes, seminars, and workshops for police professionals.'],
                    ['icon' => 'handshake',        'text' => 'Develop and maintain bilateral and multilateral cooperative frameworks.'],
                    ['icon' => 'description',      'text' => 'Publish the ASEANAPOL Bulletin/Magazine to disseminate information on activities and best practices.'],
                    ['icon' => 'travel_explore',   'text' => 'Represent ASEAN police interests in international forums and organisations.'],
                ];
                @endphp
                <ul class="space-y-4">
                    @foreach($functions as $fn)
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-accent text-xl flex-shrink-0 mt-0.5">{{ $fn['icon'] }}</span>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">{{ $fn['text'] }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
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
