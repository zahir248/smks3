<?php
$is_editor = !empty($is_editor);
smks3_ensure_bilangan_kelas_sort($pdo);
$data = $pdo->query('
    SELECT *
    FROM bilangan_kelas
    ORDER BY sort_order ASC, tingkatan ASC, id DESC
')->fetchAll();

$group = [];
foreach ($data as $d) {
    $group[$d['tingkatan']][] = $d;
}
?>

<style>
.bil-kelas-carousel {
    position: relative;
}
.bil-kelas-carousel .carousel-control-prev,
.bil-kelas-carousel .carousel-control-next {
    width: 2.75rem;
    height: 2.75rem;
    top: 50%;
    transform: translateY(-50%);
    bottom: auto;
    opacity: 0;
    visibility: hidden;
    border: 0;
    border-radius: 999px;
    background: #0b2a4a;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(11, 42, 74, 0.28);
    transition: background-color 0.2s ease, opacity 0.2s ease, visibility 0.2s ease;
}
.bil-kelas-group:hover .bil-kelas-carousel .carousel-control-prev,
.bil-kelas-group:hover .bil-kelas-carousel .carousel-control-next,
.bil-kelas-group:has(:focus-visible) .bil-kelas-carousel .carousel-control-prev,
.bil-kelas-group:has(:focus-visible) .bil-kelas-carousel .carousel-control-next {
    opacity: 1;
    visibility: visible;
}
.bil-kelas-carousel .carousel-control-prev {
    left: 0.5rem;
}
.bil-kelas-carousel .carousel-control-next {
    right: 0.5rem;
}
.bil-kelas-carousel .carousel-control-prev:hover,
.bil-kelas-carousel .carousel-control-next:hover,
.bil-kelas-carousel .carousel-control-prev:focus-visible,
.bil-kelas-carousel .carousel-control-next:focus-visible {
    background: #143a63;
    color: #fff;
    opacity: 1;
    visibility: visible;
}
.bil-kelas-carousel .carousel-control-prev:focus,
.bil-kelas-carousel .carousel-control-next:focus {
    outline: none;
    box-shadow: none;
}
.bil-kelas-carousel .carousel-control-prev i,
.bil-kelas-carousel .carousel-control-next i {
    font-size: 1.35rem;
    line-height: 1;
}
@media (max-width: 576px) {
    .bil-kelas-carousel .carousel-control-prev,
    .bil-kelas-carousel .carousel-control-next {
        width: 2.35rem;
        height: 2.35rem;
    }
}
@media (hover: none) {
    .bil-kelas-carousel .carousel-control-prev,
    .bil-kelas-carousel .carousel-control-next {
        opacity: 0.9;
        visibility: visible;
    }
}
.bil-kelas-filter {
    max-width: 22rem;
    margin: 0 auto 2rem;
}
.bil-kelas-filter .form-label {
    font-weight: 600;
    margin-bottom: 0.35rem;
}
.bil-kelas-group.is-hidden {
    display: none !important;
}
</style>

<section class="page-section">
<div class="container">

<?php
$page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('bil-kelas-gambar');
$sec = is_array($page_meta['sections']['main'] ?? null) ? $page_meta['sections']['main'] : ['title' => 'Bilangan Kelas (Gambar)', 'subtitle' => 'Susunan kelas mengikut tingkatan'];
$tingkatanOptions = array_keys($group);
?>
<div class="text-center mb-4"
     <?php if ($is_editor): ?>
     data-edit-block="kurikulum_section"
     data-edit-label="Sunting tajuk bilangan kelas"
     data-edit-hint="Tajuk halaman ini sahaja."
     data-page-key="bil-kelas-gambar"
     data-section-key="main"
     data-title="<?= htmlspecialchars((string) ($sec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
     <?php endif; ?>>
    <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($sec['title'] ?? 'Bilangan Kelas (Gambar)')) ?></h2>
</div>

<?php if ($group): ?>
<div class="bil-kelas-filter">
    <label class="form-label" for="bilKelasTingkatanFilter">Tapis tingkatan</label>
    <select id="bilKelasTingkatanFilter" class="form-select" aria-label="Tapis mengikut tingkatan">
        <option value="">Semua tingkatan</option>
        <?php foreach ($tingkatanOptions as $opt): ?>
        <option value="<?= htmlspecialchars((string) $opt, ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars((string) $opt) ?>
        </option>
        <?php endforeach; ?>
    </select>
</div>
<?php endif; ?>

<?php if (!$group && empty($is_editor)) : ?>
    <div class="text-center text-muted">
        Tiada data bilangan kelas.
    </div>
