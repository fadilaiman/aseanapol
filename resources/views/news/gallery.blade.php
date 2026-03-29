@extends('layouts.app')

@section('page_header')
@include('partials.page-hero', [
    'title'    => 'Photo Gallery',
    'subtitle' => 'Images from ASEANAPOL conferences, activities, and member organisations.',
    'breadcrumbs' => [
        ['label' => 'Home',        'url' => route('landing',         ['locale' => app()->getLocale()])],
        ['label' => 'News & Media','url' => route('news-media.index', ['locale' => app()->getLocale()])],
        ['label' => 'Gallery',     'url' => ''],
    ],
])
@endsection

@section('content')
<section class="py-16 bg-background dark:bg-dark-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Tab bar --}}
        <div class="flex flex-wrap gap-2 mb-10" id="gallery-tabs">
            <button onclick="showAlbum('all')" data-tab="all"
                class="tab-btn px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-primary text-white">
                All
            </button>
            @foreach($albums as $album)
                @if($album['count'] > 0)
                <button onclick="showAlbum('{{ $album['key'] }}')" data-tab="{{ $album['key'] }}"
                    class="tab-btn px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-white dark:bg-dark-card text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:border-primary/30">
                    {{ $album['label'] }}
                    <span class="ml-1 text-gray-400 text-xs">({{ $album['count'] }})</span>
                </button>
                @endif
            @endforeach
        </div>

        {{-- Albums --}}
        @foreach($albums as $album)
        @if($album['count'] > 0)
        <div class="album-section" id="album-{{ $album['key'] }}">
            <h3 class="text-lg font-bold text-primary dark:text-white mb-5">{{ $album['label'] }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mb-12">
                @foreach($album['images'] as $img)
                <div class="relative aspect-square overflow-hidden rounded-lg cursor-pointer group bg-gray-100 dark:bg-dark-card"
                     onclick="openLightbox('{{ asset($img) }}')">
                    <img src="{{ asset($img) }}"
                         alt="{{ $album['label'] }}"
                         loading="lazy"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach

        @php $total = array_sum(array_column($albums, 'count')); @endphp
        @if($total === 0)
        <div class="text-center py-20 text-gray-400">
            <span class="material-symbols-outlined text-5xl mb-4 block">image_not_supported</span>
            <p>No gallery images available.</p>
        </div>
        @endif
    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center hidden" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white/70 hover:text-white" onclick="closeLightbox()">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-[90vw] max-h-[90vh] object-contain rounded shadow-2xl" onclick="event.stopPropagation()">
</div>

@push('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

function showAlbum(key) {
    // Update tab styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        const active = btn.dataset.tab === key || (key === 'all' && btn.dataset.tab === 'all');
        btn.className = active
            ? 'tab-btn px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-primary text-white'
            : 'tab-btn px-4 py-2 rounded-full text-sm font-semibold transition-colors bg-white dark:bg-dark-card text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 hover:border-primary/30';
    });
    // Show/hide albums
    document.querySelectorAll('.album-section').forEach(section => {
        section.style.display = (key === 'all' || section.id === 'album-' + key) ? '' : 'none';
    });
}
</script>
@endpush
@endsection
