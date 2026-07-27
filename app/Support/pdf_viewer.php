<?php

declare(strict_types=1);

/**
 * In-page PDF display via PDF.js (no browser PDF chrome).
 *
 * @param array{id?:string,label?:string,btn_class?:string,show_download?:bool,fit?:string} $opts
 *        fit: 'width' (full container width stacked) | 'grid' (wrapped cards) | 'paged' (one page + pager)
 */
function smks3_pdf_viewer(string $url, array $opts = []): void
{
    $url = trim($url);
    if ($url === '') {
        return;
    }
    $id = (string) ($opts['id'] ?? ('smks3-pdf-' . substr(hash('sha256', $url), 0, 10)));
    $label = (string) ($opts['label'] ?? 'Buka / Muat Turun PDF');
    $btnClass = (string) ($opts['btn_class'] ?? 'btn btn-outline-primary btn-sm');
    $showDownload = array_key_exists('show_download', $opts) ? (bool) $opts['show_download'] : true;
    $fit = (string) ($opts['fit'] ?? 'grid');
    if (!in_array($fit, ['width', 'grid', 'paged'], true)) {
        $fit = 'grid';
    }
    $urlEsc = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    $idEsc = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
    $labelEsc = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    $btnClassEsc = htmlspecialchars($btnClass, ENT_QUOTES, 'UTF-8');
    $fitEsc = htmlspecialchars($fit, ENT_QUOTES, 'UTF-8');
    ?>
<div class="smks3-pdf-block smks3-pdf-block--<?= $fitEsc ?>">
    <?php if ($showDownload): ?>
    <div class="smks3-pdf-actions mb-3">
        <a href="<?= $urlEsc ?>"
           class="<?= $btnClassEsc ?>"
           target="_blank"
           rel="noopener noreferrer">
            <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i><?= $labelEsc ?>
        </a>
    </div>
    <?php endif; ?>
    <div id="<?= $idEsc ?>"
         class="pdf-grid smks3-pdf-pages"
         data-pdf-src="<?= $urlEsc ?>"
         data-pdf-fit="<?= $fitEsc ?>"
         role="img"
         aria-label="Pratonton PDF"></div>
    <?php if ($fit === 'paged'): ?>
    <div class="smks3-pdf-pager mt-3" data-pdf-pager-for="<?= $idEsc ?>" hidden>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-pdf-prev aria-label="Halaman sebelumnya">
            <i class="bi bi-chevron-left" aria-hidden="true"></i> Sebelum
        </button>
        <span class="smks3-pdf-pager__label px-2">
            Halaman <span data-pdf-page>1</span> / <span data-pdf-total>1</span>
        </span>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-pdf-next aria-label="Halaman seterusnya">
            Seterusnya <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
    <?php endif; ?>
</div>
    <?php
    smks3_pdf_viewer_assets();
}

