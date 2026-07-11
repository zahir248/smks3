<?php
$meta = is_array($kurikulum_meta ?? null) ? $kurikulum_meta : ['intro' => '', 'sections' => []];
$sections = is_array($meta['sections'] ?? null) ? $meta['sections'] : [];
$pageKey = (string) ($kurikulum_page_key ?? 'pra-sekolah');
$cartaSec = is_array($sections['carta'] ?? null) ? $sections['carta'] : ['title' => 'Carta Organisasi Sekolah', 'subtitle' => ''];
$galeriSec = is_array($sections['galeri'] ?? null) ? $sections['galeri'] : ['title' => 'Galeri Murid', 'subtitle' => ''];
?>

<style>
.pra-image-edit {
    position: relative;
    display: inline-block;
    max-width: 1200px;
    width: 100%;
}
.pra-image-edit img {
    width: 100%;
    height: auto;
    border-radius: 0.5rem;
}
.pra-image-empty {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 2.5rem 1rem;
    color: #64748b;
    background: #f8fafc;
}
</style>

<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if ($is_editor): ?>
             data-edit-block="kurikulum_meta"
             data-edit-label="Sunting tajuk pra sekolah"
             data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
             data-intro="<?= htmlspecialchars((string) ($meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-sections="<?= htmlspecialchars(json_encode($sections, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold"><?= htmlspecialchars((string) ($cartaSec['title'] ?? 'Carta Organisasi Sekolah')) ?></h2>
            <?php if (trim((string) ($cartaSec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted"><?= htmlspecialchars((string) $cartaSec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <?php if ($carta): ?>
                <div class="pra-image-edit"
                     <?php if ($is_editor): ?>
                     data-edit-block="pra_sekolah_carta"
                     data-edit-label="Ganti gambar carta"
                     data-edit-hint="Pilih gambar baharu untuk carta organisasi."
                     <?php else: ?>
                     style="cursor:pointer;"
                     onclick="window.open(this.querySelector('img').src, '_blank')"
                     <?php endif; ?>>
                    <img src="uploads/pra_sekolah/<?= htmlspecialchars($carta) ?>"
                         alt="Carta Organisasi Sekolah"
                         class="img-fluid rounded shadow">
                </div>
            <?php elseif ($is_editor): ?>
                <div class="pra-image-empty"
                     data-edit-block="pra_sekolah_carta"
                     data-edit-label="Muat naik gambar carta"
                     data-edit-hint="Pilih gambar carta organisasi.">
                    <i class="bi bi-image d-block fs-2 mb-2"></i>
                    Tiada gambar carta. Klik untuk muat naik.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if ($is_editor): ?>
             data-edit-block="kurikulum_meta"
             data-edit-label="Sunting tajuk pra sekolah"
             data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
             data-intro="<?= htmlspecialchars((string) ($meta['intro'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-sections="<?= htmlspecialchars(json_encode($sections, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold"><?= htmlspecialchars((string) ($galeriSec['title'] ?? 'Galeri Murid')) ?></h2>
            <?php if (trim((string) ($galeriSec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted"><?= htmlspecialchars((string) $galeriSec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <div class="text-center">
            <?php if ($galeri): ?>
                <div class="pra-image-edit"
                     <?php if ($is_editor): ?>
                     data-edit-block="pra_sekolah_galeri"
                     data-edit-label="Ganti gambar galeri"
                     data-edit-hint="Pilih gambar baharu untuk galeri murid."
                     <?php else: ?>
                     style="cursor:pointer;"
                     onclick="window.open(this.querySelector('img').src, '_blank')"
                     <?php endif; ?>>
                    <img src="uploads/pra_sekolah/<?= htmlspecialchars($galeri) ?>"
                         alt="Galeri Murid"
                         class="img-fluid rounded shadow">
                </div>
            <?php elseif ($is_editor): ?>
                <div class="pra-image-empty"
                     data-edit-block="pra_sekolah_galeri"
                     data-edit-label="Muat naik gambar galeri"
                     data-edit-hint="Pilih gambar galeri murid.">
                    <i class="bi bi-image d-block fs-2 mb-2"></i>
                    Tiada gambar galeri. Klik untuk muat naik.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
