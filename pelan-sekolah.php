<?php
$page_title = 'Pelan Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- School Plan Section -->
<section class="py-5 bg-light">
    <div class="container">

        <h2 class="text-center fw-bold mb-5">Pelan Bangunan Sekolah</h2>
        <div class="text-center">
            <!-- Trigger Modal -->
            <img src="images/pelansekolah.png" alt="Pelan Sekolah Menengah Kebangsaan Seremban 3" 
                 class="img-fluid shadow rounded zoom-img" 
                 style="cursor: pointer; max-width: 100%; height: auto;" 
                 data-bs-toggle="modal" data-bs-target="#zoomModal">
        </div>
        <p class="text-center mt-4 text-muted">Klik gambar untuk lihat lebih jelas. Gunakan scroll atau pinch untuk zoom, drag untuk gerakkan.</p>
    </div>
</section>

<!-- Modal for Zoom -->
<div class="modal fade" id="zoomModal" tabindex="-1" aria-labelledby="zoomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body d-flex justify-content-center align-items-center position-relative p-0 overflow-hidden">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3 text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        <img src="images/pelansekolah.png" alt="Pelan Sekolah" id="modal-img" class="img-fluid" style="cursor: grab;">
      </div>
    </div>
  </div>
</div>

<!-- JS for Zoom + Drag -->
<script>
const modalImg = document.getElementById('modal-img');
let scale = 1, originX = 0, originY = 0, startX = 0, startY = 0, isDragging = false;

// Desktop: zoom in/out with scroll
modalImg.addEventListener('wheel', function(e) {
    e.preventDefault();
    if(e.deltaY < 0) scale += 0.1;
    else scale = Math.max(0.5, scale - 0.1);
    modalImg.style.transform = `scale(${scale}) translate(${originX}px, ${originY}px)`;
});

// Dragging
modalImg.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.clientX - originX;
    startY = e.clientY - originY;
    modalImg.style.cursor = 'grabbing';
});
document.addEventListener('mouseup', () => { isDragging = false; modalImg.style.cursor = 'grab'; });
document.addEventListener('mousemove', (e) => {
    if(!isDragging) return;
    originX = e.clientX - startX;
    originY = e.clientY - startY;
    modalImg.style.transform = `scale(${scale}) translate(${originX}px, ${originY}px)`;
});

// Mobile: pinch zoom
let initialDistance = null;
modalImg.addEventListener('touchstart', function(e) {
    if(e.touches.length === 2){
        initialDistance = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
    }
}, {passive: false});

modalImg.addEventListener('touchmove', function(e) {
    if(e.touches.length === 2 && initialDistance){
        e.preventDefault();
        let currentDistance = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
        scale *= currentDistance / initialDistance;
        initialDistance = currentDistance;
        modalImg.style.transform = `scale(${scale}) translate(${originX}px, ${originY}px)`;
    }
}, {passive: false});

// Reset when modal closed
const zoomModal = document.getElementById('zoomModal');
zoomModal.addEventListener('hidden.bs.modal', () => {
    scale = 1; originX = 0; originY = 0;
    modalImg.style.transform = `scale(${scale}) translate(0,0)`;
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>