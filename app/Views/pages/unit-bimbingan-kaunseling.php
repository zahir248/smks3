<?php
$ubk = is_array($ubk ?? null) ? $ubk : smks3_get_ubk_content();
$is_editor = !empty($is_editor);
?>

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
        $cartaSrc = smks3_ubk_img_src((string) ($ubk['carta_image'] ?? ''));
        $cartaExists = $cartaSrc !== '' && (preg_match('#^https?://#i', $cartaSrc) || is_file(BASE_PATH . '/' . $cartaSrc));
        ?>
        <div class="text-center"
             <?php if ($is_editor): ?>
             data-edit-block="ubk_carta_image"
             data-edit-label="Ganti carta organisasi UBK"
             <?php endif; ?>>
            <?php if ($cartaExists): ?>
                <a href="<?= htmlspecialchars($cartaSrc, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?= htmlspecialchars($cartaSrc, ENT_QUOTES, 'UTF-8') ?>" alt="Carta Organisasi UBK" class="img-fluid rounded shadow">
                </a>
            <?php elseif ($is_editor): ?>
                <p class="text-muted mb-0">Tiada gambar carta. Klik untuk muat naik.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Proses / Pamplet -->
<section class="page-section" id="proses">
    <div class="container">
        <h3 class="fw-bold text-center mb-4">Proses Perkhidmatan Bimbingan & Kaunseling/Komponen Perkhidmatan</h3>
        <?php foreach (['pamplet1_image' => 'ubk_pamplet1', 'pamplet2_image' => 'ubk_pamplet2'] as $key => $block):
            $src = smks3_ubk_img_src((string) ($ubk[$key] ?? ''));
            $exists = $src !== '' && (preg_match('#^https?://#i', $src) || is_file(BASE_PATH . '/' . $src));
        ?>
        <div class="text-center mb-4"
             <?php if ($is_editor): ?>
             data-edit-block="<?= htmlspecialchars($block, ENT_QUOTES, 'UTF-8') ?>"
             data-edit-label="Ganti gambar pamplet"
             <?php endif; ?>>
            <?php if ($exists): ?>
                <a href="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    <img src="<?= htmlspecialchars($src, ENT_QUOTES, 'UTF-8') ?>" alt="Pamplet UBK" class="img-fluid rounded shadow">
                </a>
            <?php elseif ($is_editor): ?>
                <p class="text-muted mb-0">Tiada gambar. Klik untuk muat naik.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
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
