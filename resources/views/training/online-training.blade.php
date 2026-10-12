@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => cms('training.online-training', 'hero.title'),
    'subtitle' => cms('training.online-training', 'hero.subtitle'),
    'breadcrumbs' => [
        ['label' => 'Home',            'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => cms('training.index', 'hero.title'),        'url' => route('training.index', ['locale' => app()->getLocale()])],
        ['label' => cms('training.online-training', 'hero.title'), 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(cms_on('training.online-training', 'table'))
        <div class="overflow-x-auto rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-6 py-4 font-semibold w-12 text-center">{{ cms('training.online-training', 'table.col_no') }}</th>
                        <th class="px-6 py-4 font-semibold">{{ cms('training.online-training', 'table.col_title') }}</th>
                        <th class="px-6 py-4 font-semibold whitespace-nowrap">{{ cms('training.online-training', 'table.col_date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach(cms('training.online-training', 'table.rows') as $row)
                    <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-dark-surface' : 'bg-white dark:bg-dark-card' }} hover:bg-blue-50 dark:hover:bg-primary/10 transition-colors">
                        <td class="px-6 py-5 text-center font-semibold text-primary dark:text-accent">{{ $loop->iteration }}</td>
                        <td class="px-6 py-5 text-gray-800 dark:text-gray-200 font-medium">{{ $row['title'] }}</td>
                        <td class="px-6 py-5 text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $row['date'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</section>
@endsection
