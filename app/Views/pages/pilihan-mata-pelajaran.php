<section class="page-section">
<div class="container">

    <h2 class="text-center fw-bold mb-4">
        Pilihan Mata Pelajaran
    </h2>
            <p class="text-muted">Senarai mata pelajaran yang boleh dipilih oleh pelajar mengikut tingkatan dan aliran. </p>

    <?php if (!empty($data['file_pdf'])): ?>
        <?php $pilihanPdf = 'uploads/pilihan_mata_pelajaran/' . $data['file_pdf']; ?>

        <div class="card shadow-sm border-0"
             <?php if ($is_editor): ?>
             data-edit-block="pilihan_pdf"
             data-edit-label="PDF pilihan mata pelajaran"
             data-edit-hint="Guna Padam untuk buang PDF ini."
             data-id="<?= (int) $data['id'] ?>"
             <?php endif; ?>>

            <div class="card-body">
                <?php
                smks3_pdf_viewer($pilihanPdf, [
                    'id' => 'pilihan-pdf-' . (int) ($data['id'] ?? 0),
                    'label' => 'Buka / Muat Turun PDF',
                    'btn_class' => 'btn btn-outline-primary btn-sm',
                ]);
                ?>
            </div>

        </div>

    <?php else: ?>

        <div class="alert alert-info text-center">
            Tiada PDF dimuat naik lagi
        </div>

    <?php endif; ?>

    <?php if ($is_editor): ?>
    <div class="text-center mt-4">
        <button type="button" class="btn btn-outline-primary"
                data-edit-block="pilihan_pdf_add"
                data-edit-label="Tambah / ganti PDF"
                data-edit-hint="Muat naik fail PDF pilihan mata pelajaran.">
            <i class="bi bi-plus-lg me-1"></i> Tambah PDF
        </button>
    </div>
    <?php endif; ?>

</div>
</section>
