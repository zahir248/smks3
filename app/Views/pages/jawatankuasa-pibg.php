<?php
$is_editor = !empty($is_editor);
$pibg = is_array($pibg ?? null) ? $pibg : smks3_get_pibg_content();
$pdfSrcs = smks3_pibg_pdf_srcs($pibg['pdfs'] ?? []);
$pdfJson = json_encode($pdfSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$btnLabel = (string) ($pibg['button_label'] ?? 'Buka / Muat Turun PDF');
?>
<section class="page-section">
    <div class="container">
        <div class="text-center mb-5"
             <?php if ($is_editor): ?>
             data-edit-block="pibg_meta"
             data-edit-label="Sunting tajuk PIBG"
             data-title="<?= htmlspecialchars((string) ($pibg['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-subtitle="<?= htmlspecialchars((string) ($pibg['subtitle'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
             data-button-label="<?= htmlspecialchars($btnLabel, ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="fw-bold"><?= htmlspecialchars((string) ($pibg['title'] ?? 'Jawatankuasa PIBG')) ?></h2>
            <?php if (trim((string) ($pibg['subtitle'] ?? '')) !== ''): ?>
                <p class="text-muted"><?= htmlspecialchars((string) $pibg['subtitle']) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($pdfSrcs !== []): ?>
            <?php foreach ($pdfSrcs as $idx => $pdfSrc): ?>
            <div class="mb-4">
                <?php
                smks3_pdf_viewer($pdfSrc, [
                    'id' => 'pibg-pdf-viewer-' . $idx,
                    'label' => $btnLabel . (count($pdfSrcs) > 1 ? ' (' . ($idx + 1) . ')' : ''),
                    'btn_class' => 'btn btn-primary',
                ]);
                ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-info text-center mb-4">
                Tiada PDF dimuat naik lagi.
            </div>
        <?php endif; ?>

        <?php if ($is_editor): ?>
        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="pibg_pdf_gallery"
                    data-edit-label="Urus PDF jawatankuasa PIBG"
                    data-edit-hint="Tambah, buang, atau susun semula semua PDF dalam satu panel."
                    data-images-json="<?= htmlspecialchars($pdfJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>">
                <i class="bi bi-files me-1"></i> Urus PDF
            </button>
            <p class="small text-muted mt-2 mb-0">Satu panel untuk semua PDF: muat naik berbilang, buang, dan susun semula.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
