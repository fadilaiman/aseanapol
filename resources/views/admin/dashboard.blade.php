@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-2 text-blue-600 mb-3">
            <span class="material-symbols-outlined" style="font-size:22px;">play_circle</span>
            <span class="text-sm text-gray-500 font-medium">Videos</span>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $videoCount }}</div>
        <div class="text-xs text-green-600 mt-1">{{ $publishedVideoCount }} published</div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center gap-2 text-indigo-600 mb-3">
            <span class="material-symbols-outlined" style="font-size:22px;">mail</span>
            <span class="text-sm text-gray-500 font-medium">Newsletters</span>
        </div>
        <div class="text-3xl font-bold text-gray-900">{{ $newsletterCount }}</div>
        <div class="text-xs text-green-600 mt-1">{{ $publishedNewsletterCount }} published</div>
    </div>

</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <a href="{{ route('admin.video-gallery.create') }}"
       class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4 hover:border-blue-300 hover:shadow-sm transition-all group">
        <span class="material-symbols-outlined text-blue-500 group-hover:text-blue-600" style="font-size:24px;">add_circle</span>
        <div>
            <div class="font-semibold text-gray-800 text-sm">Add Video</div>
            <div class="text-gray-400 text-xs">Add a new video to the gallery</div>
        </div>
    </a>
    <a href="{{ route('admin.newsletters.create') }}"
       class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4 hover:border-indigo-300 hover:shadow-sm transition-all group">
        <span class="material-symbols-outlined text-indigo-500 group-hover:text-indigo-600" style="font-size:24px;">add_circle</span>
        <div>
            <div class="font-semibold text-gray-800 text-sm">Add Newsletter</div>
            <div class="text-gray-400 text-xs">Add a new newsletter edition</div>
        </div>
    </a>
</div>
@endsection
