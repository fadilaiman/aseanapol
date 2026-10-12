@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('cooperation.index', 'hero.title'),
    'subtitle' => cms('cooperation.index', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.index', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(cms_on('cooperation.index', 'intro'))
        <div class="max-w-3xl mb-8">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                {{ cms('cooperation.index', 'intro.text') }}
            </p>
        </div>
        @endif

        {{-- Sub-section links --}}
        @if(cms_on('cooperation.index', 'quicklinks'))
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-12">
            @foreach(cms('cooperation.index', 'quicklinks.items') as $sub)
            <a href="{{ route($sub['route'], ['locale' => app()->getLocale()]) }}"
               class="group bg-white dark:bg-dark-card rounded-xl p-4 border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center gap-2 hover:border-primary/30 hover:shadow-md transition-all">
                <span class="material-symbols-outlined text-primary dark:text-accent group-hover:text-accent text-2xl transition-colors">{{ $sub['icon'] }}</span>
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-200 group-hover:text-primary dark:group-hover:text-accent transition-colors">{{ $sub['title'] }}</span>
                <span class="material-symbols-outlined text-xs text-gray-300 dark:text-gray-600 group-hover:text-primary dark:group-hover:text-accent transition-colors">arrow_forward</span>
            </a>
            @endforeach
        </div>
        @endif

        @if(cms_on('cooperation.index', 'partners') && \App\Support\Shared::partners('cooperation'))
        <h2 class="text-xl font-bold text-primary dark:text-white mb-6">{{ cms('cooperation.index', 'partners.heading') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @php
                // Name / full name / website / order come from Shared facts; icon, category, logo, description are local presentation.
                $coopCards = [];
                foreach ((array) \App\Support\Cms::raw('cooperation.index', 'partners.items', []) as $c) {
                    if (is_array($c) && !empty($c['key'])) { $coopCards[$c['key']] = $c; }
                }
            @endphp
            @foreach(\App\Support\Shared::partners('cooperation') as $sp)
            @php
                $local = $coopCards[$sp['key']] ?? [];
                if (!empty($local['_hide'])) { continue; }
                $partner = [
                    'name'     => $sp['name'],
                    'full'     => $sp['full_name'],
                    'website'  => $sp['website'],
                    'category' => $local['category'] ?? '',
                    'icon'     => ($local['icon'] ?? '') !== '' ? $local['icon'] : ($sp['icon'] ?? ''),
                    'logo'     => $local['logo'] ?? '',
                    'desc'     => $local['desc'] ?? '',
                ];
            @endphp
            <div class="bg-white dark:bg-dark-card rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-primary/8 dark:bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        @if(!empty($partner['logo']))
                        <img src="{{ cms_url($partner['logo']) }}" alt="{{ $partner['name'] }}" class="max-w-full max-h-full object-contain rounded-lg">
                        @endif
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl{{ !empty($partner['logo']) ? ' hidden' : '' }}">{{ $partner['icon'] }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $partner['name'] }}</h3>
                            <span class="text-xs bg-accent/15 text-accent font-medium px-2 py-0.5 rounded-full">{{ $partner['category'] }}</span>
                        </div>
                        <p class="text-gray-400 dark:text-gray-500 text-xs mt-0.5">{{ $partner['full'] }}</p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed flex-1">{{ $partner['desc'] }}</p>
                @if(!empty($partner['website']))
                <a href="{{ $partner['website'] }}" target="_blank" rel="noopener"
                   class="mt-3 inline-flex items-center gap-1 text-primary dark:text-accent text-xs font-medium hover:underline">
                    <span class="material-symbols-outlined text-sm">open_in_new</span> {{ cms('cooperation.index', 'partners.website_label') }}
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endsection
