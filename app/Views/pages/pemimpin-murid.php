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
        <div class="text-center mb-2"
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk: Barisan Pemimpin Murid"
             data-edit-hint="Tajuk bahagian barisan pemimpin sahaja."
             data-page-key="pemimpin-murid"
             data-section-key="main"
             data-title="<?= htmlspecialchars((string) ($mainSec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold mb-4" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($mainSec['title'] ?? 'Barisan Pemimpin Murid')) ?></h2>
        </div>
        <div class="text-center mb-3"
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_meta"
             data-edit-label="Sunting pengenalan"
             data-edit-hint="Teks pengenalan di bahagian atas sahaja."
             data-page-key="pemimpin-murid"
             data-intro="<?= htmlspecialchars((string) ($page_meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <p class="text-muted lead mb-0"><?= htmlspecialchars((string) ($page_meta['intro'] ?? '')) ?></p>
        </div>

        <div class="text-center mb-4">
            <a href="#info" class="btn btn-outline-primary btn-sm">Maklumat Lanjut</a>
        </div>

        <div class="text-center">
            <?php
            $db_images = is_array($db_images ?? null) ? $db_images : [];
            $pemimpinSrcs = [];
            foreach ($db_images as $img) {
                $src = 'uploads/pemimpin_murid/' . ($img['image'] ?? '');
                if (is_file(BASE_PATH . '/' . $src)) {
                    $pemimpinSrcs[] = $src;
                }
            }
            $pemimpinJson = json_encode($pemimpinSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            ?>
            <?php if ($pemimpinSrcs !== []) : ?>
                <div class="pemimpin-gallery"
                     <?php if ($is_editor): ?>
                     data-edit-block="pemimpin_gallery"
                     data-edit-label="Urus gambar pemimpin murid"
                     data-edit-hint="Tambah, buang, atau susun semula semua gambar dalam satu panel."
                     data-images-json="<?= htmlspecialchars($pemimpinJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                    <?php foreach ($pemimpinSrcs as $src): ?>
                    <div class="mb-4">
                        <img
                            src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                            alt="Barisan Pemimpin Murid"
                            class="img-fluid rounded shadow pemimpin-photo"
                            <?php if (!$is_editor): ?>
                            style="cursor:pointer;"
                            onclick="window.open(this.src, '_blank')"
                            <?php endif; ?>>
                    </div>
                    <?php endforeach; ?>
                </div>
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
                        data-edit-block="pemimpin_gallery"
                        data-edit-label="Urus gambar pemimpin murid"
                        data-edit-hint="Tambah, buang, atau susun semula semua gambar dalam satu panel."
                        data-images-json="<?= htmlspecialchars($pemimpinJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>">
                    <i class="bi bi-images me-1"></i> Urus Gambar
                </button>
                <p class="small text-muted mt-2 mb-0">Satu panel untuk semua gambar: muat naik berbilang, buang, dan susun semula.</p>
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
        <div class="text-center mb-5"
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk: Maklumat Berkaitan"
             data-edit-hint="Tajuk bahagian maklumat berkaitan sahaja."
             data-page-key="pemimpin-murid"
             data-section-key="info"
             data-title="<?= htmlspecialchars((string) ($infoSec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($infoSec['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h3 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($infoSec['title'] ?? 'Maklumat Berkaitan')) ?></h3>
            <?php if (trim((string) ($infoSec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $infoSec['subtitle']) ?></p>
            <?php endif; ?>
        </div>
        <?php
        $kurikulum_page_key = 'pemimpin-murid';
        $section_key = 'info';
        $cards = $infoCards;
        $col_class = 'col-md-4';
        $row_class = 'row g-4 justify-content-center';
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
