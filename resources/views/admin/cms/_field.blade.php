{{--
    Recursive field renderer.
    Params: $def (field definition), $segs (array of name segments), $value, $depth (repeater nesting depth, 0 = top)
--}}
@php
    $type    = $def['type'] ?? 'text';
    $name    = 'f' . implode('', array_map(fn ($s) => '[' . $s . ']', $segs));
    $fname   = 'files' . implode('', array_map(fn ($s) => '[' . $s . ']', $segs));
    $id      = 'fld-' . md5($name);
    $label   = $def['label'] ?? \Illuminate\Support\Str::headline(end($segs));
    $inputCls = 'w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200';
    $isPh    = str_contains($name, '__I');
@endphp
<div class="cms-field" data-type="{{ $type }}">
    <label for="{{ $id }}" class="block text-xs font-semibold text-gray-600 mb-1">{{ $label }}</label>

    @switch($type)
        @case('textarea')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $def['rows'] ?? 4 }}" class="{{ $inputCls }}" data-label-src="{{ $def['label_src'] ?? '' }}">{{ is_array($value) ? '' : $value }}</textarea>
            @break

        @case('list')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $def['rows'] ?? 5 }}" class="{{ $inputCls }}" placeholder="One item per line">{{ is_array($value) ? implode("\n", $value) : $value }}</textarea>
            <p class="text-[11px] text-gray-400 mt-1">One item per line.</p>
            @break

        @case('html')
            <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $def['rows'] ?? 8 }}" class="{{ $inputCls }} font-mono text-xs">{{ is_array($value) ? '' : $value }}</textarea>
            <p class="text-[11px] text-gray-400 mt-1">HTML allowed (links, lists, headings, tables, images). Scripts are stripped on save.</p>
            @break

        @case('richtext')
            <input type="hidden" id="{{ $id }}" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}">
            <div class="cms-rt bg-white" data-target="{{ $id }}" style="min-height:140px;"></div>
            @break

        @case('boolean')
            <input type="hidden" name="{{ $name }}" value="0">
            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1" @checked(filter_var($value, FILTER_VALIDATE_BOOLEAN)) class="rounded border-gray-300">
                {{ $def['help'] ?? 'Enabled' }}
            </label>
            @break

        @case('select')
            <select id="{{ $id }}" name="{{ $name }}" class="{{ $inputCls }}">
                @foreach(($def['options'] ?? []) as $ov => $ol)
                    @php $optVal = is_int($ov) ? $ol : $ov; @endphp
                    <option value="{{ $optVal }}" @selected((string) $value === (string) $optVal)>{{ $ol }}</option>
                @endforeach
            </select>
            @break

        @case('image')
        @case('file')
            <div class="cms-upload flex flex-col gap-2">
                @if($type === 'image')
                    <img src="{{ $value && !$isPh ? cms_url($value) : '' }}" alt="" class="cms-preview max-h-24 w-auto rounded border border-gray-200 bg-gray-50 {{ $value ? '' : 'hidden' }}" style="max-width:240px;">
                @endif
                <div class="flex gap-2">
                    <input type="text" id="{{ $id }}" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}" placeholder="Path or URL (e.g. media/… or https://…)" class="{{ $inputCls }} cms-path">
                    <button type="button" data-act="clear-file" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 whitespace-nowrap">Clear</button>
                </div>
                <input type="file" name="{{ $fname }}" accept="{{ $type === 'image' ? 'image/png,image/jpeg,image/gif,image/webp' : '.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.jpg,.jpeg,.png' }}" class="text-xs text-gray-500">
                <p class="text-[11px] text-gray-400">Upload a new file to replace it (a new filename is always generated). Max 25 MB.</p>
            </div>
            @break

        @case('repeater')
            @php
                $rows = is_array($value) ? array_values($value) : [];
                $ph   = '__I' . ($depth + 1) . '__';
                $labelKey = $def['item_label'] ?? array_key_first($def['fields']);
            @endphp
            <div class="cms-repeater border border-gray-200 rounded-xl p-3 bg-white" data-repeater data-ph="{{ $ph }}" data-next="{{ count($rows) }}">
                <input type="hidden" name="{{ $name }}[__present]" value="1">
                <div class="cms-rows space-y-2">
                    @foreach($rows as $i => $row)
                        @include('admin.cms._row', ['def' => $def, 'segs' => array_merge($segs, [$i]), 'row' => (array) $row, 'depth' => $depth + 1, 'labelKey' => $labelKey])
                    @endforeach
                </div>
                <template>
                    @include('admin.cms._row', ['def' => $def, 'segs' => array_merge($segs, [$ph]), 'row' => [], 'depth' => $depth + 1, 'labelKey' => $labelKey])
                </template>
                <button type="button" data-act="add-row" class="mt-3 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-300 text-blue-700 hover:bg-blue-50">
                    <span class="material-symbols-outlined" style="font-size:16px;">add</span> {{ $def['add_label'] ?? 'Add item' }}
                </button>
            </div>
            @break

        @case('number')
            <input type="number" step="any" id="{{ $id }}" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}" class="{{ $inputCls }}">
            @break

        @case('date')
            <input type="date" id="{{ $id }}" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}" class="{{ $inputCls }}">
            @break

        @default {{-- text, url, icon --}}
            <input type="{{ $type === 'url' ? 'text' : 'text' }}" id="{{ $id }}" name="{{ $name }}" value="{{ is_array($value) ? '' : $value }}" class="{{ $inputCls }}" data-label-src="{{ $def['label_src'] ?? '' }}"
                   @if($type === 'icon') placeholder="Material Symbols icon name, e.g. shield" @endif>
            @if($type === 'icon')
                <p class="text-[11px] text-gray-400 mt-1">Icon name from <a class="underline" target="_blank" rel="noopener" href="https://fonts.google.com/icons">Material Symbols</a>.</p>
            @endif
    @endswitch

    @if(!empty($def['help']) && $type !== 'boolean')
        <p class="text-[11px] text-gray-400 mt-1">{{ $def['help'] }}</p>
    @endif
</div>