/** Shared PDF.js CSS/JS once per response. */
function smks3_pdf_viewer_assets(): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    ?>
<style>
.smks3-pdf-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}
.smks3-pdf-block .smks3-pdf-actions {
    text-align: center;
    width: 100%;
}
.smks3-pdf-pager {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    max-width: 860px;
}
.smks3-pdf-pager__label {
    font-size: 0.9rem;
    color: #475569;
    text-align: center;
    flex: 1;
}
.smks3-pdf-pager[hidden] {
    display: none !important;
}
.pdf-grid.smks3-pdf-pages {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    gap: 1.25rem;
    width: 100%;
}
.smks3-pdf-block--width .smks3-pdf-pages {
    flex-direction: column;
    flex-wrap: nowrap;
    align-items: stretch;
}
.smks3-pdf-block--width .smks3-pdf-pages canvas {
    width: 100% !important;
    max-width: 100%;
    height: auto !important;
}
.smks3-pdf-block--grid .smks3-pdf-pages canvas {
    width: min(100%, 520px);
    max-width: 100%;
    height: auto;
}
.smks3-pdf-block--grid .smks3-pdf-pages:has(> canvas:only-child) canvas {
    width: min(100%, 860px);
}
.smks3-pdf-block--paged .smks3-pdf-pages {
    flex-wrap: nowrap;
    justify-content: center;
}
.smks3-pdf-block--paged .smks3-pdf-pages canvas {
    width: min(100%, 860px);
    max-width: 100%;
    height: auto;
}
.pdf-grid.smks3-pdf-pages canvas {
    border-radius: 10px;
    border: 1px solid #dde5ee;
    box-shadow: 0 4px 14px rgba(11, 60, 93, 0.08);
    background: #fff;
    display: block;
}
.smks3-pdf-block .smks3-pdf-error {
    border: 1px dashed #cbd5e1;
    border-radius: 10px;
    padding: 1.25rem;
    color: #64748b;
    text-align: center;
    background: #f8fafc;
    width: 100%;
}
@media (max-width: 768px) {
    .smks3-pdf-block--grid .smks3-pdf-pages canvas,
    .smks3-pdf-block--grid .smks3-pdf-pages:has(> canvas:only-child) canvas,
    .smks3-pdf-block--paged .smks3-pdf-pages canvas {
        width: 100%;
    }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
(function () {
    if (window.smks3PdfJsReady) return;
    window.smks3PdfJsReady = true;

    function findPager(container) {
        var id = container.id || '';
        if (!id) return null;
        return document.querySelector('.smks3-pdf-pager[data-pdf-pager-for="' + id + '"]');
    }

    async function renderPage(pdf, container, pageNum, fit) {
        var page = await pdf.getPage(pageNum);
        var maxW = Math.max(280, container.clientWidth || container.parentElement.clientWidth || 800);
        var base = page.getViewport({ scale: 1 });
        var scale = (fit === 'width' || fit === 'paged')
            ? Math.min(2.5, maxW / base.width)
            : 1.25;
        var viewport = page.getViewport({ scale: scale });
        var canvas = document.createElement('canvas');
        var context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        canvas.setAttribute('aria-label', 'Halaman ' + pageNum);
        container.innerHTML = '';
        container.appendChild(canvas);
        await page.render({ canvasContext: context, viewport: viewport }).promise;
    }

    function bindPager(pager, pdf, container, fit) {
        var pageEl = pager.querySelector('[data-pdf-page]');
        var totalEl = pager.querySelector('[data-pdf-total]');
        var prevBtn = pager.querySelector('[data-pdf-prev]');
        var nextBtn = pager.querySelector('[data-pdf-next]');
        var current = 1;
        var total = pdf.numPages || 1;

        function updateUi() {
            if (pageEl) pageEl.textContent = String(current);
            if (totalEl) totalEl.textContent = String(total);
            if (prevBtn) prevBtn.disabled = current <= 1;
            if (nextBtn) nextBtn.disabled = current >= total;
            pager.hidden = total <= 1;
        }

        async function go(pageNum) {
            if (pageNum < 1 || pageNum > total) return;
            current = pageNum;
            updateUi();
            await renderPage(pdf, container, current, fit);
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () { go(current - 1); });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function () { go(current + 1); });
        }
        updateUi();
        return go(1);
    }

    function renderContainer(container) {
        if (!window.pdfjsLib || container.getAttribute('data-pdf-loaded') === '1') return;
        var url = container.getAttribute('data-pdf-src') || '';
        if (!url) return;
        container.setAttribute('data-pdf-loaded', '1');
        var fit = container.getAttribute('data-pdf-fit') || 'width';
        pdfjsLib.getDocument(url).promise.then(async function (pdf) {
            if (fit === 'paged') {
                var pager = findPager(container);
                if (pager) {
                    await bindPager(pager, pdf, container, fit);
                    return;
                }
            }
            var maxW = Math.max(280, container.clientWidth || container.parentElement.clientWidth || 800);
            for (var pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                var page = await pdf.getPage(pageNum);
                var base = page.getViewport({ scale: 1 });
                var scale = fit === 'width'
                    ? Math.min(2.5, maxW / base.width)
                    : 1.25;
                var viewport = page.getViewport({ scale: scale });
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.setAttribute('aria-label', 'Halaman ' + pageNum);
                container.appendChild(canvas);
                await page.render({ canvasContext: context, viewport: viewport }).promise;
            }
        }).catch(function () {
            container.innerHTML = '<div class="smks3-pdf-error">Tidak dapat memaparkan PDF. Sila gunakan butang buka / muat turun.</div>';
        });
    }
    function renderAll() {
        if (!window.pdfjsLib) return;
        if (pdfjsLib.GlobalWorkerOptions) {
            pdfjsLib.GlobalWorkerOptions.workerSrc =
                'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
        }
        document.querySelectorAll('.smks3-pdf-pages[data-pdf-src]').forEach(renderContainer);
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', renderAll);
    } else {
        setTimeout(renderAll, 0);
    }
})();
</script>
    <?php
}
