@php $__rows = cms($key, 'openings.items'); @endphp
@if(count($__rows))
{{-- Current openings (admin-managed) --}}
<h2 class="text-base font-bold text-gray-900 dark:text-white mb-4">{{ cms($key, 'openings.heading') }}</h2>
<div class="space-y-4 mb-10">
    @foreach($__rows as $job)
    <div class="bg-white dark:bg-dark-card rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">{{ $job['title'] ?? '' }}</h3>
                @if(!empty($job['department']))
                <p class="text-xs font-semibold text-primary dark:text-accent uppercase tracking-wider mt-1">{{ $job['department'] }}</p>
                @endif
            </div>
            @if(!empty($job['apply_link']))
            <a href="{{ $job['apply_link'] }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                <span class="material-symbols-outlined text-base">send</span>
                {{ !empty($job['apply_label']) ? $job['apply_label'] : 'Apply' }}
            </a>
            @endif
        </div>
        @if(!empty($job['location']) || !empty($job['deadline']))
        <div class="flex flex-wrap gap-x-5 gap-y-1 mt-3 text-xs text-gray-500 dark:text-gray-400">
            @if(!empty($job['location']))
            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">location_on</span>{{ $job['location'] }}</span>
            @endif
            @if(!empty($job['deadline']))
            <span class="inline-flex items-center gap-1"><span class="material-symbols-outlined text-base">event</span>{{ $job['deadline'] }}</span>
            @endif
        </div>
        @endif
        @if(!empty($job['description']))
        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mt-3 whitespace-pre-line">{{ $job['description'] }}</p>
        @endif
    </div>
    @endforeach
</div>
@endif
