<?php
/**
 * Portal inline edit mode (admin / superadmin).
 * Included from footer when smks3_is_editor() is true.
 */
if (!smks3_is_editor()) {
    return;
}

$smks3_api = 'api/save-content.php';
?>
<style>
body.smks3-is-editor.smks3-panel-open {
    overflow: hidden;
}
/* Overlay / preview / carousel critical styles live in header.php to avoid refresh flash */
.smks3-icon-picker {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 0.4rem;
    max-height: 320px;
    overflow-y: auto;
    padding: 0.4rem;
    border: 1px solid #dce8f2;
    border-radius: 10px;
    background: #f8fafc;
}
.smks3-icon-picker__search {
    margin-bottom: 0.5rem;
}
.smks3-icon-picker__meta {
    font-size: 0.75rem;
    color: #64748b;
    margin: 0.35rem 0 0;
}
.smks3-icon-picker__btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.15rem;
    min-height: 3.6rem;
    padding: 0.35rem 0.2rem;
    border: 1px solid transparent;
    border-radius: 8px;
    background: #fff;
    color: #0B3C5D;
    cursor: pointer;
    font-size: 0.62rem;
    line-height: 1.15;
    text-align: center;
}
.smks3-icon-picker__btn i {
    font-size: 1.25rem;
}
.smks3-icon-picker__btn span {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    max-width: 100%;
}
.smks3-icon-picker__btn:hover {
    border-color: #1a6fa8;
    background: #eef6fc;
}
.smks3-icon-picker__btn.is-selected {
    border-color: #0B3C5D;
    background: #e8f2f9;
    box-shadow: inset 0 0 0 1px #0B3C5D;
    font-weight: 600;
}
.smks3-icon-picker__btn.is-hidden {
    display: none;
}
.smks3-edit-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.35);
    z-index: 1085;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.25s ease, visibility 0.25s ease;
}
.smks3-edit-backdrop.is-open {
    opacity: 1;
    visibility: visible;
}
.smks3-edit-panel {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: min(380px, 92vw);
    z-index: 1090;
    background: #fff;
    box-shadow: 8px 0 32px rgba(0, 0, 0, 0.18);
    transform: translateX(-105%);
    transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1), width 0.2s ease;
    display: flex;
    flex-direction: column;
}
.smks3-edit-panel.is-wide {
    width: min(520px, 94vw);
}
.smks3-edit-panel.is-wide.is-news-editor {
    width: min(680px, 96vw);
}
.smks3-quill-wrap .ql-toolbar.ql-snow {
    border-color: #dbe3ee;
    border-radius: 8px 8px 0 0;
    background: #f8fafc;
}
.smks3-quill-wrap .ql-container.ql-snow {
    border-color: #dbe3ee;
    border-radius: 0 0 8px 8px;
    font-family: inherit;
    font-size: 0.95rem;
    min-height: 200px;
}
.smks3-quill-wrap .ql-editor {
    min-height: 200px;
    line-height: 1.6;
}
.smks3-quill-wrap .ql-editor.ql-blank::before {
    font-style: normal;
    color: #94a3b8;
}
.smks3-edit-panel.is-open {
    transform: translateX(0);
}
.smks3-edit-panel__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 1.1rem 1.15rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}
.smks3-edit-panel__head h2 {
    font-size: 1.05rem;
    font-weight: 700;
    color: #0B3C5D;
    margin: 0;
}
.smks3-edit-panel__head p {
    margin: 0.25rem 0 0;
    font-size: 0.8rem;
    color: #64748b;
}
.smks3-edit-panel__close {
    border: none;
    background: #e2e8f0;
    width: 2rem;
    height: 2rem;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #334155;
}
.smks3-edit-panel__body {
    flex: 1;
    overflow-y: auto;
    padding: 1.15rem;
}
.smks3-edit-panel__foot {
    padding: 1rem 1.15rem;
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.5rem;
}
.smks3-edit-panel .form-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #334155;
}
.smks3-lyrics-editor {
    width: 100%;
    min-height: 22rem;
    padding: 1rem 1.1rem;
    border: 1px solid #dce8f2;
    border-radius: 12px;
    background: #f8fafc;
    color: #0f172a;
    font-size: 1rem;
    line-height: 1.85;
    text-align: center;
    resize: vertical;
    white-space: pre-wrap;
    font-family: Georgia, "Times New Roman", serif;
}
.smks3-lyrics-editor:focus {
    outline: none;
    border-color: #1a6fa8;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(26, 111, 168, 0.15);
}
.smks3-credit-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.65rem;
}
@media (max-width: 480px) {
    .smks3-credit-grid {
        grid-template-columns: 1fr;
    }
}
.smks3-links-editor {
    border: 1px solid #dce8f2;
    border-radius: 10px;
    background: #f8fafc;
    padding: 0.65rem;
}
.smks3-links-editor__list {
    display: flex;
    flex-direction: column;
    gap: 0.55rem;
    max-height: 280px;
    overflow-y: auto;
    margin-bottom: 0.55rem;
}
.smks3-links-editor__row {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.35rem;
    padding: 0.55rem 2.2rem 0.55rem 0.55rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    position: relative;
}
.smks3-links-editor__row input {
    font-size: 0.85rem;
}
.smks3-links-editor__row.is-locked {
    padding-right: 0.55rem;
    background: #f1f5f9;
    border-style: dashed;
}
.smks3-links-editor__row.is-locked input[readonly] {
    background: #e2e8f0;
    color: #475569;
    cursor: not-allowed;
}
.smks3-links-editor__lock {
    font-size: 0.72rem;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 0.15rem;
}
.smks3-field-locked {
    margin-bottom: 1rem;
}
.smks3-field-locked .form-control[readonly] {
    background: #f1f5f9;
    color: #475569;
    cursor: not-allowed;
}
.smks3-links-editor__remove {
    position: absolute;
    top: 0.35rem;
    right: 0.35rem;
    border: none;
    background: #fee2e2;
    color: #b91c1c;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.75rem;
    line-height: 1;
}
.smks3-links-editor__remove:hover {
    background: #fecaca;
}
.smks3-links-editor__empty {
    font-size: 0.8rem;
    color: #64748b;
    text-align: center;
    padding: 0.5rem;
    margin: 0;
}
.smks3-edit-status {
    font-size: 0.82rem;
    min-height: 1.25rem;
    margin-top: 0.75rem;
}
.smks3-edit-status.is-error { color: #b91c1c; }
.smks3-edit-status.is-ok { color: #15803d; }
.smks3-current-file {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    flex-wrap: wrap;
    margin-bottom: 0.45rem;
    padding: 0.45rem 0.65rem;
    border: 1px solid #dbe3ee;
    border-radius: 8px;
    background: #f8fafc;
    font-size: 0.82rem;
    color: #0f172a;
}
.smks3-current-file > .bi {
    color: #0B3C5D;
    font-size: 1rem;
    flex: 0 0 auto;
}
.smks3-current-file__name {
    display: flex;
    align-items: baseline;
    min-width: 0;
    max-width: 100%;
    overflow: hidden;
    font-weight: 600;
}
.smks3-current-file__stem {
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.smks3-current-file__ext {
    flex: 0 0 auto;
    white-space: nowrap;
    font-weight: 700;
    color: #0B3C5D;
}
.smks3-current-file__label {
    margin-left: auto;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #64748b;
}
.smks3-news-images {
    display: grid;
    gap: 0.55rem;
    margin-bottom: 0.55rem;
}
.smks3-news-images__item {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    padding: 0.45rem 0.55rem;
    border: 1px solid #dbe3ee;
    border-radius: 8px;
    background: #f8fafc;
}
.smks3-news-images__thumb {
    width: 44px;
    height: 44px;
    object-fit: cover;
    border-radius: 6px;
    background: #e2e8f0;
    flex: 0 0 auto;
}
.smks3-news-images__meta {
    min-width: 0;
    flex: 1 1 auto;
}
.smks3-news-images__name {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #0f172a;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.smks3-news-images__remove {
    font-size: 0.78rem;
    color: #64748b;
    margin: 0;
}
</style>

<script>
(function () {
    var bar = document.getElementById('smks3EditBar');
    if (!bar) return;

    // Auto log keluar selepas AFK (selaras dengan had sesi pelayan)
    var IDLE_MS = <?= (int) smks3_session_idle_ttl() ?> * 1000;
    var LOGOUT_URL = <?= json_encode(
        (smks3_editor_role() === 'superadmin' ? 'superadmin/logout.php' : 'admin/logout.php') . '?idle=1',
        JSON_UNESCAPED_SLASHES
    ) ?>;
    var idleTimer = null;
    function goIdleLogout() {
        window.location.href = LOGOUT_URL;
    }
    function bumpIdleTimer() {
        if (idleTimer) clearTimeout(idleTimer);
        idleTimer = setTimeout(goIdleLogout, IDLE_MS);
    }
    ['mousemove', 'mousedown', 'keydown', 'scroll', 'touchstart', 'click', 'wheel'].forEach(function (evt) {
        document.addEventListener(evt, bumpIdleTimer, { passive: true, capture: true });
    });
    bumpIdleTimer();

    function syncEditBarHeight() {
        var h = Math.ceil(bar.getBoundingClientRect().height) || 44;
        document.documentElement.style.setProperty('--smks3-edit-bar-height', h + 'px');
        var nav = document.getElementById('site-navbar');
        if (nav) {
            document.documentElement.style.setProperty('--site-navbar-height', nav.offsetHeight + 'px');
        }
    }
    syncEditBarHeight();
    window.addEventListener('resize', syncEditBarHeight);
    if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(syncEditBarHeight).catch(function () {});
    }

    var toggle = document.getElementById('smks3EditModeToggle');
    var prefApi = 'api/edit-mode-pref.php';

    function setEditingOn(editingOn) {
        document.body.classList.toggle('smks3-edit-preview', !editingOn);
        if (toggle) toggle.checked = editingOn;
        syncEditBarHeight();
        if (typeof window.smks3SyncEditCarousels === 'function') {
            window.smks3SyncEditCarousels(editingOn);
        }
        fetch(prefApi, {
            method: 'POST',
            credentials: 'same-origin',
            headers: window.smks3WithCsrf({ 'Content-Type': 'application/json', 'Accept': 'application/json' }),
            body: JSON.stringify({ preview: !editingOn, csrf_token: window.smks3Csrf })
        }).catch(function () {});
    }

    // Enable switch animation only after first paint (avoids slide on navigation).
    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            document.body.classList.add('smks3-edit-ready');
        });
    });

    if (toggle) {
        toggle.addEventListener('change', function () {
            var editingOn = !!toggle.checked;
            if (!editingOn) {
                var panel = document.getElementById('smks3EditPanel');
                var backdrop = document.getElementById('smks3EditBackdrop');
                if (panel && panel.classList.contains('is-open')) {
                    panel.classList.remove('is-open');
                    panel.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('smks3-panel-open');
                    if (backdrop) backdrop.hidden = true;
                }
            }
            setEditingOn(editingOn);
        });
    }
})();
</script>

<div class="smks3-edit-backdrop" id="smks3EditBackdrop" hidden></div>
<aside class="smks3-edit-panel" id="smks3EditPanel" aria-hidden="true">
    <div class="smks3-edit-panel__head">
        <div>
            <h2 id="smks3EditTitle">Sunting kandungan</h2>
            <p id="smks3EditHint">Ubah teks kemudian simpan.</p>
        </div>
        <button type="button" class="smks3-edit-panel__close" id="smks3EditClose" aria-label="Tutup panel">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="smks3-edit-panel__body">
        <form id="smks3EditForm"></form>
        <div class="smks3-edit-status" id="smks3EditStatus" aria-live="polite"></div>
    </div>
    <div class="smks3-edit-panel__foot">
        <button type="button" class="btn btn-primary flex-grow-1" id="smks3EditSave">Simpan</button>
        <button type="button" class="btn btn-outline-danger d-none" id="smks3EditDelete">Padam</button>
        <button type="button" class="btn btn-outline-secondary" id="smks3EditCancel">Batal</button>
    </div>
</aside>

