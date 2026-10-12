@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('cooperation.interpol', 'hero.title'),
    'subtitle' => cms('cooperation.interpol', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',                      'url' => route('landing',                       ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.index', 'hero.title'), 'url' => route('international-cooperation',      ['locale' => app()->getLocale()])],
        ['label' => cms('cooperation.interpol', 'hero.crumb'),                  'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-14">

        {{-- Overview --}}
        @if(cms_on('cooperation.interpol', 'overview'))
        <div class="prose prose-gray dark:prose-invert max-w-none">
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms_html('cooperation.interpol', 'overview.text') }}
            </p>
        </div>
        @endif

        {{-- e-ADS System --}}
        @if(cms_on('cooperation.interpol', 'eads'))
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-primary/20 dark:border-primary/30 overflow-hidden">
            <div class="bg-primary px-6 py-4">
                <h2 class="text-lg font-extrabold text-white flex items-center gap-2">
                    <span class="material-symbols-outlined">{{ cms('cooperation.interpol', 'eads.icon') }}</span>
                    {{ cms('cooperation.interpol', 'eads.heading') }}
                </h2>
            </div>
            <div class="p-6 space-y-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ cms_html('cooperation.interpol', 'eads.body') }}
            </div>
        </div>
        @endif

        {{-- Joint Operations --}}
        @if(cms_on('cooperation.interpol', 'ops'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">{{ cms('cooperation.interpol', 'ops.heading') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @foreach(cms('cooperation.interpol', 'ops.items') as $op)
                <div class="bg-white dark:bg-dark-card rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $op['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ $op['name'] }}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">{{ $op['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- General Assembly participation --}}
        @if(cms_on('cooperation.interpol', 'iga'))
        <div>
            <h2 class="text-2xl font-extrabold text-primary dark:text-white mb-6">{{ cms('cooperation.interpol', 'iga.heading') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">{{ cms('cooperation.interpol', 'iga.intro') }}</p>
            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800 text-left">
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">{{ cms('cooperation.interpol', 'iga.col_session') }}</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">{{ cms('cooperation.interpol', 'iga.col_year') }}</th>
                            <th class="px-5 py-3 font-semibold text-gray-600 dark:text-gray-300">{{ cms('cooperation.interpol', 'iga.col_location') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach(cms('cooperation.interpol', 'iga.rows') as $iga)
                        <tr class="hover:bg-gray-50 dark:hover:bg-dark-surface transition-colors">
                            <td class="px-5 py-3 font-bold text-primary dark:text-accent">{{ $iga['session'] }}</td>
                            <td class="px-5 py-3 text-gray-700 dark:text-gray-300">{{ $iga['year'] }}</td>
                            <td class="px-5 py-3 text-gray-500 dark:text-gray-400">{{ $iga['city'] }}, {{ $iga['country'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
