<style>
.akademik-content {
    font-size: clamp(0.9rem, 1.2vw, 1rem);
    line-height: 1.7;
    overflow-x: auto;
}

.akademik-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
    font-size: clamp(0.8rem, 1vw, 0.95rem);
}

.akademik-content th,
.akademik-content td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.akademik-content th {
    background: #0B3C5D;
    color: #fff;
}

body.smks3-is-editor .akademik-content td[data-edit-block] {
    cursor: pointer;
}

@media (max-width: 768px) {
    .akademik-content { font-size: 0.9rem; }
    .akademik-content table { font-size: 0.75rem; }
    .akademik-content th,
    .akademik-content td { padding: 6px; }
}

@media (max-width: 576px) {
    .akademik-content table { font-size: 0.7rem; }
}
</style>

<section class="page-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-4"
            <?php if ($is_editor): ?>
            data-edit-block="kalendar_title"
            data-edit-label="Tajuk halaman"
            data-edit-hint="Tukar tajuk yang dipaparkan di atas jadual."
            data-value="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?>"
            <?php endif; ?>
            ><span data-bind="text"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span></h2>

        <p class="text-muted mb-4">
            <strong>Kumpulan B:</strong>
            Johor, Melaka, Negeri Sembilan, Pahang, Perak,
            Perlis, Pulau Pinang, Sabah, Sarawak, Selangor,
            Wilayah Persekutuan KL, Labuan &amp; Putrajaya
        </p>

        <div class="akademik-content mb-4"
             id="kalendarTableWrap"
             data-edit-table="1"
             data-table-key="kalendar_akademik"
             data-table-store="pages">
            <?php if ($pageContentHtml !== '') : ?>
                <?= $pageContentHtml ?>
            <?php else : ?>
                <p class="text-muted mb-0">Tiada jadual lagi.</p>
            <?php endif; ?>
        </div>

        <?php if ($is_editor): ?>
        <p class="small text-muted mb-4">
            <i class="bi bi-info-circle me-1"></i>
            Klik mana-mana nilai dalam jadual untuk ubah. Muat naik PDF di bawah jika perlu.
        </p>
        <?php endif; ?>

        <?php foreach ($calendar_pdfs as $row) : ?>
            <?php if (empty($row['file_pdf'])) {
                continue;
            }
            $kalendarPdf = 'uploads/kalendar/' . $row['file_pdf'];
            ?>
        <div class="card mb-4 shadow-sm border-0"
             <?php if ($is_editor): ?>
             data-edit-block="kalendar_pdf"
             data-edit-label="PDF kalendar"
             data-edit-hint="Guna Padam untuk buang PDF ini."
             data-id="<?= (int) $row['id'] ?>"
             <?php endif; ?>>
            <div class="card-body">
                <?php
                smks3_pdf_viewer($kalendarPdf, [
                    'id' => 'kalendar-pdf-' . (int) $row['id'],
                    'label' => 'Buka / Muat Turun PDF',
                    'btn_class' => 'btn btn-outline-primary btn-sm',
                ]);
                ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if ($is_editor): ?>
        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="kalendar_pdf_add"
                    data-edit-label="Tambah PDF kalendar"
                    data-edit-hint="Tambah PDF baharu. PDF yang sedia ada kekal (tidak diganti).">
                <i class="bi bi-plus-lg me-1"></i> Tambah PDF
            </button>
            <p class="small text-muted mt-2 mb-0">Boleh ada lebih dari satu PDF. Muat naik baharu tidak menggantikan yang lama.</p>
        </div>
        <?php endif; ?>

        <?php if ($pageContentHtml === '' && empty($calendar_pdfs) && !$is_editor) : ?>
        <div class="text-center text-muted">
            Tiada data kalendar akademik.
        </div>
        <?php endif; ?>
    </div>
</section>
