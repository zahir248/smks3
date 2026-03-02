<?php
$page_title = 'Profil Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Profil Sekolah</h1>
                <p class="lead">SMK Seremban 3. Sekolah yang berpendidikan berkualiti, persekitaran kondusif dan peluang pembangunan menyeluruh untuk pelajar.</p>
                <a href="#maklumat-sekolah" class="btn btn-light btn-lg me-2">Maklumat Sekolah</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg">Hubungi Kami</a>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-building text-white opacity-75" style="font-size: 7rem;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Profil Sekolah Creative Cards -->
<section class="py-5" id="maklumat-sekolah">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Mengenai SMK Seremban 3</h2>
            <p class="text-muted">Maklumat Am dan ringkas tentang sekolah kami.</p>
        </div>

        <div class="row g-4">
            <?php 
            $profil_data = [
                ['title'=>'Nama Pengetua','value'=>'Puan Siamala A/P Govindan','icon'=>'bi-person-badge','color'=>'#1e3a8a'],
                ['title'=>'Bilangan Guru','value'=>'43 orang','icon'=>'bi-people','color'=>'#0d9488'],
                ['title'=>'Bilangan Murid','value'=>'644 orang','icon'=>'bi-people-fill','color'=>'#f59e0b'],
                ['title'=>'Keluasan Sekolah','value'=>'7.01 hektar','icon'=>'bi-arrows-fullscreen','color'=>'#9333ea'],
                ['title'=>'Sesi Persekolahan','value'=>'1 Sesi','icon'=>'bi-clock','color'=>'#ef4444'],
                ['title'=>'Tingkatan Tertinggi','value'=>'5','icon'=>'bi-mortarboard','color'=>'#2563eb'],
                ['title'=>'Alamat Sekolah','value'=>'Jalan Seremban Tiga 3, 70300 Seremban, Negeri Sembilan','icon'=>'bi-geo-alt','color'=>'#f97316'],
                ['title'=>'Kod Sekolah','value'=>'NEA 4117','icon'=>'bi-hash','color'=>'#10b981'],
                ['title'=>'Lokasi','value'=>'Luar Bandar','icon'=>'bi-map','color'=>'#3b82f6'],
                ['title'=>'Daerah Pentadbiran','value'=>'Seremban','icon'=>'bi-building','color'=>'#8b5cf6'],
                ['title'=>'Gred Sekolah','value'=>'B','icon'=>'bi-award','color'=>'#fbbf24'],
                ['title'=>'Pejabat Pendidikan Daerah','value'=>'Seremban','icon'=>'bi-building','color'=>'#ef4444'],
                ['title'=>'Jenis Bantuan','value'=>'Sekolah Menengah Kerajaan','icon'=>'bi-bank2','color'=>'#22c55e'],
            ];

            foreach($profil_data as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-hover h-100 shadow-sm border-0">
                    <div class="card-body d-flex align-items-start">
                        <div class="me-3">
                            <i class="bi <?= $item['icon'] ?> fs-1" style="color: <?= $item['color'] ?>;"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold mb-1"><?= $item['title'] ?></h5>
                            <p class="card-text mb-0"><?= $item['value'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>