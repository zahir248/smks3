<section class="page-section">
<div class="container">

    <h2 class="text-center fw-bold mb-4">
        Pilihan Mata Pelajaran
    </h2>
    <p class="text-muted">Senarai mata pelajaran yang boleh dipilih oleh pelajar mengikut tingkatan dan aliran.</p>

    <?php
    $pilihan_pdfs = is_array($pilihan_pdfs ?? null) ? $pilihan_pdfs : [];
    $pilihanPdfSrcs = [];
    $shown = 0;
    foreach ($pilihan_pdfs as $row) :
        $file = $row['file_pdf'] ?? '';
        if ($file === '') {
            continue;
        }
        $filePath = 'uploads/pilihan_mata_pelajaran/' . $file;
        if (!file_exists(BASE_PATH . '/' . $filePath)) {
            continue;
        }
        $pilihanPdfSrcs[] = $filePath;
        $shown++;
    ?>
    <div class="card shadow-sm border-0 mb-4 mt-4">
        <div class="card-body">
            <?php
            smks3_pdf_viewer($filePath, [
                'id' => 'pilihan-pdf-' . (int) $row['id'],
                'label' => 'Buka / Muat Turun PDF',
                'btn_class' => 'btn btn-outline-primary btn-sm',
            ]);
            ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php $pilihanPdfJson = json_encode($pilihanPdfSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    <?php if ($shown === 0): ?>
        <div class="alert alert-info text-center">
            Tiada PDF dimuat naik lagi
        </div>
    <?php endif; ?>

    <?php if ($is_editor): ?>
    <div class="text-center mt-4">
        <button type="button" class="btn btn-outline-primary"
                data-edit-block="pilihan_pdf_gallery"
                data-edit-label="Urus PDF pilihan mata pelajaran"
                data-edit-hint="Tambah, buang, atau susun semula semua PDF dalam satu panel."
                data-images-json="<?= htmlspecialchars($pilihanPdfJson ?: '[]', ENT_QUOTES, 'UTF-8') ?>">
            <i class="bi bi-files me-1"></i> Urus PDF
        </button>
        <p class="small text-muted mt-2 mb-0">Satu panel untuk semua PDF: muat naik berbilang, buang, dan susun semula.</p>
    </div>
    <?php endif; ?>

</div>
</section>
