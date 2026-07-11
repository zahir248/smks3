<section class="page-section">
    <div class="container">

        <style>
            .pengurusan-card {
                text-align: center;
                margin-bottom: 2rem;
                cursor: pointer;
                background: #fff;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.05);
                transition: transform 0.3s, box-shadow 0.3s;
                height: 300px; /* tinggi tetap supaya semua card konsisten */
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
                width: 130px;
                height: 200px;
                overflow: hidden;
                border-radius: 50%;
                margin-bottom: 10px;
                flex-shrink: 0;
            }
            .pengurusan-card img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                transition: transform 0.3s;
            }
            .pengurusan-card:hover img {
                transform: scale(1.1);
            }
            .pengurusan-card h5 {
                margin: 2px 0;
                font-size: 14px;
                color: #333;
            }
            .pengurusan-card h5.jawatan {
                font-weight: 600;
                color: #0B3C5D;
            }

            /* Grid spacing */
            .pengurusan-row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }
            @media(max-width: 768px){
                .pengurusan-card { height: auto; width: 140px; padding: 8px; }
                .pengurusan-card .image-wrapper { width: 80px; height: 80px; }
            }
        </style>

        <?php
        $order = ['pengetua', 'pk', 'gkmp', 'kaunselor'];
        foreach ($order as $kategori):
            if (!empty($groups[$kategori])):
        ?>
                <div class="pengurusan-row mb-4">
                    <?php foreach ($groups[$kategori] as $p): ?>
                        <div class="pengurusan-card"
                             <?php if ($is_editor): ?>
                             data-edit-block="pengurusan_item"
                             data-edit-label="Sunting pengurusan"
                             data-id="<?= (int) $p['id'] ?>"
                             data-nama="<?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8') ?>"
                             data-gred="<?= htmlspecialchars($p['gred'], ENT_QUOTES, 'UTF-8') ?>"
                             data-jawatan="<?= htmlspecialchars($p['jawatan'], ENT_QUOTES, 'UTF-8') ?>"
                             data-kategori="<?= htmlspecialchars((string) ($p['kategori'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                             <?php endif; ?>>
                            <div class="image-wrapper">
                                <img src="<?= !empty($p['gambar']) ? htmlspecialchars($p['gambar']) : $placeholderImage ?>" 
                                     alt="<?= htmlspecialchars($p['nama']) ?>">
                            </div>
                            <h5 data-bind="pengurusan_nama"><?= htmlspecialchars($p['nama']) ?></h5>
                            <h5 data-bind="pengurusan_gred"><?= htmlspecialchars($p['gred']) ?></h5>
                            <h5 class="jawatan" data-bind="pengurusan_jawatan"><?= htmlspecialchars($p['jawatan']) ?></h5>
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
