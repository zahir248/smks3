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

                        <!-- TAHUN -->
                        <a class="list-group-item"
                        data-bs-toggle="collapse"
                        href="#tahun2025">
                        2025
                        </a>

                        <div class="collapse ms-3 mt-2" id="tahun2025">
                            <!-- TINGKATAN 1 -->
                            <a class="list-group-item"
                            data-bs-toggle="collapse"
                            href="#t1_2025">
                            TINGKATAN 1
                            </a>

                            <div class="collapse ms-3 mt-2" id="t1_2025">
                                <a href="https://docs.google.com/spreadsheets/d/1P0eT9DIDCN51UZpX7ic1fDSl1K0R4AG7/edit?gid=269500431#gid=269500431"
                                target="_blank"
                                class="list-group-item">
                                ANALISIS MP F1 UASA 2025
                                </a>

                                <a href="https://docs.google.com/spreadsheets/d/1mC8Va3NB6E-ZTd0Dz-37hD8_ge1Mm_LH/edit?gid=103880239#gid=103880239"
                                target="_blank"
                                class="list-group-item">
                                ANALISIS MP UPSA TANPA PECAHAN KELAS T1 2025
                                </a>
                            </div>
                            <a href="https://docs.google.com/spreadsheets/d/1Lrfb9AOSByxJx6xVNkE9L44mjFu-0dIr/edit?gid=360450347#gid=360450347" target="_blank" class="list-group-item">TINGKATAN 2</a>
                            <a href="https://docs.google.com/spreadsheets/d/1A95TWniq2p_XH4_wxKWj0Jlvtp0pJemd/edit?gid=656362314#gid=656362314" target="_blank" class="list-group-item">TINGKATAN 3</a>
                            <a href="https://docs.google.com/spreadsheets/d/13EX8bdxT3SRcQJhgHDlL3IM0COgJvUvq/edit?gid=1650092040#gid=1650092040" target="_blank" class="list-group-item">TINGKATAN 4</a>
                        </div>

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
                        <a href="https://docs.google.com/spreadsheets/d/12NKdE7rZdZEYz9xoUA75fja2_IeNmZEV/edit?gid=1473146862#gid=1473146862" target="_blank" class="list-group-item">2025</a>
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
                        <a href="https://drive.google.com/drive/folders/1u46c82LzcW2YjTqKILsL1fMld4PFv3T7" target="_blank" class="list-group-item">TINGKATAN 1</a>
                        <a href="https://drive.google.com/drive/folders/1f5zGFQS1c7bLS_zw-P-JrK9s6WfzIQxd" target="_blank" class="list-group-item">TINGKATAN 2</a>
                        <a href="https://drive.google.com/drive/folders/1xGpoij2d5X0NACZYL4YtogDJRpuHDkFI" target="_blank" class="list-group-item">TINGKATAN 3</a>
                        <a href="https://drive.google.com/drive/folders/11-lJSrTVBqFXoYDBqAYUFzHJU7U1s5Ee" target="_blank" class="list-group-item">TINGKATAN 4</a>
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
                        <a href="https://drive.google.com/drive/folders/1w5uMsiu9JYBlifgyLFJrUmvKNy9Z3b8M" target="_blank" class="list-group-item">2025</a>
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
                        <a href="#" class="list-group-item">PELAPORAN UASA TINGKATAN 1</a>
                        <a href="#" class="list-group-item">PELAPORAN UASA TINGKATAN 2</a>
                        <a href="#" class="list-group-item">PELAPORAN UASA TINGKATAN 3</a>
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