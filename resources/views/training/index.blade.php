@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Training',
    'subtitle' => 'Capacity-building and professional development programmes for ASEANAPOL member agencies.',
    'breadcrumbs' => [
        ['label' => 'Home',     'url' => route('landing', ['locale' => app()->getLocale()])],
        ['label' => 'Training', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="w-24 h-24 rounded-3xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-primary dark:text-accent" style="font-size: 48px;">model_training</span>
        </div>

        <h2 class="text-4xl sm:text-5xl font-bold text-primary dark:text-white mb-4">Coming Soon</h2>
        <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
            ASEANAPOL Training programmes are currently under development.<br>
            Please check back soon for updates.
        </p>

    </div>
</section>
@endsection
