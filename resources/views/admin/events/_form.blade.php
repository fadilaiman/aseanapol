@php
    $in = 'w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500';
    $v = fn ($f) => old($f, $event->{$f});
    $d = fn ($f) => old($f, optional($event->{$f})->format('Y-m-d'));
    $err = fn ($f) => $errors->first($f);
@endphp

<div class="max-w-3xl">
    <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-5">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to Events
    </a>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5" data-testid="form-errors">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-5" id="event-form" novalidate>
            @csrf
            @if($method !== 'POST') @method($method) @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Appears on <span class="text-red-500">*</span></label>
                <select name="kind" id="kind" class="{{ $in }}">
                    @foreach(\App\Models\Event::KINDS as $k => $label)
                        @php $pages = ['calendar' => 'Event Calendar page', 'conference' => 'Conferences page', 'training' => 'Training Programs page']; @endphp
                        <option value="{{ $k }}" @selected($v('kind') === $k)>{{ $label }} — {{ $pages[$k] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ $v('title') }}" class="{{ $in }}" placeholder="e.g. 45th ASEANAPOL Conference">
                <p class="text-xs text-gray-400 mt-1" data-kinds="conference">For conferences the title is only an admin label; the page shows the number and dates below.</p>
            </div>

            {{-- Calendar + conference: dates --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-kinds="calendar conference">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start date <span class="text-red-500" data-kinds="calendar">*</span></label>
                    <input type="date" name="start_date" value="{{ $d('start_date') }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End date</label>
                    <input type="date" name="end_date" value="{{ $d('end_date') }}" class="{{ $in }}">
                    <p class="text-xs text-gray-400 mt-1">Leave empty for a one-day event.</p>
                </div>
            </div>

            <div data-kinds="calendar">
                <label class="block text-sm font-medium text-gray-700 mb-1">Event type <span class="text-red-500">*</span></label>
                <select name="type" class="{{ $in }}">
                    @foreach(\App\Models\Event::TYPES as $t)
                        <option value="{{ $t }}" @selected($v('type') === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Conference only --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" data-kinds="conference">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Conference number <span class="text-red-500">*</span></label>
                    <input type="text" name="conf_number" value="{{ $v('conf_number') }}" placeholder="46th" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year <span class="text-red-500">*</span></label>
                    <input type="number" name="conf_year" value="{{ $v('conf_year') }}" placeholder="2027" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="{{ $in }}">
                        @foreach(\App\Models\Event::STATUSES as $k => $label)
                            <option value="{{ $k }}" @selected($v('status') === $k)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dates as displayed</label>
                    <input type="text" name="dates_label" value="{{ $v('dates_label') }}" placeholder="22–26 July 2027" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Flag emoji</label>
                    <input type="text" name="flag" value="{{ $v('flag') }}" placeholder="🇵🇭" class="{{ $in }}">
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Theme</label>
                    <input type="text" name="theme" value="{{ $v('theme') }}" class="{{ $in }}">
                </div>
            </div>

            {{-- Training only --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-kinds="training">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                    <input type="text" name="category" value="{{ $v('category') }}" list="category-list" placeholder="Cybercrime" class="{{ $in }}">
                    <datalist id="category-list">
                        @foreach(['Governance', 'Cybercrime', 'TIP', 'Drugs', 'Financial Crime', 'Wildlife'] as $c)<option value="{{ $c }}">@endforeach
                    </datalist>
                    <p class="text-xs text-gray-400 mt-1">The six existing categories have their own colour; others show in grey.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Abbreviation</label>
                    <input type="text" name="abbr" value="{{ $v('abbr') }}" placeholder="CIT" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (Material Symbols name)</label>
                    <input type="text" name="icon" value="{{ $v('icon') }}" placeholder="security" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                    <input type="text" name="frequency" value="{{ $v('frequency') }}" placeholder="Annual" class="{{ $in }}">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latest edition / partner note</label>
                    <input type="text" name="latest" value="{{ $v('latest') }}" class="{{ $in }}">
                </div>
            </div>

            <div data-kinds="training">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" class="{{ $in }} resize-y">{{ $v('description') }}</textarea>
            </div>

            {{-- Shared --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-kinds="calendar conference">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ $v('city') }}" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                    <input type="text" name="country" value="{{ $v('country') }}" class="{{ $in }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link (optional)</label>
                    <input type="text" name="link" value="{{ $v('link') }}" placeholder="https://…" class="{{ $in }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Link text</label>
                    <input type="text" name="link_label" value="{{ $v('link_label') }}" placeholder="More information" class="{{ $in }}">
                    <p class="text-xs text-gray-400 mt-1">Shown on the link; defaults to “More information”.</p>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Image (optional)</label>
                @if($event->image_url)
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ $event->image_url }}" alt="" class="w-24 h-16 object-cover rounded-lg border border-gray-200">
                        <label class="flex items-center gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="remove_image" value="1" class="w-4 h-4 rounded border-gray-300"> Remove image
                        </label>
                    </div>
                @endif
                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-400 mt-1">JPG, PNG or WebP, max 4 MB. Uploading replaces the current image.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sort order</label>
                    <input type="number" name="sort_order" value="{{ $v('sort_order') }}" placeholder="auto" class="{{ $in }}">
                    <p class="text-xs text-gray-400 mt-1">Lower numbers first within the same year (calendar) or overall (training). Leave blank: training programmes go last, calendar events are ordered by date.</p>
                </div>
                <div class="flex items-center pb-3 sm:pt-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="hidden" name="is_published" value="0">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $event->is_published ? 1 : 0) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm font-medium text-gray-700">Visible on the public site</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.events.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg transition-colors">Cancel</a>
                <button type="submit" data-testid="save-event" class="px-5 py-2 text-sm font-semibold text-white rounded-lg transition-colors" style="background:#0a2540;">{{ $submit }}</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var sel = document.getElementById('kind');
    function sync() {
        document.querySelectorAll('#event-form [data-kinds]').forEach(function (el) {
            el.style.display = el.getAttribute('data-kinds').split(' ').indexOf(sel.value) === -1 ? 'none' : '';
        });
    }
    sel.addEventListener('change', sync);
    sync();
})();
</script>
