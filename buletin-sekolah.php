<?php
$page_title = 'Buletin Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

$buletin_list = [
    [
        'title' => 'Buletin SMK Seremban 3 2024',
        'year' => '2024',
        'cover' => 'uploads/buletin/buletin2024.jpg',
        'file' => 'uploads/buletin/buletin2024.pdf'
    ],
    [
        'title' => 'Buletin SMK Seremban 3 2023',
        'year' => '2023',
        'cover' => 'uploads/buletin/buletin2023.jpg',
        'file' => 'uploads/buletin/buletin2023.pdf'
    ],
    [
        'title' => 'Buletin SMK Seremban 3 2022',
        'year' => '2022',
        'cover' => 'uploads/buletin/buletin2022.jpg',
        'file' => 'uploads/buletin/buletin2022.pdf'
    ],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <p class="text-muted lead mb-3">Koleksi buletin rasmi SMK Seremban 3 — aktiviti, program dan pencapaian warga sekolah.</p>
            <h2 class="fw-bold">Koleksi Buletin</h2>
            <p class="text-muted">Klik buletin untuk melihat atau memuat turun versi PDF.</p>
        </div>

        <div class="row g-4">

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

<?php require_once __DIR__ . '/includes/footer.php'; ?>