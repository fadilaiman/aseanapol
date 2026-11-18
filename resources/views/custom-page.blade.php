@extends('layouts.app')
@section('title', $page->title)

@section('page_header')
@include('partials.page-hero', [
    'title'    => $page->title,
    'subtitle' => '',
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => $page->title, 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-dark-card rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <div class="prose prose-gray dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed space-y-4">
                {!! $page->body !!}
            </div>
        </div>
    </div>
</section>
@endsection
