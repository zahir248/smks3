<style>
.peraturan-img {
    width: 100%;
    max-width: 1000px;
    cursor: pointer;
}
.peraturan-gallery {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
}
.peraturan-gallery__item {
    width: 100%;
    text-align: center;
    margin: 0;
}
</style>

<section class="page-section">
    <div class="container">
        <?php
        $page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('peraturan-sekolah');
        $sec = is_array($page_meta['sections']['main'] ?? null) ? $page_meta['sections']['main'] : ['title' => 'Peraturan Sekolah', 'subtitle' => ''];
        $db_images = is_array($db_images ?? null) ? $db_images : [];
        $gallerySrcs = [];
        foreach ($db_images as $img) {
            $src = 'uploads/peraturan/' . ($img['image'] ?? '');
            if (is_file(BASE_PATH . '/' . $src)) {
                $gallerySrcs[] = $src;
            }
        }
        $galleryJson = json_encode($gallerySrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        ?>
        <div class="text-center mb-5"
             <?php if (!empty($is_editor)): ?>
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk peraturan"
             data-edit-hint="Tajuk dan subtajuk halaman ini sahaja."
             data-page-key="peraturan-sekolah"
             data-section-key="main"
             data-title="<?= htmlspecialchars((string) ($sec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($sec['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($sec['title'] ?? 'Peraturan Sekolah')) ?></h2>
            <?php if (trim((string) ($sec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $sec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($gallerySrcs !== []): ?>
            <div class="peraturan-gallery"
                 <?php if (!empty($is_editor)): ?>
                 data-edit-block="peraturan_gallery"
                 data-edit-label="Urus gambar peraturan"
                 data-edit-hint="Tambah, buang, atau susun semula semua gambar dalam satu panel."
                 data-images-json="<?= htmlspecialchars($galleryJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <?php foreach ($gallerySrcs as $src): ?>
                <figure class="peraturan-gallery__item">
                    <img
                        src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        <?php if (empty($is_editor)): ?>
                        style="cursor:pointer;"
                        onclick="smks3OpenMediaOverlay(this.src)"
                        <?php endif; ?>>
                </figure>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($static_images)) : ?>
            <div class="peraturan-gallery">
                <?php foreach ($static_images as $src) : ?>
                <figure class="peraturan-gallery__item">
                    <img
                        src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        onclick="smks3OpenMediaOverlay(this.src)">
                </figure>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($is_editor)): ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="peraturan_gallery"
                    data-edit-label="Urus gambar peraturan"
                    data-edit-hint="Tambah, buang, atau susun semula semua gambar dalam satu panel."
                    data-images-json="<?= htmlspecialchars($galleryJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>">
                <i class="bi bi-images me-1"></i> Urus Gambar
            </button>
            <p class="small text-muted mt-2 mb-0">Satu panel untuk semua gambar: muat naik berbilang, buang, dan susun semula.</p>
        </div>
        <?php endif; ?>

        <div class="alert alert-warning text-center mt-4">
            Maklumat lanjut akan dikemaskini dari semasa ke semasa.
        </div>
    </div>
</section>
