<?php
$page_title = 'Laman Utama';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
$news_list = [
    ['id' => 1, 'title' => 'Kemasukan Pelajar Baru 2025', 'slug' => 'ppdb-2025', 'excerpt' => 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', 'published_at' => date('Y-m-d H:i:s')],
    ['id' => 2, 'title' => 'Aktiviti Latihan Industri', 'slug' => 'pkl-2025', 'excerpt' => 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', 'published_at' => date('Y-m-d H:i:s')],
];
$courses_list = [
    ['id' => 1, 'name' => 'Teknik Komputer dan Rangkaian', 'slug' => 'tkj', 'description' => 'Jurusan yang mempelajari rangkaian komputer, pelayan, dan sistem maklumat.', 'icon' => 'bi-laptop'],
    ['id' => 2, 'name' => 'Kejuruteraan Perisian', 'slug' => 'rpl', 'description' => 'Jurusan pengaturcaraan dan pembangunan aplikasi serta perisian.', 'icon' => 'bi-code-slash'],
    ['id' => 3, 'name' => 'Multimedia', 'slug' => 'multimedia', 'description' => 'Jurusan reka bentuk grafik, video, animasi, dan kandungan digital.', 'icon' => 'bi-camera-video'],
];
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($settings['school_name']) ?></h1>
                <p class="lead mb-4"><?= htmlspecialchars($settings['tagline'] ?? 'Belajar, Berkreasi, Berprestasi') ?></p>
                <a href="about.php" class="btn btn-light btn-lg me-2">Perihal Kami</a>
                <a href="courses.php" class="btn btn-outline-light btn-lg">Lihat Jurusan</a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-mortarboard-fill opacity-75" style="font-size: 7rem;"></i>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Selamat Datang</h2>
            <p class="text-muted lead"><?= htmlspecialchars($settings['about_summary'] ?? '') ?></p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-book-half text-primary display-4 mb-3"></i>
                        <h5>Pendidikan Berkualiti</h5>
                        <p class="text-muted small mb-0">Kurikulum bersepadu dan amali lapangan untuk siap bekerja.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-people text-primary display-4 mb-3"></i>
                        <h5>Guru Berpengalaman</h5>
                        <p class="text-muted small mb-0">Tenaga pengajar profesional dan bertauliah.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-briefcase text-primary display-4 mb-3"></i>
                        <h5>Perkaitan Industri</h5>
                        <p class="text-muted small mb-0">Kerjasama dengan industri dan dunia pekerjaan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($courses_list)) : ?>
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Jurusan Kami</h2>
        <div class="row g-4">
            <?php foreach ($courses_list as $c) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <i class="bi <?= htmlspecialchars($c['icon'] ?? 'bi-journal-bookmark') ?> text-primary fs-1 mb-2"></i>
                        <h5 class="card-title"><?= htmlspecialchars($c['name']) ?></h5>
                        <p class="card-text text-muted small"><?= htmlspecialchars($c['description']) ?></p>
                        <a href="courses.php#<?= htmlspecialchars($c['slug']) ?>" class="btn btn-outline-primary btn-sm">Lanjutan</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="courses.php" class="btn btn-primary">Semua Jurusan</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($news_list)) : ?>
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Berita Terkini</h2>
        <div class="row g-4">
            <?php foreach ($news_list as $n) : ?>
            <div class="col-md-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted"><?= date('d M Y', strtotime($n['published_at'])) ?></small>
                        <h6 class="card-title mt-1"><?= htmlspecialchars($n['title']) ?></h6>
                        <p class="card-text small text-muted"><?= htmlspecialchars($n['excerpt']) ?></p>
                        <a href="news.php?id=<?= (int)$n['id'] ?>" class="btn btn-link p-0">Baca selanjutnya</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="news.php" class="btn btn-outline-primary">Semua Berita</a>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5" style="background: linear-gradient(180deg, var(--school-bg-subtle) 0%, #fff 100%);">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Sedia Menyertai?</h2>
        <p class="text-muted mb-4">Daftar sekarang dan capai impian anda di <?= htmlspecialchars($settings['school_name']) ?>.</p>
        <a href="contact.php" class="btn btn-primary btn-lg px-4">Hubungi Kami</a>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
