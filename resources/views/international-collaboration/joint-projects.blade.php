@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('cooperation.joint-projects', 'hero.title'),
    'subtitle' => cms('cooperation.joint-projects', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.index', 'hero.title'), 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.joint-projects', 'hero.crumb'),            'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Intro --}}
        @if(cms_on('cooperation.joint-projects', 'intro'))
        <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
            {{ cms('cooperation.joint-projects', 'intro.text') }}
        </p>
        @endif

        {{-- Operations grid --}}
        @if(cms_on('cooperation.joint-projects', 'projects'))
        <div class="space-y-5">
            @foreach(cms('cooperation.joint-projects', 'projects.items') as $p)
            @php $statusCls = match($p['status']) { 'Active', 'Ongoing' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400', default => 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }; @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-5 items-start">
                <div class="w-12 h-12 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $p['icon'] }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $p['name'] }}</h3>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusCls }}">{{ $p['status'] }}</span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-accent/15 text-accent">{{ $p['type'] }}</span>
                    </div>
                    <p class="text-xs text-primary dark:text-accent font-medium mb-2">{{ cms('cooperation.joint-projects', 'projects.partner_label') }} {{ $p['partner'] }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $p['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>
@endsection