<script>
(function () {
    var API = <?= json_encode($smks3_api) ?>;
    var ICON_OPTIONS = <?= file_exists(__DIR__ . '/../../Support/bootstrap-icons.json')
        ? (file_get_contents(__DIR__ . '/../../Support/bootstrap-icons.json') ?: '[]')
        : '[]' ?>;
    if (!Array.isArray(ICON_OPTIONS) || !ICON_OPTIONS.length) {
        ICON_OPTIONS = [
            { value: 'bi-info-circle', label: 'Info Circle' },
            { value: 'bi-people', label: 'People' },
            { value: 'bi-book', label: 'Book' },
            { value: 'bi-building', label: 'Building' },
            { value: 'bi-link-45deg', label: 'Link' }
        ];
    }
    var FAVORITE_ICONS = [
        'bi-person-badge', 'bi-people', 'bi-people-fill', 'bi-mortarboard', 'bi-book', 'bi-book-half',
        'bi-building', 'bi-geo-alt', 'bi-map', 'bi-award', 'bi-trophy', 'bi-trophy-fill',
        'bi-bank', 'bi-calendar-event', 'bi-newspaper', 'bi-telephone', 'bi-envelope', 'bi-globe2',
        'bi-house-door', 'bi-clock', 'bi-hash', 'bi-arrows-fullscreen', 'bi-diagram-3', 'bi-info-circle',
        'bi-lightbulb', 'bi-star', 'bi-heart', 'bi-shield-check', 'bi-clipboard-check', 'bi-journal-text',
        'bi-camera-video', 'bi-image', 'bi-music-note-beamed', 'bi-flag', 'bi-link-45deg', 'bi-pencil-square'
    ];
    var PAGE_HREF_OPTIONS = <?= json_encode(smks3_site_page_options(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
    if (!Array.isArray(PAGE_HREF_OPTIONS)) PAGE_HREF_OPTIONS = [];
    var panel = document.getElementById('smks3EditPanel');
    var backdrop = document.getElementById('smks3EditBackdrop');
    var form = document.getElementById('smks3EditForm');
    var titleEl = document.getElementById('smks3EditTitle');
    var hintEl = document.getElementById('smks3EditHint');
    var statusEl = document.getElementById('smks3EditStatus');
    var deleteBtn = document.getElementById('smks3EditDelete');
    var activeEl = null;
    var currentBlock = null;
    var deleteBlock = null;
    var quillInstance = null;
    var quillLoading = null;

    // Pause carousels while editing; resume autoplay in preview (visitor-like).
    window.smks3SyncEditCarousels = function (editingOn) {
        document.querySelectorAll('.carousel').forEach(function (el) {
            if (!window.bootstrap || !bootstrap.Carousel) return;
            try {
                var existing = bootstrap.Carousel.getInstance(el);
                if (existing) existing.dispose();
            } catch (err) {}

            var slideCount = el.querySelectorAll('.carousel-item').length;
            if (editingOn || slideCount < 2) {
                el.removeAttribute('data-bs-ride');
                el.setAttribute('data-bs-interval', 'false');
                try {
                    var paused = bootstrap.Carousel.getOrCreateInstance(el, {
                        interval: false,
                        ride: false,
                        wrap: false
                    });
                    paused.pause();
                } catch (err) {}
                return;
            }

            el.setAttribute('data-bs-ride', 'carousel');
            el.setAttribute('data-bs-interval', '5500');
            try {
                var live = bootstrap.Carousel.getOrCreateInstance(el, {
                    interval: 5500,
                    ride: 'carousel',
                    wrap: true
                });
                live.cycle();
            } catch (err) {}
        });
    };
    window.smks3SyncEditCarousels(!document.body.classList.contains('smks3-edit-preview'));

    function setStatus(msg, ok) {
        statusEl.textContent = msg || '';
        statusEl.className = 'smks3-edit-status' + (msg ? (ok ? ' is-ok' : ' is-error') : '');
    }

    function closePanel() {
        destroyRichTextEditor();
        panel.classList.remove('is-open');
        panel.classList.remove('is-wide');
        panel.classList.remove('is-news-editor');
        backdrop.classList.remove('is-open');
        backdrop.hidden = true;
        panel.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('smks3-panel-open');
        if (activeEl) activeEl.classList.remove('is-editing');
        activeEl = null;
        currentBlock = null;
        deleteBlock = null;
        form.innerHTML = '';
        deleteBtn.classList.add('d-none');
        setStatus('');
    }

    function openPanel(el) {
        if (activeEl) activeEl.classList.remove('is-editing');
        activeEl = el;
        el.classList.add('is-editing');
        currentBlock = el.getAttribute('data-edit-block');
        titleEl.textContent = el.getAttribute('data-edit-label') || 'Sunting kandungan';
        hintEl.textContent = el.getAttribute('data-edit-hint') || 'Ubah teks kemudian simpan.';
        destroyRichTextEditor();
        form.innerHTML = buildFields(el);
        bindIconPicker(form);
        bindLinksEditor(form);
        bindSocialEditor(form);
        bindKurikulumCardKind(form);
        bindPageHrefField(form);
        bindRichTextEditor(form);
        var isNewsEditor = currentBlock === 'news_item' || currentBlock === 'news_add';
        panel.classList.toggle('is-wide', isNewsEditor || currentBlock === 'lencana_lagu' || currentBlock === 'cuti_kumpulan' || currentBlock === 'kurikulum_card' || currentBlock === 'kurikulum_card_add' || currentBlock === 'kurikulum_meta' || currentBlock === 'footer_social');
        panel.classList.toggle('is-news-editor', isNewsEditor);
        deleteBlock = null;
        var deletable = {
            quick_link: 'quick_link_delete',
            slideshow_slide: 'slideshow_delete',
            news_item: 'news_delete',
            pengetua_item: 'pengetua_delete',
            pengurusan_item: 'pengurusan_delete',
            sejarah_item: 'sejarah_delete',
            fpk_item: 'fpk_delete',
            guru_item: 'guru_delete',
            akp_item: 'akp_delete',
            kalendar_pdf: 'kalendar_pdf_delete',
            cuti_pdf: 'cuti_pdf_delete',
            pilihan_pdf: 'pilihan_pdf_delete',
            enrolmen_item: 'enrolmen_delete',
            bil_kelas_item: 'bil_kelas_delete',
            peraturan_item: 'peraturan_delete',
            pemimpin_item: 'pemimpin_delete',
            lencana_item: 'lencana_item_delete',
            profil_item: 'profil_item_delete',
            kurikulum_card: 'kurikulum_card_delete'
        };
        var saveBtn = document.getElementById('smks3EditSave');
        var deleteOnly = {
            kalendar_pdf: 1, cuti_pdf: 1, pilihan_pdf: 1,
            peraturan_item: 1, pemimpin_item: 1
        };
        if (deletable[currentBlock]) {
            deleteBlock = deletable[currentBlock];
            deleteBtn.classList.remove('d-none');
        } else {
            deleteBtn.classList.add('d-none');
        }
        if (deleteOnly[currentBlock]) {
            saveBtn.classList.add('d-none');
        } else {
            saveBtn.classList.remove('d-none');
        }
        setStatus('');
        backdrop.hidden = false;
        requestAnimationFrame(function () {
            panel.classList.add('is-open');
            backdrop.classList.add('is-open');
            document.body.classList.add('smks3-panel-open');
            panel.setAttribute('aria-hidden', 'false');
        });
    }

    function attr(el, name) { return el.getAttribute(name) || ''; }

    function buildFields(el) {
        var block = el.getAttribute('data-edit-block');
        if (block === 'school_info') {
            return field('school_name', 'Nama sekolah', attr(el, 'data-school-name'))
                + field('address', 'Alamat', attr(el, 'data-address'), true)
                + field('phone', 'Telefon', attr(el, 'data-phone'))
                + field('email', 'Emel', attr(el, 'data-email'));
        }
        if (block === 'footer_about') {
            return field('brand', 'Nama sekolah (footer)', attr(el, 'data-brand'))
                + field('blurb', 'Ayat pengenalan', attr(el, 'data-blurb'), true, false);
        }
        if (block === 'footer_contact') {
            return field('title', 'Tajuk bahagian', attr(el, 'data-title') || 'Hubungi', false, false)
                + field('address', 'Alamat', attr(el, 'data-address'), true)
                + field('phone', 'No. telefon', attr(el, 'data-phone'))
                + field('email', 'Emel', attr(el, 'data-email'));
        }
        if (block === 'footer_social') {
            var socialArr = [];
            try { socialArr = JSON.parse(attr(el, 'data-social-json') || '[]') || []; } catch (err) { socialArr = []; }
            if (!Array.isArray(socialArr)) socialArr = [];
            return field('title', 'Tajuk bahagian', attr(el, 'data-title') || 'Ikuti Kami', false, false)
                + socialEditor(socialArr);
        }
        if (block === 'footer_copyright') {
            return field('value', 'Teks selepas tahun', attr(el, 'data-value'))
                + '<p class="small text-muted mb-0">Contoh: SMK Seremban 3. Hak Cipta Terpelihara.</p>';
        }
        if (block === 'news_item') {
            return hidden('id', attr(el, 'data-news-id') || attr(el, 'data-id'))
                + field('title', 'Tajuk', attr(el, 'data-title'))
                + field('year', 'Tahun', attr(el, 'data-year') || new Date().getFullYear(), false, false)
                + richTextField('content', 'Kandungan (pilihan)', attr(el, 'data-content'), false)
                + newsImagesField(el)
                + fileField('pdf_file', 'PDF (pilihan)', false, 'application/pdf,.pdf', findCurrentPdfPath(el));
        }
        if (block === 'news_add') {
            return field('title', 'Tajuk', '')
                + field('year', 'Tahun', String(new Date().getFullYear()), false, false)
                + richTextField('content', 'Kandungan (pilihan)', '', false)
                + newsImagesField(null)
                + fileField('pdf_file', 'PDF (pilihan)', false, 'application/pdf,.pdf');
        }
        if (block === 'quick_link') {
            return hidden('index', attr(el, 'data-index'))
                + field('title', 'Tajuk', attr(el, 'data-title'))
                + field('subtitle', 'Subtajuk', attr(el, 'data-subtitle'), false, false)
                + pageHrefField(attr(el, 'data-href'))
                + iconPicker('icon', 'Pilih ikon', attr(el, 'data-icon') || 'bi-link-45deg')
                + check('external', 'Pautan luaran (buka tab baharu)', attr(el, 'data-external') === '1');
        }
        if (block === 'quick_link_add') {
            return field('title', 'Tajuk', 'Pautan Baharu')
                + field('subtitle', 'Subtajuk', '', false, false)
                + pageHrefField('./')
                + iconPicker('icon', 'Pilih ikon', 'bi-link-45deg')
                + check('external', 'Pautan luaran', false);
        }
        if (block === 'slideshow_slide') {
            return hidden('index', attr(el, 'data-index'))
                + field('alt', 'Teks alt', attr(el, 'data-alt'))
                + field('href', 'Pautan (pilihan)', attr(el, 'data-href'), false, false)
                + check('external', 'Pautan luaran', attr(el, 'data-external') === '1')
                + fileField('image', 'Ganti gambar (pilihan)');
        }
        if (block === 'slideshow_add') {
            return field('alt', 'Teks alt', 'Slaid baharu')
                + field('href', 'Pautan (pilihan)', '', false, false)
                + check('external', 'Pautan luaran', false)
                + fileField('image', 'Gambar slaid', true)
                + '<p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Slaid baharu akan ditambah. Slaid sedia ada kekal.</p>';
        }
        if (block === 'profil_item' || block === 'profil_item_add') {
            var isAddProfil = block === 'profil_item_add';
            return (isAddProfil ? '' : hidden('id', attr(el, 'data-id')))
                + field('title', 'Tajuk', isAddProfil ? '' : attr(el, 'data-title'))
                + field('value', 'Nilai / maklumat', isAddProfil ? '' : attr(el, 'data-value'), true, false)
                + iconPicker('icon', 'Pilih ikon', isAddProfil ? 'bi-info-circle' : (attr(el, 'data-icon') || 'bi-info-circle'));
        }
        if (block === 'fpk_falsafah') {
            return field('title', 'Tajuk', attr(el, 'data-title'))
                + field('content', 'Teks falsafah', attr(el, 'data-content'), true)
                + '<p class="small text-muted mb-0">Guna baris kosong antara perenggan.</p>';
        }
        if (block === 'fpk_item') {
            return hidden('id', attr(el, 'data-id'))
                + field('kategori', 'Kategori', attr(el, 'data-kategori'))
                + field('content', 'Kandungan', attr(el, 'data-content'), true);
        }
        if (block === 'fpk_add') {
            return field('kategori', 'Kategori', 'Visi')
                + field('content', 'Kandungan', '', true);
        }
        if (block === 'sejarah_item' || block === 'sejarah_add') {
            var isAddS = block === 'sejarah_add';
            return (isAddS ? '' : hidden('id', attr(el, 'data-id')))
                + field('tajuk', 'Tajuk', isAddS ? '' : attr(el, 'data-tajuk'))
                + field('tarikh', 'Tarikh (pilihan)', isAddS ? '' : attr(el, 'data-tarikh'), false, false)
                + field('content', 'Kandungan', isAddS ? '' : attr(el, 'data-content'), true);
        }
        if (block === 'pengetua_item' || block === 'pengetua_add') {
            var isAddP = block === 'pengetua_add';
            return (isAddP ? '' : hidden('id', attr(el, 'data-id')))
                + field('name', 'Nama', isAddP ? '' : attr(el, 'data-name'))
                + field('start_year', 'Tahun mula', isAddP ? '' : attr(el, 'data-start-year'), false, false)
                + field('end_year', 'Tahun akhir (kosong = Kini)', isAddP ? '' : attr(el, 'data-end-year'), false, false)
                + fileField('photo', 'Gambar (pilihan)', false, 'image/*', undefined, !isAddP);
        }
        if (block === 'pengurusan_item' || block === 'pengurusan_add') {
            var isAddPg = block === 'pengurusan_add';
            return (isAddPg ? '' : hidden('id', attr(el, 'data-id')))
                + field('nama', 'Nama', isAddPg ? '' : attr(el, 'data-nama'))
                + field('gred', 'Gred', isAddPg ? '' : attr(el, 'data-gred'), false, false)
                + field('jawatan', 'Jawatan', isAddPg ? '' : attr(el, 'data-jawatan'))
                + selectField('kategori', 'Kategori', isAddPg ? 'pk' : (attr(el, 'data-kategori') || 'pk'), [
                    { value: 'pengetua', label: 'Pengetua' },
                    { value: 'pk', label: 'Penolong Kanan (PK)' },
                    { value: 'gkmp', label: 'GKMP' },
                    { value: 'kaunselor', label: 'Kaunselor' }
                ])
                + fileField('gambar', 'Gambar (pilihan)', false, 'image/*', undefined, !isAddPg);
        }
        if (block === 'guru_item' || block === 'guru_add' || block === 'akp_item' || block === 'akp_add') {
            var isAddG = block.indexOf('_add') !== -1;
            return (isAddG ? '' : hidden('id', attr(el, 'data-id')))
                + field('nama', 'Nama', isAddG ? '' : attr(el, 'data-nama'))
                + field('jawatan', 'Jawatan', isAddG ? '' : attr(el, 'data-jawatan'), false, false)
                + field('dg', 'DG', isAddG ? '' : attr(el, 'data-dg'), false, false)
                + fileField('image', 'Gambar (pilihan)', false, 'image/*', undefined, !isAddG);
        }
        if (block === 'kalendar_title') {
            return field('value', 'Tajuk halaman', attr(el, 'data-value'));
        }
        if (block === 'kalendar_cell' || block === 'table_cell') {
            return field('value', 'Nilai', attr(el, 'data-value'), false, false);
        }
        if (block === 'list_item') {
            return field('value', 'Nilai', attr(el, 'data-value'), true, false);
        }
        if (block === 'ubk_pengenalan') {
            return field('lead', 'Lead / ringkasan', attr(el, 'data-lead'), true, false)
                + field('title', 'Tajuk pengenalan', attr(el, 'data-title'))
                + field('body', 'Kandungan pengenalan', attr(el, 'data-body'), true);
        }
        if (block === 'ubk_visi' || block === 'ubk_misi' || block === 'ubk_falsafah' || block === 'ubk_aktiviti') {
            var ubkLabel = block === 'ubk_visi' ? 'Visi' : (block === 'ubk_misi' ? 'Misi' : (block === 'ubk_falsafah' ? 'Falsafah' : 'Nota aktiviti'));
            return field('value', ubkLabel, attr(el, 'data-value'), true);
        }
        if (block === 'ubk_objektif' || block === 'ubk_fungsi') {
            return field('value', block === 'ubk_objektif' ? 'Objektif (satu baris = satu item)' : 'Fungsi (satu baris = satu item)', attr(el, 'data-value'), true)
                + '<p class="small text-muted mb-0">Tekan Enter untuk item baharu.</p>';
        }
        if (block === 'ubk_carta_image' || block === 'ubk_pamplet1' || block === 'ubk_pamplet2') {
            var ubkPath = findCurrentImagePath(el);
            var ubkLabel = block === 'ubk_carta_image'
                ? (ubkPath ? 'Gambar carta baharu (ganti semasa)' : 'Gambar carta organisasi')
                : (ubkPath ? 'Gambar pamplet baharu (ganti semasa)' : 'Gambar pamplet');
            return fileField('image', ubkLabel, true, 'image/*', ubkPath)
                + (ubkPath ? '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Gambar semasa akan diganti.</p>' : '');
        }
        if (block === 'kurikulum_meta') {
            var sectionsRaw = attr(el, 'data-sections');
            var sectionsHtml = '';
            if (sectionsRaw) {
                try {
                    var sectionsObj = JSON.parse(sectionsRaw);
                    Object.keys(sectionsObj || {}).forEach(function (key) {
                        var sec = sectionsObj[key] || {};
                        sectionsHtml += field('section_' + key + '_title', 'Tajuk bahagian (' + key + ')', sec.title || '', false, false);
                        sectionsHtml += field('section_' + key + '_subtitle', 'Subtajuk bahagian (' + key + ')', sec.subtitle || '', true, false);
                    });
                } catch (err) {}
            }
            var showIntro = el.hasAttribute('data-intro');
            return hidden('page_key', attr(el, 'data-page-key'))
                + (showIntro ? field('intro', 'Pengenalan halaman', attr(el, 'data-intro'), true, false) : '')
                + sectionsHtml;
        }
        if (block === 'kurikulum_section') {
            var showSubtitle = el.hasAttribute('data-subtitle');
            return hidden('page_key', attr(el, 'data-page-key'))
                + hidden('section_key', attr(el, 'data-section-key') || 'main')
                + field('title', 'Tajuk bahagian', attr(el, 'data-title'), false, false)
                + (showSubtitle ? field('subtitle', 'Subtajuk bahagian', attr(el, 'data-subtitle'), true, false) : '');
        }
        if (block === 'kurikulum_card' || block === 'kurikulum_card_add') {
            var isAddK = block === 'kurikulum_card_add';
            var pageKeyK = attr(el, 'data-page-key');
            var sectionKeyK = attr(el, 'data-section-key') || 'main';
            var linksJson = isAddK ? '[]' : (attr(el, 'data-links-json') || '[]');
            var linksArr = [];
            try { linksArr = JSON.parse(linksJson) || []; } catch (err) { linksArr = []; }
            if (!Array.isArray(linksArr)) linksArr = [];

            var btnHref = isAddK ? '' : attr(el, 'data-href');
            var btnLabel = isAddK ? 'Lihat Maklumat' : (attr(el, 'data-btn-label') || 'Lihat Maklumat');
            var btnExternal = !isAddK && attr(el, 'data-external') === '1';
            var externalLinksArr = linksArr.filter(function (link) {
                return isExternalKurikulumUrl(link.href || '');
            });
            var hasAnyLinks = linksArr.length > 0;
            var hasExternalLinks = externalLinksArr.length > 0;
            var hasButton = !hasAnyLinks && String(btnHref || '').trim() !== '';
            var cardKind = hasAnyLinks ? 'folder' : (hasButton ? 'button' : 'info');
            if (isAddK) cardKind = 'info';

            var sectionCaps = kurikulumSectionCaps(pageKeyK, sectionKeyK);
            var allowExternalButton = sectionCaps.hasExternalButton;
            var allowExternalFolder = sectionCaps.hasExternalFolder;

            function buttonFieldsHtml(visible) {
                var body;
                if (btnHref && !isExternalKurikulumUrl(btnHref)) {
                    body = hidden('href', btnHref)
                        + hidden('external', btnExternal ? '1' : '0')
                        + field('btn_label', 'Teks butang', btnLabel, false, false);
                } else {
                    body = field('href', 'Pautan butang luar (https://…)', isExternalKurikulumUrl(btnHref) ? btnHref : '', false, false)
                        + field('btn_label', 'Teks butang', btnLabel, false, false)
                        + check('external', 'Buka pautan dalam tab baharu', btnExternal);
                }
                return '<div data-kurikulum-fields="button"' + (visible ? '' : ' hidden') + '>' + body + '</div>';
            }

            function folderFieldsHtml(visible, linksForEditor) {
                return '<div data-kurikulum-fields="folder"' + (visible ? '' : ' hidden') + '>'
                    + linksEditor(visible ? (linksForEditor || []) : [], false)
                    + '</div>';
            }

            function preserveHiddenLinks() {
                return linksArr.map(function (link) {
                    return '<input type="hidden" name="link_title[]" value="' + esc(link.title || '') + '">'
                        + '<input type="hidden" name="link_href[]" value="' + esc(link.href || '') + '">';
                }).join('');
            }

            function preserveHiddenButton() {
                return hidden('href', btnHref || '')
                    + hidden('external', btnExternal ? '1' : '0')
                    + hidden('btn_label', btnLabel || '');
            }

            var typePicker = '';
            var extra = '';
            if (isAddK) {
                var kindOptions = '<option value="info" selected>Maklumat sahaja (tajuk + ikon)</option>';
                if (allowExternalButton) {
                    kindOptions += '<option value="button">Butang pautan luar</option>';
                }
                if (allowExternalFolder) {
                    kindOptions += '<option value="folder">Senarai pautan (Lihat Bahagian)</option>';
                }
                if (allowExternalButton || allowExternalFolder) {
                    typePicker = '<div class="mb-3">'
                        + '<label class="form-label" for="smks3_f_card_kind">Jenis kad</label>'
                        + '<select class="form-select" id="smks3_f_card_kind" name="card_kind" data-kurikulum-card-kind>'
                        + kindOptions
                        + '</select>'
                        + '<p class="small text-muted mb-0 mt-1">Pilih jenis mengikut keperluan seksyen ini.</p>'
                        + '</div>';
                    extra = (allowExternalButton ? buttonFieldsHtml(false) : '')
                        + (allowExternalFolder ? folderFieldsHtml(false, []) : '');
                } else {
                    extra = '<p class="small text-muted mb-3">Kad maklumat sahaja — seksyen ini tiada pautan luar.</p>';
                }
            } else if (cardKind === 'folder') {
                if (hasExternalLinks) {
                    // Editable external links + keep internal portal links hidden.
                    extra = preserveHiddenButton() + folderFieldsHtml(true, linksArr);
                } else {
                    // Internal portal list only — no “pautan luar” field.
                    extra = preserveHiddenButton() + preserveHiddenLinks()
                        + '<p class="small text-muted mb-3">Senarai pautan portal dikunci. Tiada pautan luar pada kad ini.</p>';
                }
            } else if (cardKind === 'button') {
                extra = buttonFieldsHtml(true) + preserveHiddenLinks();
            } else {
                extra = preserveHiddenButton() + preserveHiddenLinks()
                    + '<p class="small text-muted mb-3">Kad maklumat sahaja — tiada butang atau senarai pautan.</p>';
            }

            return (isAddK ? '' : hidden('id', attr(el, 'data-id')))
                + hidden('page_key', pageKeyK)
                + hidden('section_key', sectionKeyK)
                + typePicker
                + field('title', 'Tajuk', isAddK ? '' : attr(el, 'data-title'))
                + field('description', 'Penerangan', isAddK ? '' : attr(el, 'data-description'), true, false)
                + iconPicker('icon', 'Pilih ikon', isAddK ? 'bi-folder2-open' : (attr(el, 'data-icon') || 'bi-folder2-open'))
                + extra;
        }
        if (block === 'cuti_kumpulan') {
            return '<div class="mb-3">'
                + '<label class="form-label" for="smks3_f_kumpulan_a">Kumpulan A' + requiredMark(true) + '</label>'
                + '<textarea class="form-control" id="smks3_f_kumpulan_a" name="kumpulan_a" rows="3" required placeholder="Contoh: Kedah, Kelantan, Terengganu">'
                + esc(attr(el, 'data-kumpulan-a'))
                + '</textarea>'
                + '</div>'
                + '<div class="mb-2">'
                + '<label class="form-label" for="smks3_f_kumpulan_b">Kumpulan B' + requiredMark(true) + '</label>'
                + '<textarea class="form-control" id="smks3_f_kumpulan_b" name="kumpulan_b" rows="5" required placeholder="Contoh: Johor, Melaka, Negeri Sembilan…">'
                + esc(attr(el, 'data-kumpulan-b'))
                + '</textarea>'
                + '<p class="small text-muted mb-0 mt-1">Pisahkan negeri dengan koma. Label “Kumpulan A/B” ditambah automatik.</p>'
                + '</div>';
        }
        if (block === 'html_text') {
            return field('value', 'Teks', attr(el, 'data-value'), true, false);
        }
        if (block === 'kalendar_pdf_add' || block === 'cuti_pdf_add' || block === 'pilihan_pdf_add') {
            // Pass '' so add forms never show another PDF on the page as "Fail semasa".
            var pilihanReplace = block === 'pilihan_pdf_add';
            return fileField(
                'pdf',
                pilihanReplace ? 'Fail PDF baharu (ganti semasa)' : 'Fail PDF baharu',
                true,
                'application/pdf,.pdf',
                ''
            ) + (pilihanReplace
                ? '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>PDF semasa akan diganti, bukan ditambah sebagai fail baharu.</p>'
                : '<p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>PDF baharu akan ditambah. PDF sedia ada kekal.</p>');
        }
        if (block === 'kalendar_pdf' || block === 'cuti_pdf' || block === 'pilihan_pdf') {
            return hidden('id', attr(el, 'data-id'))
                + currentFileBadge(findCurrentPdfPath(el), true)
                + '<p class="small text-muted mb-0">Guna butang Padam untuk buang PDF ini.</p>';
        }
        if (block === 'enrolmen_add') {
            var slides = [];
            try { slides = JSON.parse(attr(el, 'data-slides') || '[]') || []; } catch (err) { slides = []; }
            if (!Array.isArray(slides)) slides = [];
            var posOpts = [{ value: 'start', label: 'Di awal (slaid pertama)' }];
            slides.forEach(function (s) {
                if (!s || !s.id) return;
                var label = String(s.title || ('Slaid #' + s.id)).trim() || ('Slaid #' + s.id);
                posOpts.push({ value: 'after:' + s.id, label: 'Selepas “' + label + '”' });
            });
            posOpts.push({ value: 'end', label: 'Di akhir (slaid terakhir)' });
            return field('title', 'Tajuk', 'Enrolmen', false, false)
                + selectField('position', 'Kedudukan dalam slaid', 'end', posOpts)
                + fileField('image', 'Gambar', true)
                + '<p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Gambar baharu akan ditambah ke karusel. Gambar sedia ada kekal.</p>';
        }
        if (block === 'peraturan_add' || block === 'pemimpin_add') {
            return fileField('image', 'Gambar', true)
                + '<p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Gambar baharu akan ditambah. Gambar sedia ada kekal.</p>';
        }
        if (block === 'bil_kelas_add') {
            var tingkatanPreset = attr(el, 'data-tingkatan') || '';
            var positionHtml = '';
            if (!tingkatanPreset) {
                var existingTings = [];
                try {
                    existingTings = JSON.parse(attr(el, 'data-tingkatan-options') || '[]') || [];
                } catch (err) { existingTings = []; }
                if (!Array.isArray(existingTings)) existingTings = [];
                var posOptsBk = [{ value: 'start', label: 'Di awal (paling atas)' }];
                existingTings.forEach(function (t) {
                    t = String(t || '').trim();
                    if (!t) return;
                    posOptsBk.push({ value: 'after:' + t, label: 'Selepas “' + t + '”' });
                });
                posOptsBk.push({ value: 'end', label: 'Di akhir (paling bawah)' });
                positionHtml = selectField('position', 'Kedudukan paparan', 'end', posOptsBk);
            }
            return (tingkatanPreset
                    ? (hidden('tingkatan', tingkatanPreset)
                        + '<p class="small text-muted mb-3">Tambah gambar untuk <strong>' + esc(tingkatanPreset) + '</strong>.</p>')
                    : (field('tingkatan', 'Tingkatan baharu', '', false, false) + positionHtml))
                + field('title', 'Tajuk', '', false, false)
                + fileField('image', 'Gambar', true)
                + '<p class="small text-muted mb-0"><i class="bi bi-info-circle me-1"></i>Gambar baharu akan ditambah. Gambar sedia ada kekal.</p>';
        }
        if (block === 'enrolmen_item') {
            var slidesEdit = [];
            try { slidesEdit = JSON.parse(attr(el, 'data-slides') || '[]') || []; } catch (err2) { slidesEdit = []; }
            if (!Array.isArray(slidesEdit)) slidesEdit = [];
            var curId = String(attr(el, 'data-id') || '');
            var posOptsEdit = [{ value: 'keep', label: 'Kekalkan kedudukan semasa' }, { value: 'start', label: 'Pindah ke awal' }];
            slidesEdit.forEach(function (s) {
                if (!s || !s.id || String(s.id) === curId) return;
                var label = String(s.title || ('Slaid #' + s.id)).trim() || ('Slaid #' + s.id);
                posOptsEdit.push({ value: 'after:' + s.id, label: 'Selepas “' + label + '”' });
            });
            posOptsEdit.push({ value: 'end', label: 'Pindah ke akhir' });
            return hidden('id', attr(el, 'data-id'))
                + field('title', 'Tajuk', attr(el, 'data-title'), false, false)
                + selectField('position', 'Kedudukan dalam slaid', 'keep', posOptsEdit)
                + fileField('image', 'Ganti gambar (pilihan)')
                + '<p class="small text-muted mb-0">Guna Padam untuk buang item ini.</p>';
        }
        if (block === 'bil_kelas_item') {
            return hidden('id', attr(el, 'data-id'))
                + field('tingkatan', 'Tingkatan', attr(el, 'data-tingkatan'), false, false)
                + field('title', 'Tajuk', attr(el, 'data-title'), false, false)
                + fileField('image', 'Ganti gambar (pilihan)')
                + '<p class="small text-muted mb-0">Guna Padam untuk buang item ini.</p>';
        }
        if (block === 'peraturan_item' || block === 'pemimpin_item') {
            return hidden('id', attr(el, 'data-id'))
                + currentFileBadge(findCurrentImagePath(el), false)
                + '<p class="small text-muted mb-0">Guna Padam untuk buang item ini.</p>';
        }
        if (block === 'enrolmen_feb') {
            return field('title', 'Tajuk', attr(el, 'data-title'), false, false)
                + fileField('image', 'Ganti gambar (pilihan)');
        }
        if (block === 'pibg_meta') {
            return field('title', 'Tajuk', attr(el, 'data-title'), false, false)
                + field('subtitle', 'Pengenalan', attr(el, 'data-subtitle'), true, false)
                + field('button_label', 'Teks butang PDF', attr(el, 'data-button-label'), false, false);
        }
        if (block === 'pibg_pdf') {
            var pibgCurrent = findCurrentPdfPath(el) || attr(el, 'data-file') || '';
            var pibgHasFile = !!fileBasename(pibgCurrent);
            return fileField(
                'pdf',
                pibgHasFile ? 'Fail PDF baharu (ganti semasa)' : 'Fail PDF',
                true,
                'application/pdf,.pdf',
                pibgCurrent || ''
            ) + (pibgHasFile
                ? '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>PDF semasa akan diganti, bukan ditambah sebagai fail baharu.</p>'
                : '');
        }
        if (block === 'enrolmen_summary') {
            return field('title', 'Tajuk bahagian', attr(el, 'data-title'), false, false)
                + field('items', 'Senarai bilangan kelas (satu baris = satu item)', attr(el, 'data-items'), true)
                + '<p class="small text-muted mb-0">Contoh: Tingkatan 1 – 4 Kelas</p>';
        }
        if (block === 'enrolmen_blok') {
            return field('title', 'Tajuk blok', attr(el, 'data-title'), false, false)
                + hidden('blok', attr(el, 'data-blok'));
        }
        if (block === 'enrolmen_floor') {
            return hidden('blok', attr(el, 'data-blok'))
                + hidden('floor_index', attr(el, 'data-floor-index'))
                + field('name', 'Nama aras', attr(el, 'data-name'), false, false);
        }
        if (block === 'enrolmen_room') {
            return hidden('blok', attr(el, 'data-blok'))
                + hidden('floor_index', attr(el, 'data-floor-index'))
                + hidden('room_index', attr(el, 'data-room-index'))
                + field('label', 'Nama bilik / kelas', attr(el, 'data-label'), false, false)
                + selectField('room_class', 'Warna / jenis', attr(el, 'data-class') || 'special', [
                    { value: 't1', label: 'Tingkatan 1 (biru)' },
                    { value: 't2', label: 'Tingkatan 2 (kuning)' },
                    { value: 't3', label: 'Tingkatan 3 (hijau)' },
                    { value: 't4', label: 'Tingkatan 4 (merah)' },
                    { value: 't5', label: 'Tingkatan 5 (ungu)' },
                    { value: 'special', label: 'Khas / lain' },
                    { value: 'library', label: 'Perpustakaan' }
                ]);
        }
        if (block === 'pelan_image') {
            return fileField('image', 'Gambar pelan baharu (ganti semasa)', true)
                + '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Gambar pelan semasa akan diganti.</p>';
        }
        if (block === 'pra_sekolah' || block === 'pra_sekolah_carta' || block === 'pra_sekolah_galeri') {
            if (block === 'pra_sekolah_carta') {
                var cartaPath = findCurrentImagePath(el);
                return fileField('gambar_carta', cartaPath ? 'Gambar carta baharu (ganti semasa)' : 'Gambar carta organisasi', true, 'image/*', cartaPath)
                    + (cartaPath ? '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Gambar carta semasa akan diganti.</p>' : '');
            }
            if (block === 'pra_sekolah_galeri') {
                var galeriPath = findCurrentImagePath(el);
                return fileField('gambar_galeri', galeriPath ? 'Gambar galeri baharu (ganti semasa)' : 'Gambar galeri murid', true, 'image/*', galeriPath)
                    + (galeriPath ? '<p class="small text-warning mb-0"><i class="bi bi-exclamation-triangle me-1"></i>Gambar galeri semasa akan diganti.</p>' : '');
            }
            var praImgs = el.querySelectorAll('img[src]');
            var cartaSrc = praImgs[0] && !isPlaceholderMedia(praImgs[0].getAttribute('src') || '')
                ? (praImgs[0].getAttribute('src') || '')
                : '';
            var galeriSrc = praImgs[1] && !isPlaceholderMedia(praImgs[1].getAttribute('src') || '')
                ? (praImgs[1].getAttribute('src') || '')
                : '';
            return fileField('gambar_carta', 'Gambar carta (pilihan)', false, 'image/*', cartaSrc)
                + fileField('gambar_galeri', 'Gambar galeri (pilihan)', false, 'image/*', galeriSrc);
        }
        if (block === 'lencana_moto') {
            return field('moto', 'Moto', attr(el, 'data-moto'), false);
        }
        if (block === 'lencana_lagu') {
            return '<div class="mb-3">'
                + '<label class="form-label" for="smks3_f_lirik">Lirik lagu' + requiredMark(true) + '</label>'
                + '<textarea class="smks3-lyrics-editor" id="smks3_f_lirik" name="lirik" rows="18" required spellcheck="true">'
                + esc(attr(el, 'data-lirik'))
                + '</textarea>'
                + '<p class="small text-muted mb-0 mt-1">Tekan Enter untuk baris baharu. Biarkan satu baris kosong antara bait.</p>'
                + '</div>'
                + '<div class="smks3-credit-grid mb-1">'
                + '<div class="mb-3 mb-md-0"><label class="form-label" for="smks3_f_lirik_penggubah">Penggubah (Lagu)</label>'
                + '<input type="text" class="form-control" id="smks3_f_lirik_penggubah" name="lirik_penggubah" value="' + esc(attr(el, 'data-lirik-penggubah')) + '" placeholder="Nama penggubah"></div>'
                + '<div class="mb-0"><label class="form-label" for="smks3_f_lirik_penulis">Penulis (Lirik)</label>'
                + '<input type="text" class="form-control" id="smks3_f_lirik_penulis" name="lirik_penulis" value="' + esc(attr(el, 'data-lirik-penulis')) + '" placeholder="Nama penulis lirik"></div>'
                + '</div>';
        }
        if (block === 'lencana_main') {
            return field('moto', 'Moto', attr(el, 'data-moto'), true, false)
                + field('lirik', 'Lirik', attr(el, 'data-lirik'), true, false)
                + field('lirik_penggubah', 'Penggubah', attr(el, 'data-lirik-penggubah'), false, false)
                + field('lirik_penulis', 'Penulis', attr(el, 'data-lirik-penulis'), false, false)
                + fileField('image', 'Ganti gambar lencana (pilihan)');
        }
        if (block === 'lencana_item' || block === 'lencana_item_add') {
            var isAddL = block === 'lencana_item_add';
            return (isAddL ? '' : hidden('id', attr(el, 'data-id')))
                + field('title', 'Tajuk', isAddL ? '' : attr(el, 'data-title'))
                + field('description', 'Penerangan', isAddL ? '' : attr(el, 'data-description'), true, false);
        }
        if (block === 'kalendar_page') {
            return field('title', 'Tajuk halaman', attr(el, 'data-title'), false, false)
                + field('content', 'Nota / pengenalan ringkas', attr(el, 'data-content'), true, false)
                + '<p class="small text-muted mb-0">Tulis seperti biasa. Untuk jadual penuh, muat naik PDF di bawah.</p>';
        }
        if (block === 'cta_text') {
            var ctaSchool = resolveSchoolName();
            var ctaRaw = attr(el, 'data-value') || attr(el, 'data-display') || '';
            var ctaDisplay = resolveContentPlaceholders(ctaRaw, ctaSchool);
            return field('value', 'Teks', ctaDisplay, true)
                + '<p class="form-text mb-0">Tulis nama sekolah seperti biasa. Sistem akan kekalkan pautan automatik ke Maklumat Sekolah.</p>';
        }
        var value = el.getAttribute('data-value');
        if (value === null) value = (el.textContent || '').trim();
        return field('value', 'Teks', value, block.indexOf('subtitle') !== -1 || block.indexOf('text') !== -1 || block === 'cta_text');
    }

    function resolveSchoolName() {
        var node = document.querySelector('[data-bind="school_name"]');
        var name = node ? String(node.textContent || '').trim() : '';
        if (name) return name;
        var dataNode = document.querySelector('[data-school-name]');
        if (dataNode) {
            name = String(dataNode.getAttribute('data-school-name') || '').trim();
            if (name) return name;
        }
        return 'Sekolah Menengah Kebangsaan Seremban 3';
    }

    function resolveContentPlaceholders(text, schoolName) {
        schoolName = schoolName || resolveSchoolName();
        return String(text == null ? '' : text).replace(/\{\s*school_name\s*\}/gi, schoolName);
    }

    function hidden(name, value) {
        return '<input type="hidden" name="' + esc(name) + '" value="' + esc(value) + '">';
    }

    function requiredMark(required) {
        return required ? ' <span class="text-danger" aria-hidden="true">*</span>' : '';
    }

    function field(name, label, value, multiline, required) {
        if (required === undefined) required = true;
        var id = 'smks3_f_' + name;
        var req = required ? ' required' : '';
        if (multiline) {
            return '<div class="mb-3"><label class="form-label" for="' + id + '">' + esc(label) + requiredMark(required) + '</label>'
                + '<textarea class="form-control" id="' + id + '" name="' + esc(name) + '" rows="5"' + req + '>' + esc(value) + '</textarea></div>';
        }
        return '<div class="mb-3"><label class="form-label" for="' + id + '">' + esc(label) + requiredMark(required) + '</label>'
            + '<input type="text" class="form-control" id="' + id + '" name="' + esc(name) + '" value="' + esc(value) + '"' + req + '></div>';
    }

    function normalizePageHref(href) {
        href = String(href == null ? '' : href).trim();
        if (!href || href === '#' || href === '/' || href === './' || href === 'index' || href === 'index.php') {
            return './';
        }
        if (/^https?:\/\//i.test(href) || href.indexOf('://') !== -1) {
            return href;
        }
        href = href.split('#')[0].split('?')[0];
        href = href.replace(/^\.\//, '').replace(/^\/+/, '');
        href = href.replace(/\.php$/i, '');
        return href || './';
    }

    function pageHrefField(currentHref) {
        var raw = String(currentHref == null ? '' : currentHref).trim();
        var normalized = normalizePageHref(raw);
        var known = {};
        PAGE_HREF_OPTIONS.forEach(function (opt) { known[opt.value] = true; });
        var isKnown = !!known[normalized];
        var pickValue = isKnown ? normalized : '__custom__';
        var customValue = isKnown ? '' : raw;

        var groups = {};
        var groupOrder = [];
        PAGE_HREF_OPTIONS.forEach(function (opt) {
            var g = opt.group || 'Lain';
            if (!groups[g]) {
                groups[g] = [];
                groupOrder.push(g);
            }
            groups[g].push(opt);
        });

        var optsHtml = groupOrder.map(function (g) {
            return '<optgroup label="' + esc(g) + '">'
                + groups[g].map(function (opt) {
                    var selected = String(opt.value) === String(pickValue) ? ' selected' : '';
                    return '<option value="' + esc(opt.value) + '"' + selected + '>' + esc(opt.label) + '</option>';
                }).join('')
                + '</optgroup>';
        }).join('');
        optsHtml += '<option value="__custom__"' + (pickValue === '__custom__' ? ' selected' : '') + '>Lain — taip URL sendiri</option>';

        return '<div class="mb-3" data-page-href-field>'
            + '<label class="form-label" for="smks3_f_href_pick">Halaman / pautan' + requiredMark(true) + '</label>'
            + '<select class="form-select" id="smks3_f_href_pick" aria-describedby="smks3_f_href_hint">' + optsHtml + '</select>'
            + '<div class="mt-2' + (pickValue === '__custom__' ? '' : ' d-none') + '" data-page-href-custom>'
            + '<label class="form-label" for="smks3_f_href_custom">URL tersuai</label>'
            + '<input type="text" class="form-control" id="smks3_f_href_custom" value="' + esc(customValue) + '" placeholder="https://… atau nama-halaman" autocomplete="off">'
            + '</div>'
            + '<input type="hidden" name="href" id="smks3_f_href" value="' + esc(isKnown ? normalized : raw) + '" required>'
            + '<p class="form-text mb-0 mt-1" id="smks3_f_href_hint">Pilih halaman dalam laman web, atau gunakan URL tersuai untuk pautan luar.</p>'
            + '</div>';
    }

    function bindPageHrefField(root) {
        var wrap = root.querySelector('[data-page-href-field]');
        if (!wrap) return;
        var pick = wrap.querySelector('#smks3_f_href_pick');
        var customWrap = wrap.querySelector('[data-page-href-custom]');
        var custom = wrap.querySelector('#smks3_f_href_custom');
        var hidden = wrap.querySelector('#smks3_f_href');
        var external = root.querySelector('#smks3_f_external');
        if (!pick || !hidden) return;

        function sync(fromUser) {
            var isCustom = pick.value === '__custom__';
            if (customWrap) customWrap.classList.toggle('d-none', !isCustom);
            if (isCustom) {
                hidden.value = (custom && custom.value) ? custom.value.trim() : '';
                if (fromUser && external && custom && /^https?:\/\//i.test(custom.value.trim())) {
                    external.checked = true;
                }
            } else {
                hidden.value = pick.value;
                if (fromUser && external) external.checked = false;
            }
        }

        pick.addEventListener('change', function () { sync(true); });
        if (custom) {
            custom.addEventListener('input', function () { sync(true); });
        }
        sync(false);
    }

    function richTextField(name, label, html, required) {
        if (required === undefined) required = false;
        return '<div class="mb-3 smks3-quill-wrap" data-rich-text-field data-rich-name="' + esc(name) + '">'
            + '<label class="form-label">' + esc(label) + requiredMark(!!required) + '</label>'
            + '<input type="hidden" name="' + esc(name) + '" id="smks3_f_' + esc(name) + '" value="">'
            + '<div class="smks3-quill-editor" data-initial-html="' + esc(html || '') + '"></div>'
            + '<p class="form-text mb-0 mt-1">Gunakan bar alat untuk tebal, senarai, pautan dan tajuk.</p>'
            + '</div>';
    }

    function newsImagesField(el) {
        var images = [];
        if (el) {
            try {
                images = JSON.parse(el.getAttribute('data-images-json') || '[]') || [];
            } catch (err) {
                images = [];
            }
            if (!Array.isArray(images) || !images.length) {
                var fallback = findCurrentImagePath(el);
                if (fallback) images = [fallback];
            }
        }
        var listHtml = '';
        if (images.length) {
            listHtml = '<div class="smks3-news-images">' + images.map(function (src, idx) {
                src = String(src || '').trim();
                if (!src) return '';
                var name = fileBasename(src);
                var parts = splitFileName(name, false);
                var label = (parts.stem || name) + (parts.ext || '');
                var fileKey = name || ('image-' + idx);
                return '<div class="smks3-news-images__item">'
                    + '<img class="smks3-news-images__thumb" src="' + esc(src) + '" alt="">'
                    + '<div class="smks3-news-images__meta">'
                    + '<span class="smks3-news-images__name" title="' + esc(label) + '">'
                    + '<span class="smks3-current-file__stem">' + esc(parts.stem || label) + '</span>'
                    + (parts.ext ? '<span class="smks3-current-file__ext">' + esc(parts.ext) + '</span>' : '')
                    + '</span>'
                    + '<label class="smks3-news-images__remove form-check">'
                    + '<input class="form-check-input me-1" type="checkbox" name="remove_images[]" value="' + esc(fileKey) + '">'
                    + 'Buang'
                    + '</label>'
                    + '</div></div>';
            }).join('') + '</div>';
        }
        return '<div class="mb-3">'
            + '<label class="form-label" for="smks3_f_images">Gambar (boleh lebih daripada satu)</label>'
            + listHtml
            + '<input type="file" class="form-control" id="smks3_f_images" name="images[]" accept="image/*" multiple>'
            + '<p class="form-text mb-0">Pilih beberapa fail sekali gus. Tandai “Buang” untuk padam gambar sedia ada.</p>'
            + '</div>';
    }

    function ensureQuillLoaded(done) {
        if (window.Quill) {
            done();
            return;
        }
        if (quillLoading) {
            quillLoading.then(done).catch(function () { done(); });
            return;
        }
        quillLoading = new Promise(function (resolve, reject) {
            if (!document.querySelector('link[data-smks3-quill]')) {
                var css = document.createElement('link');
                css.rel = 'stylesheet';
                css.href = 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css';
                css.setAttribute('data-smks3-quill', '1');
                document.head.appendChild(css);
            }
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js';
            script.async = true;
            script.onload = function () { resolve(); };
            script.onerror = function () { reject(new Error('Quill gagal dimuat.')); };
            document.head.appendChild(script);
        });
        quillLoading.then(done).catch(function () {
            setStatus('Editor teks gagal dimuat. Cuba muat semula halaman.', false);
            done();
        });
    }

    function plainTextToQuillHtml(text) {
        text = String(text == null ? '' : text).trim();
        if (!text) return '';
        if (/<[a-z][\s\S]*>/i.test(text)) return text;
        return text.split(/\n\s*\n/).map(function (part) {
            var t = String(part || '').trim();
            if (!t) return '';
            return '<p>' + esc(t).replace(/\n/g, '<br>') + '</p>';
        }).filter(Boolean).join('');
    }

    function syncRichTextEditor() {
        if (!quillInstance || !form) return;
        var wrap = form.querySelector('[data-rich-text-field]');
        if (!wrap) return;
        var name = wrap.getAttribute('data-rich-name') || 'content';
        var hidden = form.querySelector('#smks3_f_' + name) || form.querySelector('[name="' + name + '"]');
        if (!hidden) return;
        var html = String(quillInstance.root.innerHTML || '').trim();
        if (html === '<p><br></p>' || html === '<p></p>') html = '';
        hidden.value = html;
    }

    function destroyRichTextEditor() {
        syncRichTextEditor();
        quillInstance = null;
    }

    function bindRichTextEditor(root) {
        var wrap = root.querySelector('[data-rich-text-field]');
        if (!wrap) return;
        var holder = wrap.querySelector('.smks3-quill-editor');
        var name = wrap.getAttribute('data-rich-name') || 'content';
        var hidden = root.querySelector('#smks3_f_' + name) || root.querySelector('[name="' + name + '"]');
        if (!holder || !hidden) return;
        var initialHtml = plainTextToQuillHtml(holder.getAttribute('data-initial-html') || '');
        ensureQuillLoaded(function () {
            if (!window.Quill || !holder.isConnected) return;
            quillInstance = new Quill(holder, {
                theme: 'snow',
                placeholder: 'Tulis kandungan berita…',
                modules: {
                    toolbar: [
                        [{ header: [2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });
            if (initialHtml) {
                quillInstance.clipboard.dangerouslyPasteHTML(initialHtml);
            }
            syncRichTextEditor();
            quillInstance.on('text-change', syncRichTextEditor);
        });
    }

    function selectField(name, label, value, options) {
        var id = 'smks3_f_' + name;
        var opts = (options || []).map(function (opt) {
            var selected = String(opt.value) === String(value) ? ' selected' : '';
            return '<option value="' + esc(opt.value) + '"' + selected + '>' + esc(opt.label) + '</option>';
        }).join('');
        return '<div class="mb-3"><label class="form-label" for="' + id + '">' + esc(label) + requiredMark(true) + '</label>'
            + '<select class="form-select" id="' + id + '" name="' + esc(name) + '" required>' + opts + '</select></div>';
    }

    function getIconOptions(selected) {
        var favSet = {};
        FAVORITE_ICONS.forEach(function (v) { favSet[v] = true; });
        var favorites = [];
        var rest = [];
        var seen = {};
        function pushOpt(opt, bucket) {
            if (!opt || !opt.value || seen[opt.value]) return;
            seen[opt.value] = true;
            bucket.push(opt);
        }
        if (selected) {
            var selectedOpt = null;
            for (var i = 0; i < ICON_OPTIONS.length; i++) {
                if (ICON_OPTIONS[i].value === selected) { selectedOpt = ICON_OPTIONS[i]; break; }
            }
            pushOpt(selectedOpt || { value: selected, label: 'Semasa' }, favorites);
        }
        FAVORITE_ICONS.forEach(function (value) {
            var found = null;
            for (var j = 0; j < ICON_OPTIONS.length; j++) {
                if (ICON_OPTIONS[j].value === value) { found = ICON_OPTIONS[j]; break; }
            }
            pushOpt(found || { value: value, label: value.replace(/^bi-/, '').replace(/-/g, ' ') }, favorites);
        });
        ICON_OPTIONS.forEach(function (opt) {
            if (favSet[opt.value]) return;
            pushOpt(opt, rest);
        });
        return favorites.concat(rest);
    }

    function iconPicker(name, label, selected) {
        selected = selected || 'bi-link-45deg';
        var options = getIconOptions(selected);
        var buttons = options.map(function (opt) {
            var isSel = opt.value === selected;
            var searchText = String(opt.value + ' ' + opt.label).toLowerCase();
            return '<button type="button" class="smks3-icon-picker__btn' + (isSel ? ' is-selected' : '') + '" data-icon-value="' + esc(opt.value) + '" data-icon-search="' + esc(searchText) + '" title="' + esc(opt.label) + '">'
                + '<i class="bi ' + esc(opt.value) + '" aria-hidden="true"></i>'
                + '<span>' + esc(opt.label) + '</span>'
                + '</button>';
        }).join('');
        var searchId = 'smks3_icon_search_' + name;
        return '<div class="mb-3">'
            + '<label class="form-label">' + esc(label) + requiredMark(true) + '</label>'
            + '<input type="hidden" name="' + esc(name) + '" id="smks3_f_' + esc(name) + '" value="' + esc(selected) + '" required>'
            + '<input type="search" class="form-control form-control-sm smks3-icon-picker__search" id="' + searchId + '" placeholder="Cari ikon… (cth: book, people, trophy)" autocomplete="off">'
            + '<div class="smks3-icon-picker" data-icon-picker-for="' + esc(name) + '">' + buttons + '</div>'
            + '<p class="smks3-icon-picker__meta" data-icon-meta-for="' + esc(name) + '">Menunjukkan semua ' + options.length + ' ikon Bootstrap. Guna carian untuk tapis.</p>'
            + '</div>';
    }

    function bindIconPicker(root) {
        if (!root) return;
        root.querySelectorAll('[data-icon-picker-for]').forEach(function (grid) {
            var name = grid.getAttribute('data-icon-picker-for');
            var input = root.querySelector('#smks3_f_' + name) || root.querySelector('[name="' + name + '"]');
            var search = root.querySelector('#smks3_icon_search_' + name);
            var meta = root.querySelector('[data-icon-meta-for="' + name + '"]');
            var buttons = grid.querySelectorAll('.smks3-icon-picker__btn');

            function updateMeta() {
                if (!meta) return;
                var visible = 0;
                buttons.forEach(function (b) { if (!b.classList.contains('is-hidden')) visible++; });
                meta.textContent = 'Menunjukkan ' + visible + ' / ' + buttons.length + ' ikon Bootstrap.';
            }

            grid.addEventListener('click', function (e) {
                var btn = e.target.closest('[data-icon-value]');
                if (!btn || !grid.contains(btn)) return;
                e.preventDefault();
                var value = btn.getAttribute('data-icon-value') || '';
                if (input) input.value = value;
                buttons.forEach(function (b) {
                    b.classList.toggle('is-selected', b === btn);
                });
            });

            if (search) {
                search.addEventListener('input', function () {
                    var q = String(search.value || '').trim().toLowerCase();
                    buttons.forEach(function (b) {
                        var hay = b.getAttribute('data-icon-search') || '';
                        b.classList.toggle('is-hidden', q !== '' && hay.indexOf(q) === -1);
                    });
                    updateMeta();
                });
            }
            updateMeta();
        });
    }

    function isExternalKurikulumUrl(href) {
        return /^https?:\/\//i.test(String(href || '').trim());
    }

    /** Which external-link patterns already exist in this kurikulum section. */
    function kurikulumSectionCaps(pageKey, sectionKey) {
        var caps = { hasExternalButton: false, hasExternalFolder: false };
        pageKey = String(pageKey || '');
        sectionKey = String(sectionKey || 'main') || 'main';
        document.querySelectorAll('[data-edit-block="kurikulum_card"]').forEach(function (card) {
            if (attr(card, 'data-page-key') !== pageKey) return;
            if ((attr(card, 'data-section-key') || 'main') !== sectionKey) return;
            var href = attr(card, 'data-href') || '';
            var links = [];
            try { links = JSON.parse(attr(card, 'data-links-json') || '[]') || []; } catch (err) { links = []; }
            if (!Array.isArray(links)) links = [];
            if (links.length > 0) {
                links.forEach(function (link) {
                    if (isExternalKurikulumUrl(link.href || '')) caps.hasExternalFolder = true;
                });
            } else if (isExternalKurikulumUrl(href)) {
                caps.hasExternalButton = true;
            }
        });
        return caps;
    }

    function linksEditorRow(title, href) {
        title = title || '';
        href = href || '';
        return '<div class="smks3-links-editor__row" data-link-editable="1">'
            + '<button type="button" class="smks3-links-editor__remove" title="Buang pautan" aria-label="Buang pautan">&times;</button>'
            + '<div><label class="form-label mb-1">Tajuk pautan</label>'
            + '<input type="text" class="form-control form-control-sm" name="link_title[]" value="' + esc(title) + '" placeholder="Contoh: ANALISIS TINGKATAN 1"></div>'
            + '<div><label class="form-label mb-1">URL luar</label>'
            + '<input type="text" class="form-control form-control-sm" name="link_href[]" value="' + esc(href) + '" placeholder="https://docs.google.com/..." inputmode="url" autocomplete="off"></div>'
            + '</div>';
    }

    var SOCIAL_ICON_OPTIONS = [
        { value: 'bi-facebook', label: 'Facebook' },
        { value: 'bi-tiktok', label: 'TikTok' },
        { value: 'bi-youtube', label: 'YouTube' },
        { value: 'bi-instagram', label: 'Instagram' },
        { value: 'bi-twitter-x', label: 'X (Twitter)' },
        { value: 'bi-whatsapp', label: 'WhatsApp' },
        { value: 'bi-telegram', label: 'Telegram' },
        { value: 'bi-linkedin', label: 'LinkedIn' },
        { value: 'bi-globe2', label: 'Laman web' }
    ];

    function socialEditorRow(label, icon, href) {
        label = label || '';
        icon = icon || 'bi-facebook';
        href = href || '';
        var opts = SOCIAL_ICON_OPTIONS.map(function (opt) {
            return '<option value="' + esc(opt.value) + '"' + (opt.value === icon ? ' selected' : '') + '>'
                + esc(opt.label) + '</option>';
        }).join('');
        if (icon && !SOCIAL_ICON_OPTIONS.some(function (o) { return o.value === icon; })) {
            opts = '<option value="' + esc(icon) + '" selected>' + esc(icon) + '</option>' + opts;
        }
        return '<div class="smks3-links-editor__row" data-social-row="1">'
            + '<button type="button" class="smks3-links-editor__remove" title="Buang pautan" aria-label="Buang pautan">&times;</button>'
            + '<div><label class="form-label mb-1">Nama' + requiredMark(true) + '</label>'
            + '<input type="text" class="form-control form-control-sm" name="social_label[]" value="' + esc(label) + '" placeholder="Facebook" required></div>'
            + '<div><label class="form-label mb-1">Ikon' + requiredMark(true) + '</label>'
            + '<select class="form-select form-select-sm" name="social_icon[]" required>' + opts + '</select></div>'
            + '<div><label class="form-label mb-1">Pautan' + requiredMark(true) + '</label>'
            + '<input type="url" class="form-control form-control-sm" name="social_href[]" value="' + esc(href) + '" placeholder="https://..." required></div>'
            + '</div>';
    }

    function socialEditor(links) {
        links = Array.isArray(links) ? links : [];
        var rows = links.map(function (link) {
            return socialEditorRow(link.label || '', link.icon || 'bi-facebook', link.href || '');
        }).join('');
        return '<div class="mb-3">'
            + '<label class="form-label">Pautan media sosial</label>'
            + '<div class="smks3-links-editor" data-social-editor>'
            + '<div class="smks3-links-editor__list" data-social-list>'
            + (rows || '<p class="smks3-links-editor__empty" data-social-empty>Tiada pautan. Klik “Tambah pautan”.</p>')
            + '</div>'
            + '<button type="button" class="btn btn-outline-primary btn-sm w-100" data-social-add>'
            + '<i class="bi bi-plus-lg me-1"></i> Tambah pautan'
            + '</button>'
            + '</div>'
            + '</div>';
    }

    function bindSocialEditor(root) {
        if (!root) return;
        var editor = root.querySelector('[data-social-editor]');
        if (!editor) return;
        var list = editor.querySelector('[data-social-list]');
        var addBtn = editor.querySelector('[data-social-add]');

        function refreshEmpty() {
            if (!list) return;
            var rows = list.querySelectorAll('[data-social-row]');
            var empty = list.querySelector('[data-social-empty]');
            if (rows.length === 0) {
                if (!empty) {
                    list.innerHTML = '<p class="smks3-links-editor__empty" data-social-empty>Tiada pautan. Klik “Tambah pautan”.</p>';
                }
            } else if (empty) {
                empty.remove();
            }
        }

        if (addBtn) {
            addBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!list) return;
                var empty = list.querySelector('[data-social-empty]');
                if (empty) empty.remove();
                list.insertAdjacentHTML('beforeend', socialEditorRow('', 'bi-facebook', 'https://'));
                var last = list.querySelector('[data-social-row]:last-child input[name="social_label[]"]');
                if (last) last.focus();
            });
        }

        editor.addEventListener('click', function (e) {
            var btn = e.target.closest('.smks3-links-editor__remove');
            if (!btn || !editor.contains(btn)) return;
            e.preventDefault();
            var row = btn.closest('[data-social-row]');
            if (row) row.remove();
            refreshEmpty();
        });
    }

    function bindKurikulumCardKind(root) {
        if (!root) return;
        var select = root.querySelector('[data-kurikulum-card-kind]');
        if (!select) return;
        function sync() {
            var kind = select.value || 'info';
            root.querySelectorAll('[data-kurikulum-fields]').forEach(function (box) {
                var show = box.getAttribute('data-kurikulum-fields') === kind;
                box.hidden = !show;
                box.querySelectorAll('input, textarea, select').forEach(function (input) {
                    if (input.type === 'hidden') return;
                    // Disable hidden-section inputs so they are not submitted.
                    input.disabled = !show;
                });
            });
        }
        select.addEventListener('change', sync);
        sync();
    }

    function linksEditor(links, optional) {
        links = Array.isArray(links) ? links : [];
        optional = !!optional;
        var externalLinks = links.filter(function (link) {
            return isExternalKurikulumUrl(link.href || '');
        });
        var internalLinks = links.filter(function (link) {
            return !isExternalKurikulumUrl(link.href || '');
        });
        // Keep internal portal links on save, but do not show them in the editor.
        var hiddenInternal = internalLinks.map(function (link) {
            return '<input type="hidden" name="link_title[]" value="' + esc(link.title || '') + '">'
                + '<input type="hidden" name="link_href[]" value="' + esc(link.href || '') + '">';
        }).join('');
        var rows = externalLinks.map(function (link) {
            return linksEditorRow(link.title || '', link.href || '');
        }).join('');
        var hint = optional
            ? 'Pilihan: jika anda tambah pautan di sini, kad akan jadi “Lihat Bahagian” (senarai pautan) dan butang tunggal tidak digunakan.'
            : 'Pautan ini dipaparkan dalam senarai “Lihat Bahagian”.';
        return '<div class="mb-3">'
            + '<label class="form-label">' + (optional ? 'Pautan luar (pilihan)' : 'Pautan luar (https://…)') + '</label>'
            + hiddenInternal
            + '<div class="smks3-links-editor" data-links-editor>'
            + '<div class="smks3-links-editor__list" data-links-list>'
            + (rows || '<p class="smks3-links-editor__empty" data-links-empty>Tiada pautan luar. Klik “Tambah pautan luar”.</p>')
            + '</div>'
            + '<button type="button" class="btn btn-outline-primary btn-sm w-100" data-links-add>'
            + '<i class="bi bi-plus-lg me-1"></i> Tambah pautan luar'
            + '</button>'
            + '</div>'
            + '<p class="small text-muted mb-0 mt-1">' + hint + '</p>'
            + '</div>';
    }

    function bindLinksEditor(root) {
        if (!root) return;
        var editor = root.querySelector('[data-links-editor]');
        if (!editor) return;
        var list = editor.querySelector('[data-links-list]');
        var addBtn = editor.querySelector('[data-links-add]');

        function refreshEmpty() {
            if (!list) return;
            var rows = list.querySelectorAll('.smks3-links-editor__row');
            var empty = list.querySelector('[data-links-empty]');
            if (rows.length === 0) {
                if (!empty) {
                    list.innerHTML = '<p class="smks3-links-editor__empty" data-links-empty>Tiada pautan luar lagi. Klik “Tambah pautan luar”.</p>';
                }
            } else if (empty) {
                empty.remove();
            }
        }

        if (addBtn) {
            addBtn.addEventListener('click', function (e) {
                e.preventDefault();
                if (!list) return;
                var empty = list.querySelector('[data-links-empty]');
                if (empty) empty.remove();
                list.insertAdjacentHTML('beforeend', linksEditorRow('', 'https://', false));
                var last = list.querySelector('.smks3-links-editor__row:last-child input[name="link_href[]"]');
                if (last) {
                    last.focus();
                    last.select();
                }
            });
        }

        if (list) {
            list.addEventListener('click', function (e) {
                var btn = e.target.closest('.smks3-links-editor__remove');
                if (!btn || !list.contains(btn)) return;
                var row = btn.closest('.smks3-links-editor__row');
                if (!row || row.classList.contains('is-locked')) return;
                e.preventDefault();
                row.remove();
                refreshEmpty();
            });
        }
    }

    function check(name, label, checked) {
        return '<div class="form-check mb-3"><input class="form-check-input" type="checkbox" value="1" id="smks3_f_' + esc(name) + '" name="' + esc(name) + '"' + (checked ? ' checked' : '') + '>'
            + '<label class="form-check-label" for="smks3_f_' + esc(name) + '">' + esc(label) + '</label></div>';
    }

    function fileBasename(path) {
        path = String(path == null ? '' : path).trim().split('?')[0].split('#')[0];
        if (!path) return '';
        try {
            if (/^https?:\/\//i.test(path) || path.indexOf('/') === 0) {
                var u = new URL(path, window.location.href);
                path = u.pathname;
            }
        } catch (err) {}
        path = path.replace(/\\/g, '/');
        var parts = path.split('/').filter(Boolean);
        if (!parts.length) return '';
        try {
            return decodeURIComponent(parts[parts.length - 1]);
        } catch (err2) {
            return parts[parts.length - 1];
        }
    }

    function splitFileName(name, preferPdf) {
        name = String(name == null ? '' : name).trim();
        if (!name) return { stem: '', ext: '' };
        var match = name.match(/^(.*?)(\.[a-z0-9]{2,8})$/i);
        if (match) {
            return { stem: match[1] || name, ext: match[2].toLowerCase() };
        }
        // Path/name without extension — still show type clearly.
        if (preferPdf) return { stem: name, ext: '.pdf' };
        return { stem: name, ext: '' };
    }

    function guessImageExtFromPath(path) {
        var fromName = splitFileName(fileBasename(path), false).ext;
        if (fromName) return fromName;
        var lower = String(path || '').toLowerCase();
        if (lower.indexOf('image/png') !== -1 || /\.png(?:$|[?#])/i.test(lower)) return '.png';
        if (lower.indexOf('image/webp') !== -1 || /\.webp(?:$|[?#])/i.test(lower)) return '.webp';
        if (lower.indexOf('image/gif') !== -1 || /\.gif(?:$|[?#])/i.test(lower)) return '.gif';
        if (lower.indexOf('image/jpeg') !== -1 || /\.jpe?g(?:$|[?#])/i.test(lower)) return '.jpg';
        return '';
    }

    function isPlaceholderMedia(src) {
        src = String(src || '').trim().toLowerCase();
        return !src
            || src.indexOf('data:') === 0
            || /placeholder|default-avatar|no[-_]?image|via\.placeholder|blank\.png|blank\.jpg/i.test(src);
    }

    function findCurrentImagePath(el) {
        if (!el) return '';
        var dataFile = el.getAttribute('data-image') || el.getAttribute('data-file') || el.getAttribute('data-photo') || '';
        if (dataFile && !/\.pdf$/i.test(dataFile) && !isPlaceholderMedia(dataFile)) return dataFile;
        var imgs = el.querySelectorAll('img[src]');
        for (var i = 0; i < imgs.length; i++) {
            var src = imgs[i].getAttribute('src') || '';
            if (!isPlaceholderMedia(src)) return src;
        }
        return '';
    }

    function findCurrentPdfPath(el) {
        if (!el) return '';
        var dataFile = el.getAttribute('data-pdf') || el.getAttribute('data-file') || '';
        if (dataFile && (/\.pdf$/i.test(dataFile) || /pdf/i.test(el.getAttribute('data-edit-block') || ''))) {
            return dataFile;
        }
        var pdf = el.querySelector('[data-pdf-src]');
        if (pdf && pdf.getAttribute('data-pdf-src')) return pdf.getAttribute('data-pdf-src');
        var canvas = el.querySelector('canvas[data-pdf]');
        if (canvas && canvas.getAttribute('data-pdf')) return canvas.getAttribute('data-pdf');
        var link = el.querySelector('a[href*=".pdf"], a[href$=".PDF"], a[download]');
        if (link && link.getAttribute('href')) return link.getAttribute('href');
        var block = el.getAttribute('data-edit-block') || '';
        // Do not borrow the first PDF on the page for "Tambah" panels.
        if (/pdf/i.test(block) && block.indexOf('_add') === -1) {
            var pagePdf = document.querySelector('.smks3-pdf-pages[data-pdf-src], [data-pdf-src]');
            if (pagePdf && pagePdf.getAttribute('data-pdf-src')) {
                return pagePdf.getAttribute('data-pdf-src');
            }
        }
        return '';
    }

    function currentFileBadge(path, preferPdf) {
        var name = fileBasename(path);
        if (!name && !path) return '';
        if (!name) name = String(path);
        var parts = splitFileName(name, !!preferPdf);
        if (!parts.ext && !preferPdf) {
            parts.ext = guessImageExtFromPath(path);
        }
        if (!parts.stem && !parts.ext) return '';
        var fullName = parts.stem + parts.ext;
        var isPdf = preferPdf || parts.ext === '.pdf';
        return '<div class="smks3-current-file">'
            + '<i class="bi ' + (isPdf ? 'bi-file-earmark-pdf' : 'bi-file-earmark-image') + '" aria-hidden="true"></i>'
            + '<span class="smks3-current-file__name" title="' + esc(fullName) + '">'
            + '<span class="smks3-current-file__stem">' + esc(parts.stem || fullName) + '</span>'
            + (parts.ext ? '<span class="smks3-current-file__ext">' + esc(parts.ext) + '</span>' : '')
            + '</span>'
            + '<span class="smks3-current-file__label">Fail semasa</span>'
            + '</div>';
    }

    function fileField(name, label, required, accept, currentPath, allowRemove) {
        accept = accept || 'image/*';
        var isPdf = /pdf/i.test(String(accept || ''));
        if (currentPath === undefined) {
            currentPath = isPdf ? findCurrentPdfPath(activeEl) : findCurrentImagePath(activeEl);
        }
        var parts = splitFileName(fileBasename(currentPath), isPdf);
        if (!parts.ext && !isPdf) parts.ext = guessImageExtFromPath(currentPath);
        var fileName = (parts.stem || '') + (parts.ext || '');
        var mustUpload = !!required && !fileName;
        var removeName = 'remove_' + name;
        var removeHtml = '';
        if (allowRemove && fileName) {
            removeHtml = '<div class="form-check mb-2">'
                + '<input class="form-check-input" type="checkbox" value="1" id="smks3_f_' + esc(removeName) + '" name="' + esc(removeName) + '">'
                + '<label class="form-check-label" for="smks3_f_' + esc(removeName) + '">Buang gambar semasa</label>'
                + '</div>';
        }
        return '<div class="mb-3"><label class="form-label" for="smks3_f_' + esc(name) + '">' + esc(label) + requiredMark(mustUpload) + '</label>'
            + currentFileBadge(currentPath, isPdf)
            + removeHtml
            + '<input type="file" class="form-control" id="smks3_f_' + esc(name) + '" name="' + esc(name) + '" accept="' + esc(accept) + '"' + (mustUpload ? ' required' : '') + '>'
            + (fileName && !mustUpload
                ? ('<p class="form-text mb-0">' + (allowRemove
                    ? 'Biarkan kosong untuk kekalkan fail semasa, atau tandakan “Buang” untuk padam.'
                    : 'Biarkan kosong untuk kekalkan fail semasa.') + '</p>')
                : '')
            + '</div>';
    }

    function esc(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function applyToPage(payload) {
        if (!activeEl || !payload || !payload.fields) return;
        var f = payload.fields;
        var block = currentBlock;

        if (block === 'school_info') {
            activeEl.setAttribute('data-school-name', f.school_name || '');
            activeEl.setAttribute('data-address', f.address || '');
            activeEl.setAttribute('data-phone', f.phone || '');
            activeEl.setAttribute('data-email', f.email || '');
            document.querySelectorAll('[data-bind="school_name"]').forEach(function (n) { n.textContent = f.school_name || ''; });
            var addr = activeEl.querySelector('[data-bind="address"]');
            if (addr) addr.innerHTML = esc(f.address || '').replace(/\n/g, '<br>');
            var phone = activeEl.querySelector('[data-bind="phone"]');
            if (phone) { phone.textContent = f.phone || ''; phone.setAttribute('href', 'tel:' + String(f.phone || '').replace(/\s+/g, '')); }
            var email = activeEl.querySelector('[data-bind="email"]');
            if (email) { email.textContent = f.email || ''; email.setAttribute('href', 'mailto:' + (f.email || '')); }
            document.querySelectorAll('[data-edit-block="cta_text"]').forEach(function (cta) {
                var raw = cta.getAttribute('data-value') || '';
                var resolved = resolveContentPlaceholders(raw, f.school_name || '');
                cta.setAttribute('data-display', resolved);
                var ctaBind = cta.querySelector('[data-bind="text"]');
                if (ctaBind) ctaBind.textContent = resolved;
            });
            return;
        }
        if (block === 'quick_link') {
            activeEl.setAttribute('data-title', f.title || '');
            activeEl.setAttribute('data-subtitle', f.subtitle || '');
            activeEl.setAttribute('data-href', f.href || '#');
            activeEl.setAttribute('data-icon', f.icon || 'bi-link-45deg');
            activeEl.setAttribute('data-external', f.external ? '1' : '0');
            var link = activeEl.querySelector('a.home-quick-link');
            if (link) {
                link.setAttribute('href', f.href || '#');
                if (f.external) {
                    link.setAttribute('target', '_blank');
                    link.setAttribute('rel', 'noopener noreferrer');
                } else {
                    link.removeAttribute('target');
                    link.removeAttribute('rel');
                }
            }
            var titleNode = activeEl.querySelector('.home-quick-link__title');
            if (titleNode) titleNode.textContent = f.title || '';
            var subNode = activeEl.querySelector('.home-quick-link__subtitle');
            if (subNode) subNode.textContent = f.subtitle || '';
            var iconNode = activeEl.querySelector('.home-quick-link__icon i');
            if (iconNode) iconNode.className = 'bi ' + (f.icon || 'bi-link-45deg');
            return;
        }
        if (block === 'news_item') {
            activeEl.setAttribute('data-title', f.title || '');
            activeEl.setAttribute('data-content', f.content || '');
            var t = activeEl.querySelector('[data-bind="news_title"]');
            if (t) t.textContent = f.title || '';
            var c = activeEl.querySelector('[data-bind="news_content"]');
            if (c) {
                c.innerHTML = String(f.content || '').split(/\n\n+/).map(function (p) {
                    return '<p>' + esc(p).replace(/\n/g, '<br>') + '</p>';
                }).join('');
            }
            return;
        }
        if (block === 'cuti_kumpulan') {
            activeEl.setAttribute('data-kumpulan-a', f.kumpulan_a || '');
            activeEl.setAttribute('data-kumpulan-b', f.kumpulan_b || '');
            var ck = activeEl.querySelector('[data-bind="cuti_kumpulan"]');
            if (ck) {
                ck.innerHTML = f.html || (
                    '<strong>Kumpulan A:</strong> ' + esc(f.kumpulan_a || '')
                    + '<br><strong>Kumpulan B:</strong> ' + esc(f.kumpulan_b || '')
                );
            }
            return;
        }
        if (block === 'fpk_falsafah') {
            activeEl.setAttribute('data-title', f.title || '');
            activeEl.setAttribute('data-content', f.content || '');
            var fft = activeEl.querySelector('[data-bind="fpk_falsafah_title"]');
            if (fft) fft.textContent = f.title || '';
            var ffc = activeEl.querySelector('[data-bind="fpk_falsafah_content"]');
            if (ffc) {
                var paras = String(f.content || '').trim().split(/\n\s*\n/);
                ffc.innerHTML = paras.map(function (p) {
                    return '<p style="font-size: 1.1rem; line-height: 1.7;">' + esc(p.trim()).replace(/\n/g, '<br>') + '</p>';
                }).join('');
            }
            return;
        }
        if (block === 'fpk_item') {
            activeEl.setAttribute('data-content', f.content || '');
            activeEl.setAttribute('data-kategori', f.kategori || '');
            var fk = activeEl.querySelector('[data-bind="fpk_kategori"]');
            if (fk && f.kategori) fk.textContent = f.kategori;
            var fc = activeEl.querySelector('[data-bind="fpk_content"]');
            if (fc) fc.innerHTML = esc(f.content || '').replace(/\n/g, '<br>');
            return;
        }
        if (block === 'sejarah_item') {
            activeEl.setAttribute('data-tajuk', f.tajuk || '');
            activeEl.setAttribute('data-content', f.content || '');
            var st = activeEl.querySelector('[data-bind="sejarah_tajuk"]');
            if (st) st.textContent = f.tajuk || '';
            var sc = activeEl.querySelector('[data-bind="sejarah_content"]');
            if (sc) sc.innerHTML = esc(f.content || '').replace(/\n/g, '<br>');
            return;
        }
        if (block === 'pengetua_item') {
            var pn = activeEl.querySelector('[data-bind="pengetua_name"]');
            if (pn) pn.textContent = f.name || '';
            var py = activeEl.querySelector('[data-bind="pengetua_years"]');
            if (py) py.textContent = (f.start_year || '') + ' – ' + (f.end_year ? f.end_year : 'Kini');
            return;
        }
        if (block === 'pengurusan_item') {
            var n = activeEl.querySelector('[data-bind="pengurusan_nama"]');
            if (n) n.textContent = f.nama || '';
            var g = activeEl.querySelector('[data-bind="pengurusan_gred"]');
            if (g) g.textContent = f.gred || '';
            var j = activeEl.querySelector('[data-bind="pengurusan_jawatan"]');
            if (j) j.textContent = f.jawatan || '';
            return;
        }
        if (typeof f.value === 'string') {
            activeEl.setAttribute('data-value', f.value);
            var bind = activeEl.querySelector('[data-bind="text"]') || activeEl;
            if (block === 'cta_text') {
                var resolved = resolveContentPlaceholders(f.value);
                activeEl.setAttribute('data-display', resolved);
                bind.textContent = resolved;
            } else {
                bind.textContent = f.value;
            }
        }
    }

    function postForm(blockName) {
        syncRichTextEditor();
        var fd = new FormData(form);
        fd.set('block', blockName);
        if (!fd.has('external') && form.querySelector('[name="external"]')) {
            // unchecked checkbox omitted
        }
        setStatus('Menyimpan…', true);
        window.smks3AppendCsrf(fd);
        return fetch(API, { method: 'POST', body: fd, credentials: 'same-origin', headers: window.smks3WithCsrf({ 'Accept': 'application/json' }) })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); });
    }

    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-edit-block]');
        if (!el || !document.body.classList.contains('smks3-is-editor')) return;
        if (document.body.classList.contains('smks3-edit-preview')) return;

        // List/home news tiles: go to detail page to edit (avoid duplicate panel).
        var gotoUrl = el.getAttribute('data-edit-goto');
        if (gotoUrl) {
            e.preventDefault();
            e.stopPropagation();
            if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            location.href = gotoUrl;
            return;
        }

        // Let real nav/collapse controls inside an editable block work.
        // Slideshow slides still open the panel (poster edit), not follow links.
        var interactive = e.target.closest('a[href], button, input, select, textarea, label, summary, [data-bs-toggle], [data-bs-target]');
        if (interactive && interactive !== el && el.contains(interactive)) {
            var blockName = el.getAttribute('data-edit-block') || '';
            var linkOwnedBlocks = {
                slideshow_slide: 1
            };
            var isAddBlock = /_add$/.test(blockName);
            var isCarouselNav = interactive.hasAttribute('data-bs-slide')
                || interactive.hasAttribute('data-bs-slide-to')
                || interactive.closest('.carousel-control-prev, .carousel-control-next, .carousel-indicators');
            var isBootstrapUi = interactive.hasAttribute('data-bs-toggle') || interactive.hasAttribute('data-bs-target');
            var isFormField = interactive.matches('input, select, textarea, label');

            if (isCarouselNav || linkOwnedBlocks[blockName] || isAddBlock) {
                // open edit panel below
            } else if (isFormField || isBootstrapUi || interactive.matches('a[href]')) {
                return;
            }
            // Plain <button> inside an edit block (e.g. Tambah …) opens the panel.
        }
        e.preventDefault();
        e.stopPropagation();
        if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
        openPanel(el);
    }, true);

    document.getElementById('smks3EditClose').addEventListener('click', closePanel);
    document.getElementById('smks3EditCancel').addEventListener('click', closePanel);
    backdrop.addEventListener('click', closePanel);
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && panel.classList.contains('is-open')) closePanel();
    });

    document.getElementById('smks3EditSave').addEventListener('click', function () {
        if (!currentBlock) return;
        if (!form.reportValidity()) return;

        // Click-to-edit table cells (kalendar, cuti, etc.)
        if ((currentBlock === 'kalendar_cell' || currentBlock === 'table_cell') && activeEl) {
            var newVal = (form.querySelector('[name="value"]') || {}).value;
            if (newVal == null) newVal = '';
            newVal = String(newVal).trim();
            // Allow simple line breaks typed by teachers.
            if (newVal.indexOf('\n') !== -1) {
                activeEl.innerHTML = esc(newVal).replace(/\n/g, '<br>');
            } else {
                activeEl.textContent = newVal;
            }
            activeEl.setAttribute('data-value', newVal);
            var table = activeEl.closest('table');
            var wrap = activeEl.closest('[data-edit-table]');
            if (!table) {
                setStatus('Jadual tidak dijumpai.', false);
                return;
            }
            var tableKey = wrap ? (wrap.getAttribute('data-table-key') || 'kalendar_akademik') : 'kalendar_akademik';
            var tableStore = wrap ? (wrap.getAttribute('data-table-store') || 'pages') : 'pages';
            var fd = new FormData();
            fd.set('block', 'editable_table');
            fd.set('table_key', tableKey);
            fd.set('table_store', tableStore);
            fd.set('content', table.outerHTML);
            setStatus('Menyimpan…', true);
            window.smks3AppendCsrf(fd);
            fetch(API, { method: 'POST', body: fd, credentials: 'same-origin', headers: window.smks3WithCsrf({ 'Accept': 'application/json' }) })
                .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                .then(function (res) {
                    if (!res.ok || !res.j.ok) {
                        setStatus((res.j && res.j.error) || 'Gagal menyimpan.', false);
                        return;
                    }
                    if (window.smks3MarkEditableTables) window.smks3MarkEditableTables();
                    setStatus(res.j.message || 'Disimpan.', true);
                    setTimeout(closePanel, 600);
                })
                .catch(function () { setStatus('Ralat rangkaian. Cuba lagi.', false); });
            return;
        }

        if (currentBlock === 'list_item' && activeEl) {
            var listVal = (form.querySelector('[name="value"]') || {}).value;
            if (listVal == null) listVal = '';
            listVal = String(listVal).trim();
            activeEl.textContent = listVal;
            activeEl.setAttribute('data-value', listVal);
            var listWrap = activeEl.closest('[data-edit-list]');
            if (!listWrap) {
                setStatus('Senarai tidak dijumpai.', false);
                return;
            }
            var listKey = listWrap.getAttribute('data-list-key') || '';
            var fdList = new FormData();
            fdList.set('block', 'editable_html');
            fdList.set('content_key', listKey);
            fdList.set('content', listWrap.innerHTML);
            setStatus('Menyimpan…', true);
            window.smks3AppendCsrf(fdList);
            fetch(API, { method: 'POST', body: fdList, credentials: 'same-origin', headers: window.smks3WithCsrf({ 'Accept': 'application/json' }) })
                .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                .then(function (res) {
                    if (!res.ok || !res.j.ok) {
                        setStatus((res.j && res.j.error) || 'Gagal menyimpan.', false);
                        return;
                    }
                    if (window.smks3MarkEditableLists) window.smks3MarkEditableLists();
                    setStatus(res.j.message || 'Disimpan.', true);
                    setTimeout(closePanel, 600);
                })
                .catch(function () { setStatus('Ralat rangkaian. Cuba lagi.', false); });
            return;
        }

        if (currentBlock === 'html_text' && activeEl) {
            var textVal = (form.querySelector('[name="value"]') || {}).value;
            if (textVal == null) textVal = '';
            textVal = String(textVal).trim();
            activeEl.innerHTML = esc(textVal).replace(/\n/g, '<br>');
            activeEl.setAttribute('data-value', textVal);
            var htmlKey = activeEl.getAttribute('data-content-key') || '';
            var fdHtml = new FormData();
            fdHtml.set('block', 'editable_html');
            fdHtml.set('content_key', htmlKey);
            fdHtml.set('content', activeEl.innerHTML);
            setStatus('Menyimpan…', true);
            window.smks3AppendCsrf(fdHtml);
            fetch(API, { method: 'POST', body: fdHtml, credentials: 'same-origin', headers: window.smks3WithCsrf({ 'Accept': 'application/json' }) })
                .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                .then(function (res) {
                    if (!res.ok || !res.j.ok) {
                        setStatus((res.j && res.j.error) || 'Gagal menyimpan.', false);
                        return;
                    }
                    setStatus(res.j.message || 'Disimpan.', true);
                    setTimeout(closePanel, 600);
                })
                .catch(function () { setStatus('Ralat rangkaian. Cuba lagi.', false); });
            return;
        }

        if (currentBlock === 'kalendar_title') {
            var titleVal = (form.querySelector('[name="value"]') || {}).value || '';
            var fdTitle = new FormData();
            fdTitle.set('block', 'kalendar_title');
            fdTitle.set('value', titleVal);
            setStatus('Menyimpan…', true);
            window.smks3AppendCsrf(fdTitle);
            fetch(API, { method: 'POST', body: fdTitle, credentials: 'same-origin', headers: window.smks3WithCsrf({ 'Accept': 'application/json' }) })
                .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
                .then(function (res) {
                    if (!res.ok || !res.j.ok) {
                        setStatus((res.j && res.j.error) || 'Gagal menyimpan.', false);
                        return;
                    }
                    if (activeEl) {
                        activeEl.setAttribute('data-value', titleVal);
                        var bind = activeEl.querySelector('[data-bind="text"]') || activeEl;
                        bind.textContent = titleVal;
                    }
                    setStatus(res.j.message || 'Disimpan.', true);
                    setTimeout(closePanel, 600);
                })
                .catch(function () { setStatus('Ralat rangkaian. Cuba lagi.', false); });
            return;
        }

        postForm(currentBlock).then(function (res) {
            if (!res.ok || !res.j.ok) {
                setStatus((res.j && res.j.error) || 'Gagal menyimpan.', false);
                return;
            }
            if (res.j.reload) {
                setStatus(res.j.message || 'Disimpan.', true);
                setTimeout(function () { location.reload(); }, 500);
                return;
            }
            if (res.j.redirect) {
                setStatus(res.j.message || 'Disimpan.', true);
                setTimeout(function () { location.href = res.j.redirect; }, 500);
                return;
            }
            applyToPage(res.j);
            setStatus(res.j.message || 'Disimpan.', true);
            setTimeout(closePanel, 700);
        }).catch(function () {
            setStatus('Ralat rangkaian. Cuba lagi.', false);
        });
    });

    deleteBtn.addEventListener('click', function () {
        if (!deleteBlock || !activeEl) return;
        if (!confirm('Padam item ini?')) return;
        var idx = activeEl.getAttribute('data-index') || '';
        var id = activeEl.getAttribute('data-id') || activeEl.getAttribute('data-news-id') || '';
        form.innerHTML = hidden('index', idx) + hidden('id', id);
        postForm(deleteBlock).then(function (res) {
            if (!res.ok || !res.j.ok) {
                setStatus((res.j && res.j.error) || 'Gagal memadam.', false);
                return;
            }
            setStatus(res.j.message || 'Dipadam.', true);
            setTimeout(function () {
                if (res.j.redirect) {
                    location.href = res.j.redirect;
                } else {
                    location.reload();
                }
            }, 500);
        }).catch(function () {
            setStatus('Ralat rangkaian. Cuba lagi.', false);
        });
    });

    // Auto-mark clickable table cells / list items on any page.
    function markEditableTables() {
        document.querySelectorAll('[data-edit-table] table td').forEach(function (td, i) {
            var val = (td.innerText || td.textContent || '').trim();
            td.setAttribute('data-edit-block', 'table_cell');
            td.setAttribute('data-edit-label', 'Ubah nilai jadual');
            td.setAttribute('data-edit-hint', 'Tulis nilai baharu untuk sel ini, kemudian Simpan.');
            td.setAttribute('data-value', val);
            td.setAttribute('data-cell-index', String(i));
        });
    }
    function markEditableLists() {
        document.querySelectorAll('[data-edit-list] li').forEach(function (li, i) {
            var val = (li.innerText || li.textContent || '').trim();
            li.setAttribute('data-edit-block', 'list_item');
            li.setAttribute('data-edit-label', 'Ubah catatan');
            li.setAttribute('data-edit-hint', 'Tulis catatan baharu, kemudian Simpan.');
            li.setAttribute('data-value', val);
            li.setAttribute('data-item-index', String(i));
        });
    }
    window.smks3MarkEditableTables = markEditableTables;
    window.smks3MarkEditableLists = markEditableLists;
    markEditableTables();
    markEditableLists();
})();
</script>
