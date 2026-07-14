<?php
$is_editor = !empty($is_editor);
$pibg = is_array($pibg ?? null) ? $pibg : smks3_get_pibg_content();
$pdfSrc = smks3_pibg_pdf_src((string) ($pibg['pdf'] ?? ''));
$pdfExists = $pdfSrc !== '' && (preg_match('#^https?://#i', $pdfSrc) || is_file(BASE_PATH . '/' . $pdfSrc));
?>
<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if ($is_editor): ?>
             data-edit-block="pibg_meta"
             data-edit-label="Sunting tajuk PIBG"
             data-title="<?= htmlspecialchars((string) ($pibg['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($pibg['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-button-label="<?= htmlspecialchars((string) ($pibg['button_label'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold"><?= htmlspecialchars((string) ($pibg['title'] ?? 'Jawatankuasa PIBG')) ?></h2>
            <?php if (trim((string) ($pibg['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted"><?= htmlspecialchars((string) $pibg['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($pdfExists): ?>
            <div class="text-center mb-4">
                <?php if ($is_editor): ?>
                <button type="button" class="btn btn-outline-primary"
                        data-edit-block="pibg_pdf"
                        data-edit-label="Ganti PDF jawatankuasa PIBG"
                        data-edit-hint="Muat naik PDF baharu. Fail semasa akan diganti (bukan ditambah)."
                        data-file="<?= htmlspecialchars($pdfSrc, ENT_QUOTES, 'UTF-8') ?>">
                    <i class="bi bi-arrow-repeat me-1"></i> Ganti PDF
                </button>
                <p class="small text-muted mt-2 mb-0">Halaman ini hanya menyimpan satu PDF. Muat naik baharu akan menggantikan yang sedia ada.</p>
                <?php endif; ?>
            </div>

            <?php
            smks3_pdf_viewer($pdfSrc, [
                'id' => 'pibg-pdf-viewer',
                'label' => (string) ($pibg['button_label'] ?? 'Buka / Muat Turun PDF'),
                'btn_class' => 'btn btn-primary',
            ]);
            ?>
        <?php else: ?>
            <div class="text-center mb-4"
                 <?php if ($is_editor): ?>
                 data-edit-block="pibg_pdf"
                 data-edit-label="Muat naik PDF jawatankuasa PIBG"
                 <?php endif; ?>>
                <div class="alert alert-info mb-0">
                    Tiada PDF dimuat naik lagi<?= $is_editor ? '. Klik untuk muat naik.' : '.' ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
