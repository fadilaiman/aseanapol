<div class="border border-gray-100 bg-gray-50 rounded-lg p-3" data-sponsor-row data-testid="sponsor-row">
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Name (also logo alt text)</label>
            <input type="text" name="sponsors[{{ $key }}][name]" value="{{ $r['name'] }}" maxlength="120" data-f="name" class="{{ $inp }}">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Website (optional, http/https)</label>
            <input type="text" name="sponsors[{{ $key }}][url]" value="{{ $r['url'] }}" maxlength="255" placeholder="https://" data-f="url" class="{{ $inp }}">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Logo file (png, jpg, webp, gif; max 2 MB)</label>
            <input type="file" name="sponsors[{{ $key }}][logo_file]" accept="image/png,image/jpeg,image/webp,image/gif" data-f="logo_file" class="{{ $inp }} bg-white">
            <input type="hidden" name="sponsors[{{ $key }}][logo_existing]" value="{{ $r['logo'] }}">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">&hellip;or existing media path / URL</label>
            <input type="text" name="sponsors[{{ $key }}][logo_url]" value="" placeholder="media/logos/… or https://…" data-f="logo_url" class="{{ $inp }}">
        </div>
    </div>
    <div class="flex items-center justify-between mt-3">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            @if($r['logo'])
                <img src="{{ asset($r['logo']) }}" alt="" class="h-8 w-auto bg-white border border-gray-200 rounded" data-testid="sponsor-preview">
                <span class="font-mono break-all">{{ $r['logo'] }}</span>
            @else
                <span>No logo yet</span>
            @endif
        </div>
        <div class="flex gap-1">
            <button type="button" data-act="up" class="px-2 py-1 text-xs border border-gray-200 rounded bg-white hover:bg-gray-50" title="Move up">&uarr;</button>
            <button type="button" data-act="down" class="px-2 py-1 text-xs border border-gray-200 rounded bg-white hover:bg-gray-50" title="Move down">&darr;</button>
            <button type="button" data-act="remove" class="px-2 py-1 text-xs border border-red-200 text-red-600 rounded bg-white hover:bg-red-50">Remove</button>
        </div>
    </div>
</div>