<?php endif; ?>

<?php foreach ($group as $tingkatan => $items) :
    $carouselId = 'carousel-' . md5((string) $tingkatan);
    $slideCount = count($items);
?>

<div class="mb-5 bil-kelas-group"
     data-tingkatan="<?= htmlspecialchars((string) $tingkatan, ENT_QUOTES, 'UTF-8') ?>">
    <h3 class="fw-bold mb-4 text-primary">
        <?= htmlspecialchars((string) $tingkatan) ?>
    </h3>

    <div id="<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
         class="carousel slide bil-kelas-carousel"
         <?= $is_editor ? 'data-bs-interval="false"' : 'data-bs-ride="carousel"' ?>>
        <div class="carousel-inner">
            <?php foreach ($items as $index => $item) : ?>
            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                <div class="text-center"
                     <?php if ($is_editor): ?>
                     data-edit-block="bil_kelas_item"
                     data-edit-label="Sunting bilangan kelas"
                     data-edit-hint="Kemaskini tingkatan, tajuk atau gambar. Guna Padam untuk buang."
                     data-id="<?= (int) $item['id'] ?>"
                     data-tingkatan="<?= htmlspecialchars((string) ($item['tingkatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     data-title="<?= htmlspecialchars((string) ($item['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                    <img src="uploads/bil_kelas/<?= htmlspecialchars((string) $item['image']) ?>"
                         class="img-fluid rounded shadow"
                         style="max-height:420px;object-fit:contain;<?= $is_editor ? '' : 'cursor:pointer;' ?>"
                         <?php if (!$is_editor): ?>
                         onclick="smks3OpenMediaOverlay(this.src)"
                         <?php endif; ?>
                         alt="<?= htmlspecialchars((string) $item['title']) ?>">
                    <h5 class="mt-3 fw-bold">
                        <?= htmlspecialchars((string) $item['title']) ?>
                    </h5>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($slideCount > 1): ?>
        <button class="carousel-control-prev" type="button"
                data-bs-target="#<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
                data-bs-slide="prev"
                aria-label="Gambar sebelumnya">
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
                data-bs-slide="next"
                aria-label="Gambar seterusnya">
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </button>
        <?php endif; ?>
    </div>

    <?php if ($is_editor): ?>
    <div class="text-center mt-3">
        <button type="button" class="btn btn-outline-primary btn-sm"
                data-edit-block="bil_kelas_add"
                data-edit-label="Tambah gambar: <?= htmlspecialchars((string) $tingkatan, ENT_QUOTES, 'UTF-8') ?>"
                data-edit-hint="Tambah gambar baharu untuk tingkatan ini sahaja."
                data-tingkatan="<?= htmlspecialchars((string) $tingkatan, ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-plus-lg me-1"></i> Tambah Gambar
        </button>
    </div>
    <?php endif; ?>
</div>

<?php endforeach; ?>

<?php if ($is_editor): ?>
<div class="text-center mb-4 <?= $group ? 'mt-2' : '' ?>">
    <?php if (!$group): ?>
    <p class="text-muted mb-3">Tiada data bilangan kelas.</p>
    <?php endif; ?>
    <button type="button" class="btn btn-outline-secondary btn-sm"
            data-edit-block="bil_kelas_add"
            data-edit-label="Tambah tingkatan baharu"
            data-edit-hint="Cipta tingkatan baharu, pilih kedudukan paparan, lalu muat naik gambar pertama."
            data-tingkatan-options="<?= htmlspecialchars(json_encode($tingkatanOptions, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>">
        <i class="bi bi-plus-lg me-1"></i> Tambah Tingkatan Baharu
    </button>
    <p class="small text-muted mt-2 mb-0">Guna butang ini hanya untuk tingkatan yang belum wujud. Pilih kedudukan (awal / selepas / akhir). Untuk tingkatan sedia ada, guna “Tambah Gambar” di bahagian masing-masing.</p>
</div>
<?php endif; ?>

</div>
</section>

<?php if ($group): ?>
<script>
(function () {
    var filter = document.getElementById('bilKelasTingkatanFilter');
    if (!filter) return;
    var groups = document.querySelectorAll('.bil-kelas-group');
    function applyFilter() {
        var selected = String(filter.value || '');
        groups.forEach(function (group) {
            var value = group.getAttribute('data-tingkatan') || '';
            var show = selected === '' || value === selected;
            group.classList.toggle('is-hidden', !show);
        });
    }
    filter.addEventListener('change', applyFilter);
})();
</script>
<?php endif; ?>
