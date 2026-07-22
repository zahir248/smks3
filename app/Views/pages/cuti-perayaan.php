<style>
.kumpulan-note {
    font-size: clamp(0.8rem, 1.2vw, 0.95rem);
    line-height: 1.6;
}

.jadual-cuti {
    font-size: clamp(0.75rem, 1vw, 0.9rem);
    background: #fff;
}

.jadual-cuti th,
.jadual-cuti td {
    padding: 8px;
}

.jadual-cuti thead th {
    white-space: nowrap;
}

.jadual-cuti tbody tr:nth-child(even) {
    background: #f8fafc;
}

.catatan-cuti {
    font-size: clamp(0.8rem, 1.1vw, 0.95rem);
    line-height: 1.6;
}

body.smks3-is-editor [data-edit-table] td[data-edit-block],
body.smks3-is-editor [data-edit-list] li[data-edit-block] {
    cursor: pointer;
}

@media (max-width: 768px) {
    .jadual-cuti { font-size: 0.7rem; }
    .jadual-cuti th,
    .jadual-cuti td { padding: 6px; }
}

@media (max-width: 576px) {
    .jadual-cuti { font-size: 0.65rem; }
}
</style>

<section class="page-section">
    <div class="container">
        <p class="text-start mb-4 kumpulan-note"
           <?php if ($is_editor): ?>
           data-edit-block="cuti_kumpulan"
           data-edit-label="Ubah Kumpulan A &amp; B"
           data-edit-hint="Isi negeri untuk setiap kumpulan, kemudian simpan."
           data-kumpulan-a="<?= htmlspecialchars((string) ($cutiKumpulan['a'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
           data-kumpulan-b="<?= htmlspecialchars((string) ($cutiKumpulan['b'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
           <?php endif; ?>
           ><span data-bind="cuti_kumpulan"><?= $cutiIntroHtml ?></span></p>

        <div class="table-responsive shadow-sm rounded"
             data-edit-table="1"
             data-table-key="cuti_perayaan_table"
             data-table-store="site_content">
            <?= $cutiTableHtml ?>
        </div>

        <?php if ($is_editor): ?>
        <p class="small text-muted mt-2 mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Klik mana-mana nilai dalam jadual untuk ubah.
        </p>
        <?php endif; ?>

        <div class="mt-4 catatan-cuti">
            <h5>Catatan:</h5>
            <div data-edit-list="1" data-list-key="cuti_perayaan_notes">
                <?= $cutiNotesHtml ?>
            </div>
            <?php if ($is_editor): ?>
            <p class="small text-muted mt-2 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Klik mana-mana catatan untuk ubah.
            </p>
            <?php endif; ?>
        </div>

        <?php
        $cuti_pdfs = is_array($cuti_pdfs ?? null) ? $cuti_pdfs : [];
        $cutiPdfSrcs = [];
        foreach ($cuti_pdfs as $row) {
            $file = $row['file_pdf'] ?? '';
            if ($file === '') {
                continue;
            }
            $filePath = 'uploads/cuti_perayaan/' . $file;
            if (file_exists(BASE_PATH . '/' . $filePath)) {
                $cutiPdfSrcs[] = $filePath;
            }
        }
        $cutiPdfJson = json_encode($cutiPdfSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        foreach ($cuti_pdfs as $row) :
            $file = $row['file_pdf'] ?? '';
            if ($file === '') {
                continue;
            }
            $filePath = 'uploads/cuti_perayaan/' . $file;
            if (!file_exists(BASE_PATH . '/' . $filePath)) {
                continue;
            }
        ?>
        <div class="card shadow-sm border-0 mb-4 mt-4">
            <div class="card-body">
                <?php
                smks3_pdf_viewer($filePath, [
                    'id' => 'cuti-pdf-' . (int) $row['id'],
                    'label' => 'Buka / Muat Turun PDF',
                    'btn_class' => 'btn btn-primary',
                ]);
                ?>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if ($is_editor): ?>
        <div class="text-center mb-4 mt-3">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="cuti_pdf_gallery"
                    data-edit-label="Urus PDF cuti"
                    data-edit-hint="Tambah, buang, atau susun semula semua PDF dalam satu panel."
                    data-images-json="<?= htmlspecialchars($cutiPdfJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>">
                <i class="bi bi-files me-1"></i> Urus PDF
            </button>
            <p class="small text-muted mt-2 mb-0">Satu panel untuk semua PDF: muat naik berbilang, buang, dan susun semula.</p>
        </div>
        <?php endif; ?>
    </div>
</section>
