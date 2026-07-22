<?php
$ubk = is_array($ubk ?? null) ? $ubk : smks3_get_ubk_content();
$is_editor = !empty($is_editor);
?>
<style>
.ubk-gallery {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    max-width: 1000px;
    margin-inline: auto;
}
.ubk-gallery__item {
    display: block;
    width: 100%;
    margin: 0;
    text-align: center;
}
.ubk-gallery__item a {
    display: block;
    width: 100%;
}
.ubk-gallery__item img {
    display: block;
    width: 100%;
    max-width: 100%;
    height: auto;
    margin-inline: auto;
}
.ubk-image-empty {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 2rem 1rem;
    background: #f8fafc;
}
</style>

<!-- Pengenalan -->
<section class="page-section" id="pengenalan">
    <div class="container"
         <?php if ($is_editor): ?>
         data-edit-block="ubk_pengenalan"
         data-edit-label="Sunting pengenalan UBK"
         data-lead="<?= htmlspecialchars((string) ($ubk['lead'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         data-title="<?= htmlspecialchars((string) ($ubk['pengenalan_title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         data-body="<?= htmlspecialchars((string) ($ubk['pengenalan_body'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
        <p class="text-center text-muted lead mb-3" data-bind="ubk_lead"><?= htmlspecialchars((string) ($ubk['lead'] ?? '')) ?></p>
        <div class="text-center mb-4">
            <a href="#visi-misi" class="btn btn-outline-primary btn-sm me-2">Visi &amp; Misi</a>
            <a href="contact" class="btn btn-outline-primary btn-sm">Hubungi Kami</a>
        </div>
        <div class="text-center mb-5">
            <h2 class="fw-bold" data-bind="ubk_pengenalan_title"><?= htmlspecialchars((string) ($ubk['pengenalan_title'] ?? '')) ?></h2>
        </div>
        <p data-bind="ubk_pengenalan_body"><?= nl2br(htmlspecialchars((string) ($ubk['pengenalan_body'] ?? ''))) ?></p>
    </div>
</section>

<!-- Visi & Misi -->
<section class="page-section" id="visi-misi">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6"
                 <?php if ($is_editor): ?>
                 data-edit-block="ubk_visi"
                 data-edit-label="Sunting visi UBK"
                 data-value="<?= htmlspecialchars((string) ($ubk['visi'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <h3 class="fw-bold">Visi</h3>
                <p data-bind="ubk_visi"><?= nl2br(htmlspecialchars((string) ($ubk['visi'] ?? ''))) ?></p>
            </div>
            <div class="col-md-6"
                 <?php if ($is_editor): ?>
                 data-edit-block="ubk_misi"
                 data-edit-label="Sunting misi UBK"
                 data-value="<?= htmlspecialchars((string) ($ubk['misi'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <h3 class="fw-bold">Misi</h3>
                <p data-bind="ubk_misi"><?= nl2br(htmlspecialchars((string) ($ubk['misi'] ?? ''))) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Falsafah -->
<section class="page-section" id="falsafah"
         <?php if ($is_editor): ?>
         data-edit-block="ubk_falsafah"
         data-edit-label="Sunting falsafah UBK"
         data-value="<?= htmlspecialchars((string) ($ubk['falsafah'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Falsafah</h3>
        <p data-bind="ubk_falsafah"><?= nl2br(htmlspecialchars((string) ($ubk['falsafah'] ?? ''))) ?></p>
    </div>
</section>

<!-- Objektif -->
<section class="page-section" id="objektif"
         <?php if ($is_editor): ?>
         data-edit-block="ubk_objektif"
         data-edit-label="Sunting objektif UBK"
         data-edit-hint="Satu baris = satu objektif."
         data-value="<?= htmlspecialchars(smks3_format_lines_list($ubk['objektif'] ?? []), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Objektif / Tujuan</h3>
        <ul data-bind="ubk_objektif">
            <?php foreach (($ubk['objektif'] ?? []) as $item): ?>
                <li><?= htmlspecialchars((string) $item) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- Fungsi -->
<section class="page-section" id="fungsi"
         <?php if ($is_editor): ?>
         data-edit-block="ubk_fungsi"
         data-edit-label="Sunting fungsi unit UBK"
         data-edit-hint="Satu baris = satu fungsi."
         data-value="<?= htmlspecialchars(smks3_format_lines_list($ubk['fungsi'] ?? []), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Fungsi Unit Bimbingan & Kaunseling</h3>
        <ul data-bind="ubk_fungsi">
            <?php foreach (($ubk['fungsi'] ?? []) as $item): ?>
                <li><?= htmlspecialchars((string) $item) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<!-- Carta -->
<section class="page-section" id="carta">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Carta Organisasi</h3>
        <?php
        $cartaSrcs = smks3_ubk_img_srcs($ubk['carta_image'] ?? []);
        $cartaJson = json_encode($cartaSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        ?>
        <?php if ($cartaSrcs !== []): ?>
            <div class="ubk-gallery"
                 <?php if ($is_editor): ?>
                 data-edit-block="ubk_carta_image"
                 data-edit-label="Urus carta organisasi UBK"
                 data-edit-hint="Muat naik satu atau lebih gambar. Gambar baharu ditambah tanpa menggantikan yang lama."
                 data-images-json="<?= htmlspecialchars($cartaJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <?php foreach ($cartaSrcs as $idx => $cartaSrc): ?>
                <figure class="ubk-gallery__item">
                    <?php if ($is_editor): ?>
                        <img src="<?= htmlspecialchars($cartaSrc, ENT_QUOTES, 'UTF-8') ?>"
                             alt="Carta Organisasi UBK<?= count($cartaSrcs) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                             class="img-fluid rounded shadow"
                             loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                             decoding="async">
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($cartaSrc, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= htmlspecialchars($cartaSrc, ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Carta Organisasi UBK<?= count($cartaSrcs) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                                 class="img-fluid rounded shadow"
                                 loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                                 decoding="async">
                        </a>
                    <?php endif; ?>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php if ($is_editor): ?>
            <p class="text-center small text-muted mt-2 mb-0">Boleh ada lebih dari satu gambar. Muat naik baharu tidak menggantikan yang lama.</p>
            <?php endif; ?>
        <?php elseif ($is_editor): ?>
            <div class="text-center ubk-image-empty"
                 data-edit-block="ubk_carta_image"
                 data-edit-label="Muat naik carta organisasi UBK"
                 data-edit-hint="Pilih satu atau lebih gambar carta organisasi."
                 data-images-json="[]">
                <p class="text-muted mb-0">Tiada gambar carta. Klik untuk muat naik.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Proses / Pamplet -->
<section class="page-section" id="proses">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Proses Perkhidmatan Bimbingan & Kaunseling/Komponen Perkhidmatan</h3>
        <?php
        $pampletSrcs = smks3_ubk_img_srcs($ubk['pamplet_images'] ?? []);
        $pampletJson = json_encode($pampletSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        ?>
        <?php if ($pampletSrcs !== []): ?>
            <div class="ubk-gallery"
                 <?php if ($is_editor): ?>
                 data-edit-block="ubk_pamplet"
                 data-edit-label="Urus gambar proses / pamplet"
                 data-edit-hint="Muat naik, buang, atau susun semula gambar dalam satu panel."
                 data-images-json="<?= htmlspecialchars($pampletJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <?php foreach ($pampletSrcs as $idx => $src): ?>
                <figure class="ubk-gallery__item">
                    <?php if ($is_editor): ?>
                        <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                             alt="Pamplet UBK<?= count($pampletSrcs) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                             class="img-fluid rounded shadow"
                             loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                             decoding="async">
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>"
                                 alt="Pamplet UBK<?= count($pampletSrcs) > 1 ? ' (' . ($idx + 1) . ')' : '' ?>"
                                 class="img-fluid rounded shadow"
                                 loading="<?= $idx === 0 ? 'eager' : 'lazy' ?>"
                                 decoding="async">
                        </a>
                    <?php endif; ?>
                </figure>
                <?php endforeach; ?>
            </div>
            <?php if ($is_editor): ?>
            <p class="text-center small text-muted mt-2 mb-0">Satu panel untuk semua gambar. Guna anak panah dalam suntingan untuk susun semula.</p>
            <?php endif; ?>
        <?php elseif ($is_editor): ?>
            <div class="text-center ubk-image-empty"
                 data-edit-block="ubk_pamplet"
                 data-edit-label="Muat naik gambar proses / pamplet"
                 data-edit-hint="Pilih satu atau lebih gambar. Anda boleh susun semula kemudian."
                 data-images-json="[]">
                <p class="text-muted mb-0">Tiada gambar. Klik untuk muat naik.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Aktiviti -->
<section class="page-section" id="aktiviti"
         <?php if ($is_editor): ?>
         data-edit-block="ubk_aktiviti"
         data-edit-label="Sunting nota aktiviti UBK"
         data-value="<?= htmlspecialchars((string) ($ubk['aktiviti_note'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Aktiviti Unit Bimbingan & Kaunseling</h3>
        <p class="text-center" data-bind="ubk_aktiviti"><?= nl2br(htmlspecialchars((string) ($ubk['aktiviti_note'] ?? ''))) ?></p>
    </div>
</section>
