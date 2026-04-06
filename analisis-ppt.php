<?php
$page_title = 'Analisis PPT';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Unit Peperiksaan Dalaman -->
<section class="py-5">
    <div class="container">
        <p class="text-center text-muted lead mb-4">
            Paparan analisis pencapaian pelajar bagi Peperiksaan Pertengahan Tahun (PPT) mengikut tingkatan dan kategori penilaian.
        </p>

        <div class="text-center mb-5">
            <h2 class="fw-bold">Analisis PPT</h2>
            <p class="text-muted">
                Sila pilih kategori di bawah untuk melihat analisis keputusan secara terperinci berdasarkan tingkatan dan jenis laporan.
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
                        <a href="https://drive.google.com/drive/folders/1k39ONdnhY3obIPg0N6RJyA5ai2ZwWx1_" target="_blank" class="list-group-item">ANALISIS TINGKATAN 1</a>
                        <a href="https://drive.google.com/drive/folders/1R2W1wGO_ePd-uNhWzeDWiP3i33htP3J9" target="_blank" class="list-group-item">ANALISIS TINGKATAN 2</a>
                        <a href="https://drive.google.com/drive/folders/1qb707O-WExRt5OjZ4t5jcs3gJpYU9ljE" target="_blank" class="list-group-item">ANALISIS TINGKATAN 3</a>
                        <a href="https://drive.google.com/drive/folders/1rBNXK_N3T5E1pZCD9fmKbt2rzrwWFhgs" target="_blank" class="list-group-item">ANALISIS TINGKATAN 4</a>
                        <a href="https://drive.google.com/drive/folders/1KrKdmy_RzyivezVLljUCZIK4ULs3EI8H" target="_blank" class="list-group-item">ANALISIS TINGKATAN 5</a>
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
                <h5 class="mb-3">GPS (ANALISIS PENCAPAIAN KESELURUHAN PELAJAR)</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse2">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse2">
                    <div class="list-group text-start">
                        <a href="https://drive.google.com/drive/folders/13lFXcNtif8P9K9dkG5KFtk-fxJ9aBMZz" target="_blank" class="list-group-item">TINGKATAN 1</a>
                        <a href="https://drive.google.com/drive/folders/1MY-IWun-kjQychhVpLIFSL_fUCB4FEDY" target="_blank" class="list-group-item">TINGKATAN 2</a>
                        <a href="https://drive.google.com/drive/folders/1N-SHCq6ReLZ-Qwg5tLJBmtIO1lxacx8I" target="_blank" class="list-group-item">TINGKATAN 3</a>
                        <a href="https://drive.google.com/drive/folders/1udxBb-p1r0cS5IFG-i1bXLPP0TGco-32" target="_blank" class="list-group-item">TINGKATAN 4</a>
                        <a href="https://drive.google.com/drive/folders/1fl7z5Kodfyw3Kh2pSP5cSln3hvXhztWO" target="_blank" class="list-group-item">TINGKATAN 5</a>
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
                        <a href="https://docs.google.com/spreadsheets/d/1cSzy0egD7D8zddVZnNQ-I2DSQIrW_7HG/edit?gid=1674267632#gid=1674267632" target="_blank" class="list-group-item">TINGKATAN 1</a>
                        <a href="https://docs.google.com/spreadsheets/d/1bxllXQifY8pfpO5oNuCznnC7hqSsp_Ru/edit?gid=653020296#gid=653020296" target="_blank" class="list-group-item">TINGKATAN 2</a>
                        <a href="https://docs.google.com/spreadsheets/d/1eSB60lHtVT72IFT1KtIPn4jPSyKfXapO/edit?gid=480898156#gid=480898156" target="_blank" class="list-group-item">TINGKATAN 3</a>
                        <a href="https://drive.google.com/drive/folders/1lyQxHZBFe_dacVMcXux9Q_n4NXaRAK-h" target="_blank" class="list-group-item">TINGKATAN 4</a>
                        <a href="https://drive.google.com/drive/folders/1_Cm1pDdHw9stW-NbKG--A7hwH5aD9Fl2" target="_blank" class="list-group-item">TINGKATAN 5</a>
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
                <h5 class="mb-3">PERATUS LAYAK SIJIL TING. 4 & 5</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse4">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse4">
                    <div class="list-group text-start">
                        <a href="https://docs.google.com/spreadsheets/d/1ZKRve4wTW8Wrt5mErhaq8A6gArpHRapw/edit?gid=882625758#gid=882625758" target="_blank" class="list-group-item">ANALISA LMS T4 2025</a>
                        <a href="https://docs.google.com/spreadsheets/d/1OIYROEwwh_jEUoj6WURtqsCm-sdzFp-H/edit?gid=362934113#gid=362934113" target="_blank" class="list-group-item">ANALISA LMS T5 2025</a>
                        <a href="#" class="list-group-item">SENARAI NAMA PELAJAR GAGAL BM SEJ</a>
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
                <h5 class="mb-3">RUMUSAN RANKING</h5>

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
                <h5 class="mb-3">SISTEM PEPERIKSAAN</h5>

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