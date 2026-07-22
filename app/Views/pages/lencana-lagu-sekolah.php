<style>
.lencana-img {
    max-width: 220px;
    width: 100%;
    height: auto;
}

@media (max-width: 768px) {
    .lencana-img {
        max-width: 180px;
    }
}

.lagu-sekolah {
    font-size: clamp(0.9rem, 2.5vw, 1.1rem);
    line-height: 1.8;
    border: 1px solid #eee;
}
</style>

<!-- ================= LENCANA ================= -->

<section class="page-section">

    <div class="container">

        <h2 class="text-center fw-bold mb-5">Lencana Sekolah</h2>

        <div class="row align-items-center g-4 bg-white">

            <!-- IMAGE -->
            <div class="col-12 col-md-4 text-center"
                 <?php if ($is_editor): ?>
                 data-edit-block="lencana_main"
                 data-edit-label="Sunting logo sekolah"
                 data-edit-hint="Logo ini diguna di lencana, navbar, laman utama, log masuk, dan favicon."
                 <?php endif; ?>>
                <img 
                    src="<?= htmlspecialchars($lencana_image_src ?? ('images/' . (string) ($data['image'] ?? 'hero-logo.png')), ENT_QUOTES, 'UTF-8') ?>" 
                    class="img-fluid lencana-img"
                    alt="Lencana Sekolah"
                >
                <?php if ($is_editor): ?>
                <p class="small text-muted mt-2 mb-0">Klik untuk ganti logo (seluruh laman)</p>
                <?php endif; ?>
            </div>

            <!-- CONTENT -->
            <div class="col-12 col-md-8">

                <p class="mb-3"
                   <?php if ($is_editor): ?>
                   data-edit-block="lencana_moto"
                   data-edit-label="Sunting moto sekolah"
                   data-edit-hint="Ubah moto sekolah, kemudian simpan."
                   data-moto="<?= htmlspecialchars((string) ($data['moto'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                   <?php endif; ?>>
                    <strong>Moto:</strong> <span data-bind="lencana_moto"><?= htmlspecialchars((string) ($data['moto'] ?? '')) ?></span>
                </p>

                <ul class="ps-3">
                    <?php foreach ($lencana_items as $row): ?>
                        <li
                            <?php if ($is_editor): ?>
                            data-edit-block="lencana_item"
                            data-edit-label="Sunting item lencana"
                            data-id="<?= (int) $row['id'] ?>"
                            data-title="<?= htmlspecialchars((string) $row['title'], ENT_QUOTES, 'UTF-8') ?>"
                            data-description="<?= htmlspecialchars((string) ($row['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>>
                            <strong><?= htmlspecialchars($row['title']) ?>:</strong>
                            <?= htmlspecialchars($row['description']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if ($is_editor): ?>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                        data-edit-block="lencana_item_add"
                        data-edit-label="Tambah item lencana"
                        data-edit-hint="Tambah penerangan baharu untuk lencana.">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Item
                </button>
                <?php endif; ?>

            </div>

        </div>

    </div>

</section>

<!-- ================= LAGU ================= -->

<section class="page-section">

    <div class="container">

        <h2 class="text-center fw-bold mb-5">Lagu Sekolah</h2>

        <div class="row justify-content-center">

            <div class="col-lg-8"
                 <?php if ($is_editor): ?>
                 data-edit-block="lencana_lagu"
                 data-edit-label="Sunting lagu sekolah"
                 data-edit-hint="Ubah lirik dan kredit lagu, kemudian simpan."
                 data-lirik="<?= htmlspecialchars((string) ($data['lirik'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 data-lirik-penggubah="<?= htmlspecialchars((string) ($data['lirik_penggubah'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 data-lirik-penulis="<?= htmlspecialchars((string) ($data['lirik_penulis'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>

                <div class="p-4 bg-light rounded shadow text-center lagu-sekolah" data-bind="lencana_lirik">

                    <?= nl2br(htmlspecialchars((string) ($data['lirik'] ?? ''))) ?>

                </div>

                <p class="text-center mt-2" data-bind="lencana_kredit">
                    <small>
                        Lagu: <?= htmlspecialchars((string) ($data['lirik_penggubah'] ?? '')) ?> |
                        Lirik: <?= htmlspecialchars((string) ($data['lirik_penulis'] ?? '')) ?>
                    </small>
                </p>

            </div>

        </div>

    </div>

</section>
