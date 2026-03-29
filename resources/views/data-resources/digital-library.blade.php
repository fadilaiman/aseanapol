@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Digital Library',
    'subtitle' => 'Official documents, guidelines, and publications from ASEANAPOL.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',                ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources', 'url' => route('data-resources.index',   ['locale' => app()->getLocale()])],
        ['label' => 'Digital Library',  'url' => ''],
    ],
])
@endsection

@section('content')
@php
$collections = [
    [
        'title' => 'ASEANAPOL Bulletins & Magazine',
        'icon'  => 'menu_book',
        'docs'  => [
            [
                'title'  => 'ASEANAPOL Bulletin 2018 (11th Edition)',
                'type'   => 'PDF',
                'size'   => '—',
                'url'    => asset('media/bulletin/bulletin/aseanapol-bulletin-2018.pdf'),
            ],
        ],
    ],
    [
        'title' => 'Guidelines & Policy Documents',
        'icon'  => 'gavel',
        'docs'  => [
            [
                'title'  => 'Guidelines on the Use of the ASEANAPOL Flag',
                'type'   => 'PDF',
                'size'   => '—',
                'url'    => asset('media/default-document-library/default-document-library/guidelines-aseanapol-flag-.pdf'),
            ],
            [
                'title'  => 'Policy Guidelines — Accepting Observers & Dialogue Partners',
                'type'   => 'Web',
                'size'   => null,
                'url'    => route('guidelines.observers-dialogue', ['locale' => app()->getLocale()]),
            ],
            [
                'title'  => 'Guidelines on the Roles and Functions of Contact Persons',
                'type'   => 'Web',
                'size'   => null,
                'url'    => route('guidelines.contact-persons', ['locale' => app()->getLocale()]),
            ],
            [
                'title'  => 'Guidelines for Accepting Donations and Sponsorships',
                'type'   => 'Web',
                'size'   => null,
                'url'    => route('guidelines.donations', ['locale' => app()->getLocale()]),
            ],
        ],
    ],
    [
        'title' => 'Publications Index',
        'icon'  => 'library_books',
        'docs'  => [
            ['title' => '8th Edition ASEANAPOL Bulletin (2015)',  'type' => 'Web', 'size' => null, 'url' => route('publication.8th',  ['locale' => app()->getLocale()])],
            ['title' => '9th Edition ASEANAPOL Bulletin (2016)',  'type' => 'Web', 'size' => null, 'url' => route('publication.9th',  ['locale' => app()->getLocale()])],
            ['title' => '10th Edition ASEANAPOL Bulletin (2017)', 'type' => 'Web', 'size' => null, 'url' => route('publication.10th', ['locale' => app()->getLocale()])],
            ['title' => '11th Edition ASEANAPOL Bulletin (2018)', 'type' => 'Web', 'size' => null, 'url' => route('publication.11th', ['locale' => app()->getLocale()])],
            ['title' => '12th Edition ASEANAPOL Bulletin (2019)', 'type' => 'Web', 'size' => null, 'url' => route('publication.12th', ['locale' => app()->getLocale()])],
            ['title' => '13th Edition ASEANAPOL Magazine (2023)', 'type' => 'Web', 'size' => null, 'url' => route('publication.13th', ['locale' => app()->getLocale()])],
        ],
    ],
];
@endphp

<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        @foreach($collections as $collection)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $collection['icon'] }}</span>
                </div>
                <h2 class="text-xl font-bold text-primary dark:text-white">{{ $collection['title'] }}</h2>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-dark-surface border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Document</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 hidden sm:table-cell w-24">Format</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700 dark:text-gray-300 w-32">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                        @foreach($collection['docs'] as $doc)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-gray-400 text-lg flex-shrink-0">
                                        {{ $doc['type'] === 'PDF' ? 'picture_as_pdf' : 'open_in_new' }}
                                    </span>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $doc['title'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ $doc['type'] === 'PDF' ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' }}">
                                    {{ $doc['type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ $doc['url'] }}"
                                   {{ $doc['type'] === 'PDF' ? 'target="_blank" rel="noopener"' : '' }}
                                   class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                                    {{ $doc['type'] === 'PDF' ? 'Download' : 'View' }}
                                    <span class="material-symbols-outlined text-base">
                                        {{ $doc['type'] === 'PDF' ? 'download' : 'arrow_forward' }}
                                    </span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endforeach

    </div>
</section>
@endsection
