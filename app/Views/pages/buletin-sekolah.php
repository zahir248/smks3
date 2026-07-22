<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <p class="text-muted lead mb-3">Koleksi buletin rasmi SMK Seremban 3 — aktiviti, program dan pencapaian warga sekolah.</p>
            <h2 class="fw-bold">Koleksi Buletin</h2>
            <p class="text-muted">Klik buletin untuk melihat atau memuat turun versi PDF.</p>
        </div>

        <div class="row g-4 justify-content-center">

            <?php foreach ($buletin_list as $b) : ?>
            <div class="col-md-6 col-lg-4">

                <div class="card card-hover border-0 shadow-sm h-100">

                    <img src="<?= htmlspecialchars($b['cover']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($b['title']) ?>">

                    <div class="card-body text-center">

                        <h6 class="fw-bold"><?= htmlspecialchars($b['title']) ?></h6>

                        <small class="text-muted d-block mb-3">
                            Tahun <?= htmlspecialchars($b['year']) ?>
                        </small>

                        <a href="<?= htmlspecialchars($b['file']) ?>" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                           Lihat Buletin
                        </a>

                    </div>

                </div>

            </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>
