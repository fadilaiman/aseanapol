{{-- Global image lightbox. Any <img> inside an element with class "lightbox-zone",
     or any <img class="lightbox-img"> anywhere, opens full-size on click. --}}
<div id="lightbox" class="fixed inset-0 z-[100] bg-black/90 flex items-center justify-center hidden" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white/70 hover:text-white" onclick="closeLightbox()">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    <img id="lightbox-img" src="" alt="" class="max-w-[90vw] max-h-[90vh] object-contain rounded shadow-2xl" onclick="event.stopPropagation()">
</div>

<style>
    .lightbox-zone img, img.lightbox-img { cursor: zoom-in; }
</style>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

// Delegated click handler so future images (new news items, galleries, etc.)
// get the lightbox automatically without any per-page wiring.
document.addEventListener('click', function (e) {
    var img = e.target.closest('img');
    if (!img) return;
    if (img.closest('.lightbox-zone') || img.classList.contains('lightbox-img')) {
        openLightbox(img.currentSrc || img.src);
    }
});
</script>
