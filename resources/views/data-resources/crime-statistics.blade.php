@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Crime Statistics',
    'subtitle' => 'Public crime data covering transnational crime trends across ASEAN member states.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                    ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources', 'url' => route('data-resources.index',       ['locale' => app()->getLocale()])],
        ['label' => 'Crime Statistics', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center mb-6">
            <span class="material-symbols-outlined text-amber-600 dark:text-amber-400 text-3xl">construction</span>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Coming Soon</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
            The public crime statistics dashboard is currently being developed. Aggregated crime data across ASEAN member states will be published here once validated. For data enquiries, contact
            <a href="mailto:info@aseanapol.org" class="font-semibold text-primary dark:text-accent underline hover:no-underline">info@aseanapol.org</a>.
        </p>

        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Published reports and analysis documents are available in the Digital Library.
        </p>
        <a href="{{ route('data-resources.digital-library', ['locale' => app()->getLocale()]) }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-base">library_books</span>
            Open Digital Library
        </a>

    </div>
</section>
@endsection
