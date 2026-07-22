<section class="page-section" id="maklumat-sekolah">
    <div class="container">
        <div class="row g-3 g-md-4 justify-content-center">
            <?php foreach ($profil_data as $item) : ?>
            <div class="col-md-6 col-lg-4"
                 <?php if ($is_editor): ?>
                 data-edit-block="profil_item"
                 data-edit-label="Sunting maklumat profil"
                 data-edit-hint="Kemaskini tajuk, nilai dan ikon kad ini."
                 data-id="<?= (int) $item['id'] ?>"
                 data-title="<?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>"
                 data-value="<?= htmlspecialchars($item['value'], ENT_QUOTES, 'UTF-8') ?>"
                 data-icon="<?= htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>
                <div class="info-card">
                    <div class="info-card__body">
                        <span class="icon-box" aria-hidden="true">
                            <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i>
                        </span>
                        <div>
                            <div class="info-card__label"><?= htmlspecialchars($item['title']) ?></div>
                            <p class="info-card__value"><?= htmlspecialchars($item['value']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <?php if ($is_editor): ?>
            <div class="col-md-6 col-lg-4"
                 data-edit-block="profil_item_add"
                 data-edit-label="Tambah maklumat profil"
                 data-edit-hint="Tambah kad maklumat baharu pada halaman profil sekolah.">
                <div class="info-card" style="border-style: dashed; min-height: 100%;">
                    <div class="info-card__body justify-content-center text-center w-100">
                        <span class="icon-box" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
                        <div>
                            <div class="info-card__label">Tambah maklumat</div>
                            <p class="info-card__value text-muted mb-0">Kad baharu</p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
