{{-- One repeater row. Params: $def (repeater def), $segs (incl. row index), $row, $depth, $labelKey --}}
@php
    $rowName = 'f' . implode('', array_map(fn ($s) => '[' . $s . ']', $segs));
    $title   = trim((string) ($row[$labelKey] ?? ''));
@endphp
<div class="cms-row border border-gray-200 rounded-lg bg-gray-50" data-row>
    <div class="flex items-center gap-2 px-3 py-2 bg-white border-b border-gray-200 rounded-t-lg">
        <span class="material-symbols-outlined text-gray-400" style="font-size:18px;" aria-hidden="true">drag_indicator</span>
        <span class="cms-row-title text-sm font-medium text-gray-700 flex-1 truncate" data-label-key="{{ $labelKey }}">{{ $title !== '' ? \Illuminate\Support\Str::limit(strip_tags($title), 80) : 'New item' }}</span>
        <label class="inline-flex items-center gap-1 text-xs text-gray-500" title="Hide this item on the public page without deleting it">
            <input type="checkbox" name="{{ $rowName }}[_hide]" value="1" @checked(!empty($row['_hide'])) class="rounded border-gray-300" aria-label="Hide this item on the public page"> Hide
        </label>
        <button type="button" data-act="up"   title="Move up" aria-label="Move item up"   class="p-1 text-gray-500 hover:text-gray-800"><span class="material-symbols-outlined" style="font-size:18px;">arrow_upward</span></button>
        <button type="button" data-act="down" title="Move down" aria-label="Move item down" class="p-1 text-gray-500 hover:text-gray-800"><span class="material-symbols-outlined" style="font-size:18px;">arrow_downward</span></button>
        <button type="button" data-act="remove" title="Remove item" aria-label="Remove item" class="p-1 text-red-500 hover:text-red-700"><span class="material-symbols-outlined" style="font-size:18px;">delete</span></button>
    </div>
    <div class="p-3 grid grid-cols-1 {{ ($def['columns'] ?? 1) > 1 ? 'md:grid-cols-' . $def['columns'] : '' }} gap-3">
        @foreach($def['fields'] as $subKey => $subDef)
            @php
                $full = in_array($subDef['type'] ?? 'text', ['textarea', 'richtext', 'html', 'repeater', 'list'], true);
                $subVal = array_key_exists($subKey, $row) ? $row[$subKey] : ($subDef['default'] ?? null);
            @endphp
            <div class="{{ $full ? 'md:col-span-full' : '' }}">
                @include('admin.cms._field', ['def' => $subDef, 'segs' => array_merge($segs, [$subKey]), 'value' => $subVal, 'depth' => $depth])
            </div>
        @endforeach
    </div>
</div>
