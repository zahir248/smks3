<?php
$page_title = 'Guru & Kakitangan';
require_once __DIR__ . '/includes/functions.php';
$staff_list = [
    ['name' => 'Dr. Ahmad Wijaya, S.Pd., M.M.', 'role' => 'Pengetua', 'department' => 'Pengurusan', 'bio' => 'Memimpin SMK S3 dengan visi pendidikan vokasional yang unggul.', 'photo_url' => null],
    ['name' => 'Siti Nurhaliza, S.Kom.', 'role' => 'Guru Produktif TKJ', 'department' => 'Teknik Komputer dan Rangkaian', 'bio' => 'Pengajar rangkaian dan sistem komputer.', 'photo_url' => null],
    ['name' => 'Budi Santoso, S.T.', 'role' => 'Guru Produktif RPL', 'department' => 'Kejuruteraan Perisian', 'bio' => 'Pengajar pengaturcaraan dan pembangunan aplikasi.', 'photo_url' => null],
];
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <p class="text-muted lead mb-4">Tenaga pengajar dan kakitangan kami.</p>
        <div class="row g-4 mt-4">
            <?php foreach ($staff_list as $s) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <?php if (!empty($s['photo_url'])) : ?>
                        <img src="<?= htmlspecialchars($s['photo_url']) ?>" alt="<?= htmlspecialchars($s['name']) ?>" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
                        <?php else : ?>
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                            <i class="bi bi-person-fill text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <?php endif; ?>
                        <h6 class="mb-1"><?= htmlspecialchars($s['name']) ?></h6>
                        <p class="text-primary small fw-semibold mb-1"><?= htmlspecialchars($s['role']) ?></p>
                        <?php if (!empty($s['department'])) : ?>
                        <p class="text-muted small mb-2"><?= htmlspecialchars($s['department']) ?></p>
                        <?php endif; ?>
                        <p class="small text-muted mb-0"><?= htmlspecialchars($s['bio'] ?? '') ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
