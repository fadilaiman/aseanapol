@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'In-Person Training',
    'subtitle' => 'Face-to-face training programmes and workshops for ASEANAPOL member agencies.',
    'breadcrumbs' => [
        ['label' => 'Home',               'url' => route('landing',        ['locale' => app()->getLocale()])],
        ['label' => 'Training',           'url' => route('training.index', ['locale' => app()->getLocale()])],
        ['label' => 'In-Person Training', 'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-24 bg-background dark:bg-dark-surface">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

        <div class="w-24 h-24 rounded-3xl bg-primary/8 dark:bg-primary/20 flex items-center justify-center mx-auto mb-8">
            <span class="material-symbols-outlined text-primary dark:text-accent" style="font-size: 48px;">groups</span>
        </div>

        <h2 class="text-4xl sm:text-5xl font-bold text-primary dark:text-white mb-4">Coming Soon</h2>
        <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
            ASEANAPOL In-Person Training programmes are currently under development.<br>
            Workshops and face-to-face courses will be available here soon.
        </p>

    </div>
</section>
@endsection
