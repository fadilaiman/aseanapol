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
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

        @forelse($collections as $collection)
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-primary dark:text-accent text-xl">{{ $collection->icon }}</span>
                </div>
                <h2 class="text-xl font-bold text-primary dark:text-white">{{ $collection->name }}</h2>
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
                        @foreach($collection->publishedItems as $doc)
                        @php $isPdf = $doc->type === 'pdf'; @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-gray-400 text-lg flex-shrink-0">
                                        {{ $isPdf ? 'picture_as_pdf' : 'open_in_new' }}
                                    </span>
                                    <span class="text-gray-800 dark:text-gray-200 font-medium">{{ $doc->title }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden sm:table-cell">
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                    {{ $isPdf ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : 'bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' }}">
                                    {{ $isPdf ? 'PDF' : 'Web' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($doc->url)
                                    @if($isPdf)
                                        <a href="{{ $doc->url }}" target="_blank" rel="noopener"
                                           class="inline-flex items-center gap-1.5 bg-primary hover:bg-primary/90 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-base">download</span> Download PDF
                                        </a>
                                    @else
                                        <a href="{{ $doc->url }}"
                                           class="inline-flex items-center gap-1.5 text-primary dark:text-accent text-sm font-semibold hover:underline">
                                            View
                                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                                        </a>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-16 text-gray-400">
            <span class="material-symbols-outlined text-4xl mb-3 block">library_books</span>
            <p>Documents will be published here shortly.</p>
        </div>
        @endforelse

    </div>
</section>
@endsection
