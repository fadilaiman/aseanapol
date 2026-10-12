@if(cms_img($key, 'document.file'))
                <div class="mt-8">
                    <a href="{{ cms_img($key, 'document.file') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-semibold text-sm hover:bg-primary/90 transition-colors">
                        <span class="material-symbols-outlined text-base">download</span>
                        {{ cms($key, 'document.label') }}
                    </a>
                </div>
@endif
