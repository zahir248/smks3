<?php
$images = is_array($images ?? null) && $images !== []
    ? array_values($images)
    : [!empty($image) ? (string) $image : 'images/no-image.png'];
$editImages = is_array($editImages ?? null) ? array_values($editImages) : [];
$imagesJson = json_encode($editImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
<style>
.zoom-img {
    transition: 0.3s;
    cursor: pointer;
}
.zoom-img:hover {
    transform: scale(1.01);
}

.pelan-gallery {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
    max-width: 900px;
    margin-inline: auto;
}
.pelan-gallery__item {
    margin: 0;
    text-align: center;
}
.pelan-gallery__item .pelan-thumb {
    display: inline-block;
    margin-inline: auto;
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
.pelan-lightbox__nav {
    position: absolute;
    top: 50%;
    z-index: 3;
    transform: translateY(-50%);
    width: 2.75rem;
    height: 2.75rem;
    border: 0;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.92);
    color: #0B3C5D;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    display: none;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35);
}
.pelan-lightbox.has-many .pelan-lightbox__nav {
    display: flex;
}
.pelan-lightbox__nav:hover,
.pelan-lightbox__nav:focus-visible {
    background: #fff;
    outline: 2px solid #2d8fd4;
    outline-offset: 2px;
}
.pelan-lightbox__prev {
    left: 1rem;
}
.pelan-lightbox__next {
    right: 1rem;
}
.pelan-lightbox__counter {
    position: absolute;
    top: 1.15rem;
    left: 50%;
    z-index: 3;
    transform: translateX(-50%);
    margin: 0;
    padding: 0.35rem 0.75rem;
    border-radius: 999px;
    background: rgba(0, 0, 0, 0.55);
    color: rgba(255, 255, 255, 0.92);
    font-size: 0.85rem;
    pointer-events: none;
    display: none;
}
.pelan-lightbox.has-many .pelan-lightbox__counter {
    display: block;
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
    .pelan-lightbox__nav {
        width: 2.5rem;
        height: 2.5rem;
        font-size: 1.35rem;
    }
    .pelan-lightbox__prev {
        left: 0.5rem;
    }
    .pelan-lightbox__next {
        right: 0.5rem;
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

        <div class="pelan-gallery"
             <?php if ($is_editor): ?>
             data-edit-block="pelan_image"
             data-edit-label="Urus gambar pelan"
             data-edit-hint="Muat naik satu atau lebih gambar. Gambar baharu ditambah tanpa menggantikan yang lama."
             data-images-json="<?= htmlspecialchars($imagesJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <?php foreach ($images as $idx => $src): ?>
            <figure class="pelan-gallery__item">
                <img
                    class="img-fluid shadow rounded zoom-img pelan-thumb"
                    src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                    alt="Pelan Sekolah Menengah Kebangsaan Seremban 3<?= count($images) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                    data-index="<?= (int) $idx ?>"
                    style="max-width:100%; height:auto;"
                    loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                    decoding="async"
                >
            </figure>
            <?php endforeach; ?>
        </div>

        <?php if ($is_editor): ?>
        <p class="text-center small text-muted mt-3 mb-0">Boleh ada lebih dari satu gambar pelan. Muat naik baharu tidak menggantikan yang lama.</p>
        <?php endif; ?>

        <p class="text-center mt-4 text-muted">
            Klik gambar untuk lihat lebih jelas.
            Gunakan scroll atau pinch untuk zoom,
            dan drag untuk gerakkan gambar.
        </p>
    </div>
</section>

<div id="pelanLightbox" class="pelan-lightbox<?= count($images) > 1 ? ' has-many' : '' ?>" hidden aria-hidden="true" role="dialog" aria-label="Paparan pelan sekolah">
    <p class="pelan-lightbox__counter" id="pelanLightboxCounter" aria-live="polite">1 / <?= (int) count($images) ?></p>
    <button type="button" class="pelan-lightbox__close" id="pelanLightboxClose" aria-label="Tutup">&times;</button>
    <button type="button" class="pelan-lightbox__nav pelan-lightbox__prev" id="pelanLightboxPrev" aria-label="Gambar sebelumnya">&#8249;</button>
    <button type="button" class="pelan-lightbox__nav pelan-lightbox__next" id="pelanLightboxNext" aria-label="Gambar seterusnya">&#8250;</button>
    <div class="pelan-lightbox__stage" id="pelanLightboxStage">
        <img
            id="pelanLightboxImg"
            class="pelan-lightbox__img"
            src="<?= htmlspecialchars($images[0], ENT_QUOTES, 'UTF-8') ?>"
            alt="Pelan Sekolah"
        >
    </div>
    <p class="pelan-lightbox__hint">Scroll untuk zum · Seret untuk gerak · Esc / X untuk tutup</p>
</div>

<script>
(function () {
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.pelan-thumb'));
    var lightbox = document.getElementById('pelanLightbox');
    var stage = document.getElementById('pelanLightboxStage');
    var img = document.getElementById('pelanLightboxImg');
    var closeBtn = document.getElementById('pelanLightboxClose');
    var prevBtn = document.getElementById('pelanLightboxPrev');
    var nextBtn = document.getElementById('pelanLightboxNext');
    var counter = document.getElementById('pelanLightboxCounter');
    if (!thumbs.length || !lightbox || !stage || !img || !closeBtn) return;

    var sources = thumbs.map(function (el) { return el.getAttribute('src') || ''; }).filter(Boolean);
    if (!sources.length) return;

    var currentIndex = 0;
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

    function updateCounter() {
        if (!counter) return;
        counter.textContent = (currentIndex + 1) + ' / ' + sources.length;
    }

    function showImage(index) {
        if (!sources.length) return;
        currentIndex = (index + sources.length) % sources.length;
        img.src = sources[currentIndex];
        updateCounter();
        resetView();
    }

    function openLightbox(index) {
        showImage(typeof index === 'number' ? index : 0);
        lightbox.hidden = false;
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('media-overlay-open');
    }

    function closeLightbox() {
        lightbox.classList.remove('is-open');
        lightbox.hidden = true;
        lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('media-overlay-open');
        resetView();
    }

    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function (e) {
            // In edit mode, let the edit panel handler take the click.
            if (document.body.classList.contains('smks3-is-editor')
                && !document.body.classList.contains('smks3-edit-preview')) {
                return;
            }
            e.preventDefault();
            e.stopPropagation();
            var idx = parseInt(thumb.getAttribute('data-index') || '0', 10);
            openLightbox(isNaN(idx) ? 0 : idx);
        });
    });

    closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeLightbox();
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            showImage(currentIndex - 1);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            showImage(currentIndex + 1);
        });
    }

    // Click dark area (not the image) to close
    stage.addEventListener('click', function (e) {
        if (e.target === stage) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (!lightbox.classList.contains('is-open')) return;
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft' && sources.length > 1) {
            showImage(currentIndex - 1);
        } else if (e.key === 'ArrowRight' && sources.length > 1) {
            showImage(currentIndex + 1);
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
