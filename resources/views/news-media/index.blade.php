@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('newsmedia.index', 'hero.title'),
    'subtitle' => cms('newsmedia.index', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',         ['locale' => app()->getLocale()])],
        ['label' => cms('newsmedia.index', 'hero.title'),'url' => ''],
    ],
])
@endsection

@section('content')
@php $sections = cms('newsmedia.index', 'cards.items'); @endphp

@if(cms_on('newsmedia.index', 'cards'))
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sections as $s)
            <a href="{{ \Illuminate\Support\Facades\Route::has($s['route'] ?? '') ? route($s['route'], ['locale' => app()->getLocale()]) : '#' }}"
               class="group bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col gap-4
                      hover:shadow-md hover:border-primary/30 dark:hover:border-primary/40 transition-all">
                <div class="flex items-start justify-between">
                    <div class="w-11 h-11 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-2xl">{{ $s['icon'] }}</span>
                    </div>
                    <span class="material-symbols-outlined text-gray-300 dark:text-gray-600 group-hover:text-primary dark:group-hover:text-accent transition-colors">arrow_forward</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-2">{{ $s['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $s['desc'] }}</p>
                </div>
                <div class="text-xs font-semibold text-primary dark:text-accent group-hover:underline">
                    {{ $s['label'] }} →
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
