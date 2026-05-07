@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'E-Learning Platform',
    'subtitle' => 'Online training and capacity-building resources for ASEANAPOL member agencies.',
    'breadcrumbs' => [
        ['label' => 'Home',             'url' => route('landing',              ['locale' => app()->getLocale()])],
        ['label' => 'Data & Resources', 'url' => route('data-resources.index', ['locale' => app()->getLocale()])],
        ['label' => 'E-Learning',       'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="w-24 h-24 rounded-3xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-primary dark:text-accent" style="font-size: 48px;">school</span>
        </div>

        <h2 class="text-4xl sm:text-5xl font-bold text-primary dark:text-white mb-4">Coming Soon</h2>
        <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
            The ASEANAPOL E-Learning Platform is currently under development.<br>
            Training courses and materials will be available here soon.
        </p>

    </div>
</section>
@endsection
