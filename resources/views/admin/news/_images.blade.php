{{-- Multi-image uploader: drag files in, drag tiles to reorder; first tile = thumbnail. --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Photos</label>
    <div id="img-drop" class="border-2 border-dashed border-gray-200 rounded-lg px-4 py-6 text-center text-sm text-gray-500 cursor-pointer hover:bg-gray-50">
        Drag &amp; drop photos here or <span class="text-blue-600 font-semibold">browse</span>
        <div class="text-xs text-gray-400 mt-1">Select multiple. Max 5 MB each, up to 20.</div>
    </div>
    <input type="file" id="img-input" name="images[]" accept="image/*" multiple data-size-managed="1" class="hidden">
    <div id="img-order"></div>

    <p id="img-hint" class="text-xs text-gray-400 mt-2 hidden">Drag photos to reorder, click × to remove. The first photo is the thumbnail; all photos appear in the article gallery.</p>
    <div id="img-grid" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-3"></div>

    <div class="mt-4">
        <label class="block text-xs font-medium text-gray-500 mb-1">Or use an external thumbnail URL (only used when there are no photos above)</label>
        <input type="text" name="thumbnail_url" value="{{ old('thumbnail_url', isset($news) ? $news->thumbnail : '') }}"
               placeholder="https://… or media/news/…"
               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
    </div>
</div>
<script>
(function () {
    const input = document.getElementById('img-input');
    const drop = document.getElementById('img-drop');
    const grid = document.getElementById('img-grid');
    const hint = document.getElementById('img-hint');
    const orderBox = document.getElementById('img-order');
    // items: {url} for photos already saved, {file} for new uploads
    let items = @json(array_map(fn ($u) => ['url' => $u], $existingImages ?? []));
    let dragFrom = null;

    function sync() {
        const dt = new DataTransfer();
        let n = 0;
        orderBox.innerHTML = '';
        items.forEach(it => {
            const h = document.createElement('input');
            h.type = 'hidden'; h.name = 'image_order[]';
            if (it.file) { dt.items.add(it.file); h.value = 'n:' + (n++); } else { h.value = 'e:' + it.url; }
            orderBox.appendChild(h);
        });
        input.files = dt.files;
        render();
    }

    // Phone photos are 3-8 MB; shrink anything big so a whole set fits one upload (server limit ~20 MB).
    function shrink(f) {
        if (!/^image\/(jpeg|png|webp)$/.test(f.type) || f.size < 1.2 * 1048576) return Promise.resolve(f);
        return new Promise(resolve => {
            const url = URL.createObjectURL(f), img = new Image();
            img.onload = () => {
                const k = Math.min(1, 2400 / Math.max(img.width, img.height));
                const c = document.createElement('canvas');
                c.width = Math.round(img.width * k); c.height = Math.round(img.height * k);
                const g = c.getContext('2d'); g.fillStyle = '#fff'; g.fillRect(0, 0, c.width, c.height); g.drawImage(img, 0, 0, c.width, c.height);
                URL.revokeObjectURL(url);
                c.toBlob(b => resolve(b && b.size < f.size ? new File([b], f.name.replace(/\.\w+$/, '') + '.jpg', { type: 'image/jpeg' }) : f), 'image/jpeg', 0.85);
            };
            img.onerror = () => { URL.revokeObjectURL(url); resolve(f); };
            img.src = url;
        });
    }

    async function add(list) {
        const imgs = Array.from(list).filter(f => f.type.startsWith('image/'));
        for (const f of imgs) {
            if (items.length >= 20) { alert('A maximum of 20 photos is allowed.'); break; }
            const small = await shrink(f);
            if (small.size > 5 * 1048576) { alert(f.name + ' is still over 5 MB after shrinking and was skipped.'); continue; }
            items.push({ file: small });
        }
        const total = items.reduce((t, it) => t + (it.file ? it.file.size : 0), 0);
        if (total > 19 * 1048576) {
            alert('The new photos total ' + (total / 1048576).toFixed(1) + ' MB, over the 19 MB per-save limit. The last ones were removed; save, then add the rest in a second edit.');
            while (items.length && items.reduce((t, it) => t + (it.file ? it.file.size : 0), 0) > 19 * 1048576) items.pop();
        }
        sync();
    }

    function render() {
        grid.innerHTML = '';
        hint.classList.toggle('hidden', items.length === 0);
        items.forEach((it, i) => {
            const tile = document.createElement('div');
            tile.draggable = true;
            tile.className = 'relative rounded-lg overflow-hidden border border-gray-200 cursor-move bg-gray-50';
            tile.innerHTML = '<img class="w-full h-24 object-cover pointer-events-none" alt="">'
                + (i === 0 ? '<span class="absolute top-1 left-1 bg-gray-900 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded">Thumbnail</span>'
                           : '<span class="absolute top-1 left-1 bg-white/90 text-gray-600 text-[10px] font-semibold px-1.5 py-0.5 rounded">' + (i + 1) + '</span>')
                + '<button type="button" class="absolute top-1 right-1 bg-white/90 hover:bg-red-50 text-red-600 rounded-full w-5 h-5 text-xs leading-5">&times;</button>';
            tile.querySelector('img').src = it.file ? URL.createObjectURL(it.file) : it.url;
            tile.querySelector('button').onclick = () => { items.splice(i, 1); sync(); };
            tile.addEventListener('dragstart', e => { dragFrom = i; e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', 'tile'); });
            tile.addEventListener('dragover', e => { if (dragFrom !== null) { e.preventDefault(); tile.classList.add('ring-2', 'ring-blue-300'); } });
            tile.addEventListener('dragleave', () => tile.classList.remove('ring-2', 'ring-blue-300'));
            tile.addEventListener('drop', e => {
                e.preventDefault(); e.stopPropagation();
                if (dragFrom === null || dragFrom === i) { dragFrom = null; return; }
                const [moved] = items.splice(dragFrom, 1);
                items.splice(i, 0, moved);
                dragFrom = null;
                sync();
            });
            tile.addEventListener('dragend', () => { dragFrom = null; });
            grid.appendChild(tile);
        });
    }

    drop.addEventListener('click', () => input.click());
    input.addEventListener('change', () => {
        // the native picker replaced input.files with just the new picks; keep earlier items
        const known = items.filter(it => it.file).map(it => it.file);
        const picked = Array.from(input.files).filter(f => !known.includes(f));
        add(picked);
    });
    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('bg-gray-50'); }));
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('bg-gray-50'); }));
    drop.addEventListener('drop', e => add(e.dataTransfer.files));
    sync();
})();
</script>
