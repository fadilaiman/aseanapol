@php
// Shared breadcrumb labels resolve through the CMS so editing a parent page title / the "Home" label updates every crumb.
$__crumbMap = [
    'Home'            => ['site.header-footer', 'menu.home_label'],
    'About ASEANAPOL' => ['about.index', 'hero.title'],
    'Guidelines'      => ['guidelines.index', 'hero.title'],
    'Leadership'      => ['about-people.leadership', 'hero.title'],
];
$__crumbLabel = fn ($l) => isset($__crumbMap[$l]) ? (cms($__crumbMap[$l][0], $__crumbMap[$l][1]) ?: $l) : $l;
@endphp
<section class="bg-primary dark:bg-dark-card pb-12 relative overflow-hidden" style="padding-top: calc(var(--header-h, 5rem) + 1rem)">
    {{-- Subtle pattern overlay --}}
    <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%221%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center flex-wrap gap-1 text-sm text-white/60 mb-5">
            @foreach($breadcrumbs as $crumb)
                @if(!$loop->last)
                    <a href="{{ $crumb['url'] }}" class="hover:text-accent transition-colors">{{ $__crumbLabel($crumb['label']) }}</a>
                    <span class="material-symbols-outlined text-sm leading-none">chevron_right</span>
                @else
                    <span class="text-white/90">{{ $__crumbLabel($crumb['label']) }}</span>
                @endif
            @endforeach
        </nav>

        {{-- Title --}}
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">
            {{ $title }}
        </h1>

        @if(isset($subtitle))
            <p class="text-white/70 mt-3 text-lg max-w-2xl leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>
</section>
