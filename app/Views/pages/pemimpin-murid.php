<?php
$page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('pemimpin-murid');
$mainSec = is_array($page_meta['sections']['main'] ?? null) ? $page_meta['sections']['main'] : ['title' => 'Barisan Pemimpin Murid', 'subtitle' => ''];
$infoSec = is_array($page_meta['sections']['info'] ?? null) ? $page_meta['sections']['info'] : ['title' => 'Maklumat Berkaitan', 'subtitle' => ''];
$infoCards = is_array($kurikulum_by_section['info'] ?? null) ? $kurikulum_by_section['info'] : [];
?>
<style>
.pemimpin-info-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}
.pemimpin-info-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}
.pemimpin-photo {
    max-width: 1000px;
    width: 100%;
    cursor: pointer;
}
</style>

<section class="page-section" id="barisan">
    <div class="container">
        <div
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_meta"
             data-edit-label="Sunting tajuk pemimpin murid"
             data-page-key="pemimpin-murid"
             data-intro="<?= htmlspecialchars((string) ($page_meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-sections="<?= htmlspecialchars(json_encode($page_meta['sections'] ?? [], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold text-center mb-4"><?= htmlspecialchars((string) ($mainSec['title'] ?? 'Barisan Pemimpin Murid')) ?></h2>
            <p class="text-center text-muted lead mb-3"><?= htmlspecialchars((string) ($page_meta['intro'] ?? '')) ?></p>
        </div>

        <div class="text-center mb-4">
            <a href="#info" class="btn btn-outline-primary btn-sm">Maklumat Lanjut</a>
        </div>

        <div class="text-center">
            <?php if (!empty($db_images)) : ?>
                <?php foreach ($db_images as $img) :
                    $src = 'uploads/pemimpin_murid/' . ($img['image'] ?? '');
                    if (!is_file(BASE_PATH . '/' . $src)) {
                        continue;
                    }
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="d-inline-block mb-4"
                     <?php if ($is_editor): ?>
                     data-edit-block="pemimpin_item"
                     data-edit-label="Pemimpin murid"
                     data-edit-hint="Guna Padam untuk buang gambar ini."
                     data-id="<?= (int) $img['id'] ?>"
                     <?php endif; ?>>
                <img
                    src="<?= $srcEsc ?>"
                    alt="Barisan Pemimpin Murid"
                    class="img-fluid rounded shadow pemimpin-photo"
                    onclick="window.open(this.src, '_blank')">
                </div>
                <?php endforeach; ?>
            <?php elseif (!empty($static_image)) : ?>
                <a href="<?= htmlspecialchars($static_image, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    <img
                        src="<?= htmlspecialchars($static_image, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Barisan Pemimpin Murid"
                        class="img-fluid rounded shadow pemimpin-photo">
                </a>
            <?php endif; ?>

            <?php if ($is_editor): ?>
            <div class="mt-3 mb-3">
                <button type="button" class="btn btn-outline-primary"
                        data-edit-block="pemimpin_add"
                        data-edit-label="Tambah pemimpin murid"
                        data-edit-hint="Muat naik gambar barisan pemimpin murid.">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Gambar
                </button>
            </div>
            <?php endif; ?>

            <p class="text-muted mt-3">
                Klik pada gambar untuk paparan lebih jelas.
            </p>
        </div>
    </div>
</section>

<section class="page-section" id="info">
    <div class="container">
        <h3 class="fw-bold text-center mb-5"><?= htmlspecialchars((string) ($infoSec['title'] ?? 'Maklumat Berkaitan')) ?></h3>
        <?php
        $kurikulum_page_key = 'pemimpin-murid';
        $section_key = 'info';
        $cards = $infoCards;
        $col_class = 'col-md-4';
        $row_class = 'row g-4';
        smks3_view_include(VIEW_PATH . '/partials/kurikulum-cards.php', compact(
            'is_editor',
            'kurikulum_page_key',
            'cards',
            'section_key',
            'col_class',
            'row_class'
        ));
        ?>
    </div>
</section>
