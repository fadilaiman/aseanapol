@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('cooperation.unodc', 'hero.title'),
    'subtitle' => cms('cooperation.unodc', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.index', 'hero.title'), 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.unodc', 'hero.crumb'),                     'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        {{-- Overview --}}
        @if(cms_on('cooperation.unodc', 'overview'))
        <div>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms_html('cooperation.unodc', 'overview.text') }}
            </p>
        </div>
        @endif

        {{-- Focus areas --}}
        @if(cms_on('cooperation.unodc', 'areas'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">{{ cms('cooperation.unodc', 'areas.heading') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach(cms('cooperation.unodc', 'areas.items') as $a)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $a['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ $a['title'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $a['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Recent engagements --}}
        @if(cms_on('cooperation.unodc', 'engagements'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">{{ cms('cooperation.unodc', 'engagements.heading') }}</h2>
            <div class="space-y-4">
                @foreach(cms('cooperation.unodc', 'engagements.items') as $e)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-start gap-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-lg bg-primary/10 text-primary dark:bg-primary/20 dark:text-accent flex-shrink-0 mt-0.5">{{ $e['date'] }}</span>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">{{ $e['title'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ $e['desc'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
