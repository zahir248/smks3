<section class="page-section">
    <div class="container">

        <style>
            .pengurusan-card {
                text-align: center;
                margin-bottom: 2rem;
                cursor: pointer;
                background: #fff;
                padding: 14px;
                border-radius: 12px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.05);
                transition: transform 0.3s, box-shadow 0.3s;
                width: 240px;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
            }
            .pengurusan-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(11,60,93,0.15);
            }
            .pengurusan-card .image-wrapper {
                width: 100%;
                margin-bottom: 12px;
                flex-shrink: 0;
                line-height: 0;
            }
            .pengurusan-card img {
                width: 100%;
                height: auto;
                display: block;
                border-radius: 4px;
            }
            .pengurusan-card .staff-meta {
                width: 100%;
                max-width: 100%;
                overflow-wrap: anywhere;
                word-break: break-word;
                line-height: 1.35;
            }
            .pengurusan-card .staff-meta h6 {
                margin-bottom: 0;
            }
            .pengurusan-card--pengetua {
                width: 280px;
                padding: 16px;
            }
            .pengurusan-card--pengetua .staff-meta h6 {
                font-size: 1.05rem;
            }

            .pengurusan-row {
                display: flex;
                flex-wrap: wrap;
                gap: 28px;
                justify-content: center;
            }
            @media (max-width: 992px) {
                .pengurusan-card { width: 210px; }
                .pengurusan-card--pengetua { width: 245px; }
            }
            @media (max-width: 768px) {
                .pengurusan-card { width: 160px; padding: 10px; }
                .pengurusan-card--pengetua { width: 185px; padding: 12px; }
                .pengurusan-row { gap: 16px; }
            }
        </style>

        <?php
        $order = ['pengetua', 'pk', 'gkmp', 'kaunselor'];
        foreach ($order as $kategori):
            if (!empty($groups[$kategori])):
        ?>
                <div class="pengurusan-row mb-4">
                    <?php foreach ($groups[$kategori] as $p): ?>
                        <div class="pengurusan-card<?= $kategori === 'pengetua' ? ' pengurusan-card--pengetua' : '' ?>"
                             <?php if ($is_editor): ?>
                             data-edit-block="pengurusan_item"
                             data-edit-label="Sunting pengurusan"
                             data-id="<?= (int) $p['id'] ?>"
                             data-nama="<?= htmlspecialchars((string) ($p['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-gred="<?= htmlspecialchars((string) ($p['gred'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-jawatan="<?= htmlspecialchars((string) ($p['jawatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-kategori="<?= htmlspecialchars((string) ($p['kategori'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             data-image="<?= htmlspecialchars((string) ($p['gambar'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             <?php endif; ?>>
                            <div class="image-wrapper">
                                <img src="<?= !empty($p['gambar']) ? htmlspecialchars((string) $p['gambar'], ENT_QUOTES, 'UTF-8') : $placeholderImage ?>" 
                                     alt="<?= htmlspecialchars((string) ($p['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="staff-meta">
                                <h6 class="mb-0 fw-bold" data-bind="pengurusan_nama"><?= htmlspecialchars((string) ($p['nama'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h6>
                                <small class="text-muted" data-bind="pengurusan_gred"><?= htmlspecialchars((string) ($p['gred'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small><br>
                                <small data-bind="pengurusan_jawatan"><?= htmlspecialchars((string) ($p['jawatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php
            endif;
        endforeach;
        ?>

        <?php if ($is_editor): ?>
        <div class="text-center mt-3 mb-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="pengurusan_add"
                    data-edit-label="Tambah pengurusan"
                    data-edit-hint="Tambah ahli pengurusan baharu.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Pengurusan
            </button>
        </div>
        <?php endif; ?>

    </div>
</section>
