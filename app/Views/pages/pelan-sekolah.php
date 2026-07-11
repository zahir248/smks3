<style>
.zoom-img {
    transition: 0.3s;
    cursor: pointer;
}
.zoom-img:hover {
    transform: scale(1.01);
}

/* Custom lightbox — not Bootstrap modal (avoids transform stacking bugs) */
.pelan-lightbox {
    position: fixed;
    inset: 0;
    z-index: 12000;
    display: none;
    background: rgba(0, 0, 0, 0.94);
}
.pelan-lightbox.is-open {
    display: block;
}
.pelan-lightbox__stage {
    position: absolute;
    inset: 0;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    touch-action: none;
}
.pelan-lightbox__img {
    max-width: 96vw;
    max-height: 92vh;
    width: auto;
    height: auto;
    object-fit: contain;
    user-select: none;
    -webkit-user-drag: none;
    cursor: grab;
    transform-origin: center center;
    will-change: transform;
}
.pelan-lightbox__img.is-dragging {
    cursor: grabbing;
}
.pelan-lightbox__close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 3;
    width: 3rem;
    height: 3rem;
    border: 0;
    border-radius: 999px;
    background: #fff;
    color: #0B3C5D;
    font-size: 1.75rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.4);
    pointer-events: auto;
}
.pelan-lightbox__close:hover,
.pelan-lightbox__close:focus-visible {
    background: #f1f5f9;
    color: #082a42;
    outline: 2px solid #2d8fd4;
    outline-offset: 2px;
}
.pelan-lightbox__hint {
    position: absolute;
    left: 50%;
    bottom: 1rem;
    z-index: 3;
    transform: translateX(-50%);
    margin: 0;
    padding: 0.4rem 0.85rem;
    border-radius: 999px;
    background: rgba(0, 0, 0, 0.55);
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.85rem;
    pointer-events: none;
    white-space: nowrap;
}
@media (max-width: 768px) {
    .pelan-lightbox__close {
        top: 0.75rem;
        right: 0.75rem;
        width: 2.85rem;
        height: 2.85rem;
    }
    .pelan-lightbox__hint {
        font-size: 0.75rem;
        bottom: 0.75rem;
    }
}
</style>

<section class="page-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Pelan Bangunan Sekolah</h2>

        <div class="text-center"
             <?php if ($is_editor): ?>
             data-edit-block="pelan_image"
             data-edit-label="Ganti gambar pelan"
             data-edit-hint="Muat naik gambar pelan bangunan sekolah."
             <?php endif; ?>>
            <img
                id="pelanThumb"
                src="<?= htmlspecialchars($image) ?>"
                alt="Pelan Sekolah Menengah Kebangsaan Seremban 3"
                class="img-fluid shadow rounded zoom-img"
                style="max-width:100%; height:auto;"
            >
        </div>

        <p class="text-center mt-4 text-muted">
            Klik gambar untuk lihat lebih jelas.
            Gunakan scroll atau pinch untuk zoom,
            dan drag untuk gerakkan gambar.
        </p>
    </div>
</section>

<div id="pelanLightbox" class="pelan-lightbox" hidden aria-hidden="true" role="dialog" aria-label="Paparan pelan sekolah">
    <button type="button" class="pelan-lightbox__close" id="pelanLightboxClose" aria-label="Tutup">&times;</button>
    <div class="pelan-lightbox__stage" id="pelanLightboxStage">
        <img
            id="pelanLightboxImg"
            class="pelan-lightbox__img"
            src="<?= htmlspecialchars($image) ?>"
            alt="Pelan Sekolah"
        >
    </div>
    <p class="pelan-lightbox__hint">Scroll untuk zum · Seret untuk gerak · Esc / X untuk tutup</p>
</div>

<script>
(function () {
    var thumb = document.getElementById('pelanThumb');
    var lightbox = document.getElementById('pelanLightbox');
    var stage = document.getElementById('pelanLightboxStage');
    var img = document.getElementById('pelanLightboxImg');
    var closeBtn = document.getElementById('pelanLightboxClose');
    if (!thumb || !lightbox || !stage || !img || !closeBtn) return;

    var scale = 1;
    var originX = 0;
    var originY = 0;
    var startX = 0;
    var startY = 0;
    var isDragging = false;
    var initialDistance = null;

    function updateTransform() {
        img.style.transform = 'translate(' + originX + 'px, ' + originY + 'px) scale(' + scale + ')';
    }

    function resetView() {
        scale = 1;
        originX = 0;
        originY = 0;
        isDragging = false;
        initialDistance = null;
        img.classList.remove('is-dragging');
        updateTransform();
    }

    function openLightbox() {
        lightbox.hidden = false;
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('media-overlay-open');
        resetView();
    }

    function closeLightbox() {
        lightbox.classList.remove('is-open');
        lightbox.hidden = true;
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('media-overlay-open');
        resetView();
    }

    thumb.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        openLightbox();
    });

    closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeLightbox();
    });

    // Click dark area (not the image) to close
    stage.addEventListener('click', function (e) {
        if (e.target === stage) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && lightbox.classList.contains('is-open')) {
            closeLightbox();
        }
    });

    img.addEventListener('wheel', function (e) {
        if (!lightbox.classList.contains('is-open')) return;
        e.preventDefault();
        if (e.deltaY < 0) {
            scale += 0.1;
        } else {
            scale = Math.max(0.5, scale - 0.1);
        }
        updateTransform();
    }, { passive: false });

    img.addEventListener('mousedown', function (e) {
        if (e.button !== 0) return;
        e.preventDefault();
        isDragging = true;
        startX = e.clientX - originX;
        startY = e.clientY - originY;
        img.classList.add('is-dragging');
    });

    document.addEventListener('mouseup', function () {
        isDragging = false;
        img.classList.remove('is-dragging');
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        originX = e.clientX - startX;
        originY = e.clientY - startY;
        updateTransform();
    });

    img.addEventListener('touchstart', function (e) {
        if (e.touches.length === 2) {
            initialDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
        } else if (e.touches.length === 1) {
            isDragging = true;
            startX = e.touches[0].clientX - originX;
            startY = e.touches[0].clientY - originY;
        }
    }, { passive: false });

    img.addEventListener('touchmove', function (e) {
        if (e.touches.length === 2 && initialDistance) {
            e.preventDefault();
            var currentDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            scale *= currentDistance / initialDistance;
            initialDistance = currentDistance;
            updateTransform();
        } else if (e.touches.length === 1 && isDragging) {
            e.preventDefault();
            originX = e.touches[0].clientX - startX;
            originY = e.touches[0].clientY - startY;
            updateTransform();
        }
    }, { passive: false });

    img.addEventListener('touchend', function () {
        isDragging = false;
        initialDistance = null;
    });
})();
</script>
