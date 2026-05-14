@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Publications',
    'subtitle' => 'The official ASEANAPOL Bulletin and Magazine editions.',
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources','url' => route('data-resources.index',   ['locale' => app()->getLocale()])],
        ['label' => 'Publications',    'url' => ''],
    ],
])
@endsection

@section('content')
@php
$publications = [
    ['edition' => '14th', 'title' => '14th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin',  'route' => 'publication.14th', 'color' => 'from-cyan-700 to-cyan-900',     'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-14-2025.pdf'],
    ['edition' => '13th', 'title' => '13th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin',  'route' => 'publication.13th', 'color' => 'from-amber-700 to-amber-900',   'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-13.pdf'],
    ['edition' => '12th', 'title' => '12th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin',  'route' => 'publication.12th', 'color' => 'from-primary/90 to-primary',    'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-12.pdf'],
    ['edition' => '11th', 'title' => '11th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin',  'route' => 'publication.11th', 'color' => 'from-teal-700 to-teal-900',     'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-2018.pdf'],
    ['edition' => '10th', 'title' => '10th Edition ASEANAPOL Bulletin', 'type' => 'Bulletin',  'route' => 'publication.10th', 'color' => 'from-indigo-700 to-indigo-900', 'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-10.pdf'],
    ['edition' => '9th',  'title' => '9th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin',  'route' => 'publication.9th',  'color' => 'from-primary to-primary/70',   'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-9.pdf'],
    ['edition' => '8th',  'title' => '8th Edition ASEANAPOL Bulletin',  'type' => 'Bulletin',  'route' => 'publication.8th',  'color' => 'from-blue-700 to-blue-900',    'pdf' => 'media/bulletin/bulletin/aseanapol-bulletin-8.pdf'],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-10">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                The ASEANAPOL Bulletin and Magazine are the official publications of the organisation, documenting key activities, cooperative programmes, news from member police forces, and developments in regional law enforcement cooperation.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($publications as $pub)
            <div class="card-hover group bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:border-primary/20 dark:hover:border-accent/30 transition-all flex flex-col">
                {{-- Cover graphic --}}
                <div class="h-48 bg-gradient-to-br {{ $pub['color'] }} flex flex-col items-center justify-center p-6 relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative z-10 text-center">
                        <div class="text-white/60 text-xs uppercase tracking-widest font-semibold mb-2">ASEANAPOL</div>
                        <div class="text-white font-extrabold text-4xl">{{ $pub['edition'] }}</div>
                        <div class="text-white/80 text-sm mt-1">{{ $pub['type'] }}</div>
                    </div>
                    <img src="{{ asset('images/aseanapol-logo.png') }}" alt="" class="absolute bottom-3 right-3 w-10 h-10 object-contain opacity-20">
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <span class="text-xs font-semibold {{ $pub['type'] === 'Magazine' ? 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20' : 'text-primary dark:text-accent bg-primary/8 dark:bg-primary/20' }} px-2.5 py-1 rounded-full self-start">
                        {{ $pub['type'] }}
                    </span>
                    <h3 class="font-bold text-gray-900 dark:text-white mt-3 mb-1">{{ $pub['title'] }}</h3>
                    <p class="text-gray-400 dark:text-gray-500 text-xs">Official ASEANAPOL Publication</p>
                    <div class="mt-auto pt-4">
                        @if(!empty($pub['pdf']))
                        <a href="{{ asset($pub['pdf']) }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-base">download</span> Download PDF
                        </a>
                        @else
                        <div class="flex items-center gap-1 text-gray-400 dark:text-gray-500 text-sm">
                            <span class="material-symbols-outlined text-base">lock</span> PDF not available
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
