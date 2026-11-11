{{-- Multi-image uploader: drag files in, drag tiles to reorder; first tile = thumbnail. --}}
<div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Photos</label>
    @isset($news)
        @if($news->thumbnail)
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ asset($news->thumbnail) }}" alt="current thumbnail" class="h-16 rounded-lg object-cover">
                <span class="text-xs text-gray-400">Current thumbnail (replaced if you upload photos below)</span>
            </div>
        @endif
    @endisset

    <div id="img-drop" class="border-2 border-dashed border-gray-200 rounded-lg px-4 py-6 text-center text-sm text-gray-500 cursor-pointer hover:bg-gray-50">
        Drag &amp; drop photos here or <span class="text-blue-600 font-semibold">browse</span>
        <div class="text-xs text-gray-400 mt-1">Select multiple. Max 5 MB each, up to 20.</div>
    </div>
    <input type="file" id="img-input" name="images[]" accept="image/*" multiple class="hidden">

    <p id="img-hint" class="text-xs text-gray-400 mt-2 hidden">Drag the photos to change their order. The first photo is the thumbnail; all photos are added to the article as a gallery.</p>
    <div id="img-grid" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-3"></div>

    <div class="mt-4">
        <label class="block text-xs font-medium text-gray-500 mb-1">Or use an external thumbnail URL (ignored when photos are uploaded)</label>
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
    let files = [];
    let dragFrom = null;

    function sync() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
        render();
    }

    function add(list) {
        Array.from(list).filter(f => f.type.startsWith('image/')).forEach(f => {
            if (files.length < 20) files.push(f);
        });
        sync();
    }

    function render() {
        grid.innerHTML = '';
        hint.classList.toggle('hidden', files.length === 0);
        files.forEach((f, i) => {
            const tile = document.createElement('div');
            tile.draggable = true;
            tile.className = 'relative rounded-lg overflow-hidden border border-gray-200 cursor-move bg-gray-50';
            tile.innerHTML = '<img class="w-full h-24 object-cover" alt="">'
                + (i === 0 ? '<span class="absolute top-1 left-1 bg-gray-900 text-white text-[10px] font-semibold px-1.5 py-0.5 rounded">Thumbnail</span>'
                           : '<span class="absolute top-1 left-1 bg-white/90 text-gray-600 text-[10px] font-semibold px-1.5 py-0.5 rounded">' + (i + 1) + '</span>')
                + '<button type="button" class="absolute top-1 right-1 bg-white/90 hover:bg-red-50 text-red-600 rounded-full w-5 h-5 text-xs leading-5">&times;</button>';
            tile.querySelector('img').src = URL.createObjectURL(f);
            tile.querySelector('button').onclick = () => { files.splice(i, 1); sync(); };
            tile.addEventListener('dragstart', e => { dragFrom = i; e.dataTransfer.effectAllowed = 'move'; e.dataTransfer.setData('text/plain', 'tile'); });
            tile.addEventListener('dragover', e => { if (dragFrom !== null) { e.preventDefault(); tile.classList.add('ring-2', 'ring-blue-300'); } });
            tile.addEventListener('dragleave', () => tile.classList.remove('ring-2', 'ring-blue-300'));
            tile.addEventListener('drop', e => {
                e.preventDefault(); e.stopPropagation();
                if (dragFrom === null || dragFrom === i) { dragFrom = null; return; }
                const [moved] = files.splice(dragFrom, 1);
                files.splice(i, 0, moved);
                dragFrom = null;
                sync();
            });
            tile.addEventListener('dragend', () => { dragFrom = null; });
            grid.appendChild(tile);
        });
    }

    drop.addEventListener('click', () => input.click());
    input.addEventListener('change', () => {
        // the native picker replaced input.files; merge it into our list
        const picked = Array.from(input.files).filter(f => !files.includes(f));
        files = files.concat(picked).slice(0, 20);
        sync();
    });
    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('bg-gray-50'); }));
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('bg-gray-50'); }));
    drop.addEventListener('drop', e => add(e.dataTransfer.files));
})();
</script>
