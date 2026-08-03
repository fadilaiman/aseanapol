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
        <div class="album-section" id="album-{{ $album['key'] }}" data-album="{{ $album['key'] }}" data-offset="{{ count($album['images']) }}">
            <h3 class="text-lg font-bold text-primary dark:text-white mb-5">{{ $album['label'] }}</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mb-6 album-grid">
                @foreach($album['images'] as $img)
                <div class="relative aspect-square overflow-hidden rounded-lg group bg-gray-100 dark:bg-dark-card lightbox-zone">
                    <img src="{{ asset($img) }}"
                         alt="{{ $album['label'] }}"
                         loading="lazy"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @endforeach
            </div>
            @if($album['hasMore'])
            <div class="text-center mb-12">
                <button type="button" onclick="loadMoreGallery('{{ $album['key'] }}', this)"
                    class="px-5 py-2 rounded-full text-sm font-semibold bg-white dark:bg-dark-card text-primary dark:text-accent border border-gray-200 dark:border-gray-600 hover:border-primary/30 transition-colors">
                    Load more
                </button>
            </div>
            @else
            <div class="mb-12"></div>
            @endif
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

@push('scripts')
<script>
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

function loadMoreGallery(albumKey, btn) {
    const section = document.getElementById('album-' + albumKey);
    const grid = section.querySelector('.album-grid');
    const offset = parseInt(section.dataset.offset, 10) || 0;

    btn.disabled = true;
    btn.textContent = 'Loading…';

    fetch('{{ route("news-media.gallery.more", ["locale" => app()->getLocale()]) }}?album=' + encodeURIComponent(albumKey) + '&offset=' + offset)
        .then(r => r.json())
        .then(data => {
            data.images.forEach(src => {
                const cell = document.createElement('div');
                cell.className = 'relative aspect-square overflow-hidden rounded-lg group bg-gray-100 dark:bg-dark-card lightbox-zone';
                cell.innerHTML = '<img src="' + src + '" alt="" loading="lazy" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">';
                grid.appendChild(cell);
            });
            section.dataset.offset = offset + data.images.length;
            if (data.hasMore) {
                btn.disabled = false;
                btn.textContent = 'Load more';
            } else {
                btn.remove();
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Load more';
        });
}
</script>
@endpush
@endsection
