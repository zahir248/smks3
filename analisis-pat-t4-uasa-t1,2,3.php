<?php
$page_title = 'Analisis PAT T4 & UASA T1,2,3';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Unit Peperiksaan Dalaman -->
<section class="py-5">
    <div class="container">
        <p class="text-center text-muted lead mb-4">
            Paparan analisis pencapaian pelajar bagi Peperiksaan Akhir Tahun (PAT) Tingkatan 4 serta Ujian Akhir Sesi Akademik (UASA) bagi Tingkatan 1, 2 dan 3.
        </p>

        <div class="text-center mb-5">
            <h2 class="fw-bold">Analisis PAT T4 & UASA T1,2,3</h2>
            <p class="text-muted">
                Sila pilih kategori di bawah untuk melihat analisis terperinci mengikut tingkatan dan jenis laporan.
            </p>
        </div>

        <div class="row">

    <!-- CARD 1 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">ANALISIS SUBJEK</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse1">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse1">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">TINGKATAN 1</a>
                        <a href="#" class="list-group-item">TINGKATAN 2</a>
                        <a href="#" class="list-group-item">TINGKATAN 3</a>
                        <a href="#" class="list-group-item">TINGKATAN 4</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">GPS</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse2">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse2">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">2025</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 3 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">LEMBARAN MARKAH</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse3">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse3">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">TINGKATAN 1</a>
                        <a href="#" class="list-group-item">TINGKATAN 2</a>
                        <a href="#" class="list-group-item">TINGKATAN 3</a>
                        <a href="#" class="list-group-item">TINGKATAN 4</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 4 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">LMS TING. 4</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse4">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse4">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">2025</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 5 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">PELAPORAN UASA</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse5">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse5">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">TINGKATAN 1</a>
                        <a href="#" class="list-group-item">TINGKATAN 2</a>
                        <a href="#" class="list-group-item">TINGKATAN 3</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- CARD 6 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">RANKING</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse6">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse6">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">2025</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>