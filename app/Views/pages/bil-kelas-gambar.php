<?php
$is_editor = !empty($is_editor);
$data = $pdo->query('
    SELECT *
    FROM bilangan_kelas
    ORDER BY tingkatan, id DESC
')->fetchAll();

$group = [];
foreach ($data as $d) {
    $group[$d['tingkatan']][] = $d;
}
?>

<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1);
}
</style>

<section class="page-section">
<div class="container">

<?php
$page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('bil-kelas-gambar');
$sec = is_array($page_meta['sections']['main'] ?? null) ? $page_meta['sections']['main'] : ['title' => 'Bilangan Kelas (Gambar)', 'subtitle' => 'Susunan kelas mengikut tingkatan'];
?>
<div class="text-center mb-5"
     <?php if ($is_editor): ?>
     data-edit-block="kurikulum_meta"
     data-edit-label="Sunting tajuk bilangan kelas"
     data-page-key="bil-kelas-gambar"
     data-intro="<?= htmlspecialchars((string) ($page_meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
     data-sections="<?= htmlspecialchars(json_encode($page_meta['sections'] ?? [], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
     <?php endif; ?>>
    <h2 class="fw-bold"><?= htmlspecialchars((string) ($sec['title'] ?? 'Bilangan Kelas (Gambar)')) ?></h2>
    <?php if (trim((string) ($sec['subtitle'] ?? '')) !== ''): ?>
        <p class="text-muted"><?= htmlspecialchars((string) $sec['subtitle']) ?></p>
    <?php endif; ?>
</div>

<?php if (!$group) : ?>
    <div class="text-center text-muted">
        Tiada data bilangan kelas.
    </div>
<?php endif; ?>

<?php foreach ($group as $tingkatan => $items) :
    $carouselId = 'carousel-' . md5((string) $tingkatan);
?>

<div class="mb-5">
    <h3 class="fw-bold mb-4 text-primary">
        <?= htmlspecialchars((string) $tingkatan) ?>
    </h3>

    <div id="<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
         class="carousel slide"
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

        <?php if (!$is_editor): ?>
        <button class="carousel-control-prev" type="button"
                data-bs-target="#<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#<?= htmlspecialchars($carouselId, ENT_QUOTES, 'UTF-8') ?>"
                data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
        <?php endif; ?>
    </div>
</div>

<?php endforeach; ?>

<?php if ($is_editor): ?>
<div class="text-center mb-4">
    <button type="button" class="btn btn-outline-primary"
            data-edit-block="bil_kelas_add"
            data-edit-label="Tambah bilangan kelas"
            data-edit-hint="Muat naik gambar kelas mengikut tingkatan.">
        <i class="bi bi-plus-lg me-1"></i> Tambah Gambar
    </button>
</div>
<?php endif; ?>

</div>
</section>
