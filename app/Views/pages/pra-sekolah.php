<?php
$meta = is_array($kurikulum_meta ?? null) ? $kurikulum_meta : ['intro' => '', 'sections' => []];
$sections = is_array($meta['sections'] ?? null) ? $meta['sections'] : [];
$pageKey = (string) ($kurikulum_page_key ?? 'pra-sekolah');
$cartaSec = is_array($sections['carta'] ?? null) ? $sections['carta'] : ['title' => 'Carta Organisasi Sekolah', 'subtitle' => ''];
$galeriSec = is_array($sections['galeri'] ?? null) ? $sections['galeri'] : ['title' => 'Galeri Murid', 'subtitle' => ''];
$cartaImages = is_array($cartaImages ?? null) ? array_values($cartaImages) : [];
$galeriImages = is_array($galeriImages ?? null) ? array_values($galeriImages) : [];
if ($cartaImages === [] && !empty($carta)) {
    $cartaImages = [(string) $carta];
}
if ($galeriImages === [] && !empty($galeri)) {
    $galeriImages = [(string) $galeri];
}
$cartaJson = json_encode($cartaImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$galeriJson = json_encode($galeriImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>

<style>
.pra-gallery {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.25rem;
    max-width: 1200px;
    margin-inline: auto;
}
.pra-gallery__item {
    display: block;
    width: 100%;
    margin: 0;
    text-align: center;
}
.pra-gallery__item img {
    display: block;
    width: 100%;
    max-width: 100%;
    height: auto;
    margin-inline: auto;
    border-radius: 0.5rem;
    cursor: pointer;
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
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk: Carta Organisasi"
             data-edit-hint="Tajuk dan subtajuk untuk bahagian carta sahaja."
             data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
             data-section-key="carta"
             data-title="<?= htmlspecialchars((string) ($cartaSec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($cartaSec['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($cartaSec['title'] ?? 'Carta Organisasi Sekolah')) ?></h2>
            <?php if (trim((string) ($cartaSec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $cartaSec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($cartaImages !== []): ?>
            <div class="pra-gallery"
                 <?php if ($is_editor): ?>
                 data-edit-block="pra_sekolah_carta"
                 data-edit-label="Urus gambar carta"
                 data-edit-hint="Muat naik satu atau lebih gambar. Gambar baharu ditambah tanpa menggantikan yang lama."
                 data-images-json="<?= htmlspecialchars($cartaJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <?php foreach ($cartaImages as $idx => $src): ?>
                <figure class="pra-gallery__item">
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                         alt="Carta Organisasi Sekolah<?= count($cartaImages) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                         class="img-fluid rounded shadow"
                         loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                         decoding="async"
                         <?php if (!$is_editor): ?>
                         style="cursor:pointer;"
                         onclick="window.open(this.src, '_blank')"
                         <?php endif; ?>>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php if ($is_editor): ?>
            <p class="text-center small text-muted mt-3 mb-0">Boleh ada lebih dari satu gambar. Muat naik baharu tidak menggantikan yang lama.</p>
            <?php endif; ?>
        <?php elseif ($is_editor): ?>
            <div class="text-center">
                <div class="pra-image-empty"
                     data-edit-block="pra_sekolah_carta"
                     data-edit-label="Muat naik gambar carta"
                     data-edit-hint="Pilih satu atau lebih gambar carta organisasi."
                     data-images-json="[]">
                    <i class="bi bi-image d-block fs-2 mb-2"></i>
                    Tiada gambar carta. Klik untuk muat naik.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if ($is_editor): ?>
             data-edit-block="kurikulum_section"
             data-edit-label="Sunting tajuk: Galeri Murid"
             data-edit-hint="Tajuk dan subtajuk untuk bahagian galeri sahaja."
             data-page-key="<?= htmlspecialchars($pageKey, ENT_QUOTES, 'UTF-8') ?>"
             data-section-key="galeri"
             data-title="<?= htmlspecialchars((string) ($galeriSec['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($galeriSec['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold" data-bind="kurikulum_section_title"><?= htmlspecialchars((string) ($galeriSec['title'] ?? 'Galeri Murid')) ?></h2>
            <?php if (trim((string) ($galeriSec['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted" data-bind="kurikulum_section_subtitle"><?= htmlspecialchars((string) $galeriSec['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($galeriImages !== []): ?>
            <div class="pra-gallery"
                 <?php if ($is_editor): ?>
                 data-edit-block="pra_sekolah_galeri"
                 data-edit-label="Urus gambar galeri"
                 data-edit-hint="Muat naik satu atau lebih gambar. Gambar baharu ditambah tanpa menggantikan yang lama."
                 data-images-json="<?= htmlspecialchars($galeriJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <?php foreach ($galeriImages as $idx => $src): ?>
                <figure class="pra-gallery__item">
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                         alt="Galeri Murid<?= count($galeriImages) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                         class="img-fluid rounded shadow"
                         loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                         decoding="async"
                         <?php if (!$is_editor): ?>
                         style="cursor:pointer;"
                         onclick="window.open(this.src, '_blank')"
                         <?php endif; ?>>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php if ($is_editor): ?>
            <p class="text-center small text-muted mt-3 mb-0">Boleh ada lebih dari satu gambar. Muat naik baharu tidak menggantikan yang lama.</p>
            <?php endif; ?>
        <?php elseif ($is_editor): ?>
            <div class="text-center">
                <div class="pra-image-empty"
                     data-edit-block="pra_sekolah_galeri"
                     data-edit-label="Muat naik gambar galeri"
                     data-edit-hint="Pilih satu atau lebih gambar galeri murid."
                     data-images-json="[]">
                    <i class="bi bi-image d-block fs-2 mb-2"></i>
                    Tiada gambar galeri. Klik untuk muat naik.
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
