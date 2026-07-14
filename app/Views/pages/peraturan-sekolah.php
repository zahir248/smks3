<style>
.peraturan-img {
    width: 100%;
    max-width: 1000px;
    cursor: pointer;
}
</style>

<section class="page-section">
    <div class="container">
        <?php
        $page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('peraturan-sekolah');
        $sec = is_array($page_meta['sections']['main'] ?? null) ? $page_meta['sections']['main'] : ['title' => 'Peraturan Sekolah', 'subtitle' => ''];
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

        <div class="row justify-content-center g-4">
            <?php if (!empty($db_images)) : ?>
                <?php foreach ($db_images as $img) :
                    $src = 'uploads/peraturan/' . ($img['image'] ?? '');
                    if (!is_file(BASE_PATH . '/' . $src)) {
                        continue;
                    }
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col-12 text-center"
                     <?php if ($is_editor): ?>
                     data-edit-block="peraturan_item"
                     data-edit-label="Peraturan sekolah"
                     data-edit-hint="Guna Padam untuk buang gambar ini."
                     data-id="<?= (int) $img['id'] ?>"
                     <?php endif; ?>>
                    <img
                        src="<?= $srcEsc ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        onclick="smks3OpenMediaOverlay(this.src)">
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <?php foreach ($static_images as $src) :
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col-12 text-center">
                    <img
                        src="<?= $srcEsc ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        onclick="smks3OpenMediaOverlay(this.src)">
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($is_editor): ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="peraturan_add"
                    data-edit-label="Tambah peraturan"
                    data-edit-hint="Tambah gambar baharu. Gambar sedia ada kekal.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Gambar
            </button>
            <p class="small text-muted mt-2 mb-0">Boleh ada lebih dari satu gambar. Muat naik baharu tidak menggantikan yang lama.</p>
        </div>
        <?php endif; ?>

        <div class="alert alert-warning text-center mt-4">
            Maklumat lanjut akan dikemaskini dari semasa ke semasa.
        </div>
    </div>
</section>
