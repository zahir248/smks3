<?php
$page_title = 'Jurusan';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
$courses = [
    ['id' => 1, 'name' => 'Teknik Komputer dan Rangkaian', 'slug' => 'tkj', 'description' => 'Jurusan yang mempelajari rangkaian komputer, pelayan, dan sistem maklumat.', 'duration' => '4 Tahun', 'icon' => 'bi-laptop'],
    ['id' => 2, 'name' => 'Kejuruteraan Perisian', 'slug' => 'rpl', 'description' => 'Jurusan pengaturcaraan dan pembangunan aplikasi serta perisian.', 'duration' => '4 Tahun', 'icon' => 'bi-code-slash'],
    ['id' => 3, 'name' => 'Multimedia', 'slug' => 'multimedia', 'description' => 'Jurusan reka bentuk grafik, video, animasi, dan kandungan digital.', 'duration' => '4 Tahun', 'icon' => 'bi-camera-video'],
];
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <h1 class="fw-bold mb-2">Jurusan</h1>
        <p class="text-muted lead">Program kemahiran yang kami tawarkan untuk masa depan pelajar.</p>
        <div class="row g-4 mt-3">
            <?php foreach ($courses as $c) : ?>
            <div class="col-md-6 col-lg-4" id="<?= htmlspecialchars($c['slug']) ?>">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <i class="bi <?= htmlspecialchars($c['icon'] ?? 'bi-journal-bookmark') ?> text-primary display-6 mb-3"></i>
                        <h5 class="card-title"><?= htmlspecialchars($c['name']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($c['description']) ?></p>
                        <?php if (!empty($c['duration'])) : ?>
                        <p class="small text-muted mb-0"><i class="bi bi-clock me-1"></i> <?= htmlspecialchars($c['duration']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
