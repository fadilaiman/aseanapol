@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Events & Training',
    'subtitle' => 'ASEANAPOL conferences, training programmes, and capacity-building activities.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => 'Events & Training','url' => ''],
    ],
])
@endsection

@section('content')
@php
$sections = [
    [
        'icon'     => 'calendar_month',
        'title'    => 'Event Calendar',
        'desc'     => 'A chronological record of all ASEANAPOL conferences, meetings, training sessions, and activities from 2010 to the present.',
        'route'    => 'events-training.calendar',
        'label'    => 'View Calendar',
        'status'   => 'active',
    ],
    [
        'icon'     => 'groups',
        'title'    => 'Conferences',
        'desc'     => 'Annual ASEANAPOL Conferences — the principal decision-making body of ASEANAPOL, held annually in a member country.',
        'route'    => 'events-training.conferences',
        'label'    => 'View Conferences',
        'status'   => 'active',
    ],
    [
        'icon'     => 'school',
        'title'    => 'Training Programs',
        'desc'     => 'Capacity-building courses, joint exercises, and specialised training programmes offered to member police forces.',
        'route'    => 'events-training.training-programs',
        'label'    => 'View Programs',
        'status'   => 'active',
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sections as $s)
            <a href="{{ $s['status'] === 'active' ? route($s['route'], ['locale' => app()->getLocale()]) : route($s['route'], ['locale' => app()->getLocale()]) }}"
               class="group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-4 hover:shadow-md hover:border-primary/30 dark:hover:border-primary/40 transition-all">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $s['icon'] }}</span>
                    </div>
                    @if($s['status'] === 'soon')
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 uppercase tracking-wider">Coming Soon</span>
                    @else
                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-primary dark:group-hover:text-accent transition-colors">arrow_forward</span>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                <div class="text-xs font-semibold {{ $s['status'] === 'active' ? 'text-primary dark:text-accent' : 'text-gray-400' }} group-hover:underline">
                    {{ $s['label'] }} →
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
