<?php
$page_title = 'Pusat Sumber';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- HERO -->
<section class="hero text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3">Pusat Sumber Sekolah</h1>
                <p class="lead mb-4">
                    Pusat sumber menyediakan bahan bacaan, maklumat digital dan aktiviti literasi untuk meningkatkan budaya membaca dalam kalangan pelajar.
                </p>
                <a href="contact.php" class="btn btn-light btn-lg">Hubungi Kami</a>
            </div>

            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-book-half opacity-75" style="font-size:7rem;"></i>
            </div>
        </div>
    </div>
</section>


<!-- SECTION NILAM -->
<section class="py-5">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Program NILAM</h2>
            <p class="text-muted">
                Nadi Ilmu Amalan Membaca (NILAM) merupakan program galakan membaca yang dilaksanakan bagi memupuk budaya membaca dalam kalangan pelajar.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-6 col-lg-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">

                        <i class="bi bi-book text-primary display-5 mb-3"></i>

                        <h5>Advanced Integrated NILAM System</h5>

                        <p class="text-muted small mb-3">
                            Sistem bersepadu untuk merekod bahan NILAM murid
                        </p>

                        <a href="https://ains.moe.gov.my/login?returnUrl=/" class="btn btn-outline-primary btn-sm">
                            Lihat Maklumat
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- SECTION BULETIN SEKOLAH -->
<section class="py-5 bg-light">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Buletin Sekolah</h2>
            <p class="text-muted">
                Buletin sekolah memaparkan aktiviti, pencapaian dan perkembangan terkini warga SMK Seremban 3.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <div class="col-md-6 col-lg-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">

                        <i class="bi bi-newspaper text-primary display-5 mb-3"></i>

                        <h5>Buletin Sekolah</h5>

                        <p class="text-muted small mb-3">
                            Koleksi buletin sekolah yang memaparkan aktiviti, program dan kejayaan warga sekolah.
                        </p>

                        <a href="buletin-sekolah.php" class="btn btn-primary btn-sm">
                            Lihat Buletin
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
</section>


<?php require_once __DIR__ . '/includes/footer.php'; ?>