<?php
$page_title = 'Pentaksiran & Peperiksaan';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Unit Peperiksaan Dalaman -->
<section class="py-5">
    <div class="container">
        <p class="text-center text-muted lead mb-4">Maklumat berkaitan pentaksiran pelajar termasuk peperiksaan dalaman, peperiksaan awam dan Pentaksiran Bilik Darjah (PBD). <a href="contact.php" class="text-decoration-none fw-semibold">Hubungi kami</a> untuk pertanyaan lanjut.</p>

        <div class="text-center mb-5">
            <h2 class="fw-bold">Unit Peperiksaan Dalaman</h2>
            <p class="text-muted">Maklumat analisis peperiksaan dan bahan rujukan bagi kegunaan guru dan pelajar.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm card-hover">
                    <div class="card-body text-center p-4">

                        <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                        <h5 class="mb-3">Unit Peperiksaan Dalaman</h5>

                        <button class="btn btn-outline-primary"
                                data-bs-toggle="collapse"
                                data-bs-target="#peperiksaanDalaman">
                            Lihat Bahagian
                        </button>

                        <div class="collapse mt-4" id="peperiksaanDalaman">

                            <div class="list-group text-start">

                                <a href="#" class="list-group-item list-group-item-action">
                                    ANALISIS PAT T4 & UASA T1,2,3
                                </a>

                                <a href="#" class="list-group-item list-group-item-action">
                                    ANALISIS PPT
                                </a>

                                <a href="#" class="list-group-item list-group-item-action">
                                    BANK SOALAN UASA, PPT, PAT SELARAS
                                </a>

                                <a href="#" class="list-group-item list-group-item-action">
                                    KEPUTUSAN 2018 - 2024
                                </a>

                                <a href="#" class="list-group-item list-group-item-action">
                                    PENGGUBAL SOALAN UPSA & UASA
                                </a>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</section>

<!-- Peperiksaan Umum / SPM -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Peperiksaan Umum / SPM</h2>
            <p class="text-muted">Peperiksaan awam yang dikendalikan oleh Kementerian Pendidikan Malaysia bagi pelajar tingkatan lima.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5>Sijil Pelajaran Malaysia (SPM)</h5>
                        <p class="text-muted">
                            SPM merupakan peperiksaan awam utama bagi pelajar Tingkatan 5 di Malaysia. Keputusan peperiksaan ini
                            menjadi salah satu penentu kelayakan pelajar untuk melanjutkan pelajaran ke peringkat yang lebih tinggi
                            seperti matrikulasi, diploma atau kolej vokasional.
                        </p>

                        <ul class="text-muted">
                            <li>Dikendalikan oleh Lembaga Peperiksaan Malaysia</li>
                            <li>Melibatkan pelbagai mata pelajaran teras dan elektif</li>
                            <li>Menjadi syarat kemasukan ke institusi pengajian tinggi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pentaksiran Bilik Darjah -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Pentaksiran Bilik Darjah (PBD)</h2>
            <p class="text-muted">Pentaksiran berterusan yang dijalankan oleh guru semasa proses pengajaran dan pembelajaran.</p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- UNIT PBD -->
            <div class="col-md-5">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-folder-check text-primary display-5 mb-3"></i>
                        <h5>UNIT PBD</h5>
                        <p class="text-muted small mb-3">
                            Maklumat berkaitan pengurusan Pentaksiran Bilik Darjah termasuk garis panduan dan dokumen berkaitan.
                        </p>

                        <a href="unit-pbd.php" class="btn btn-outline-primary btn-sm">
                            Lihat Maklumat
                        </a>
                    </div>
                </div>
            </div>

            <!-- SEMAKAN PBD -->
            <div class="col-md-5">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-search text-primary display-5 mb-3"></i>
                        <h5>SEMAKAN PBD SMK SEREMBAN 3</h5>
                        <p class="text-muted small mb-3">
                            Semakan tahap penguasaan Pentaksiran Bilik Darjah bagi pelajar SMK Seremban 3.
                        </p>

                        <a href="https://lookerstudio.google.com/u/0/reporting/a93b2cf1-f955-443e-829e-cf4a9a3d37c1/page/OXERC" target="_blank" class="btn btn-primary btn-sm">
                            Semak Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>