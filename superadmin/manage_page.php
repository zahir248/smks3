<?php
session_start();

// 🔒 protect (superadmin sahaja)
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin'){
    header("Location: login.php");
    exit();
}

include "includes/header.php";
?>

<div class="container py-5">

    <!-- Header -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Manage Pages</h2>
        <p class="text-muted">
            Urus semua page website mengikut kategori menu utama.
        </p>
    </div>

    <div class="row g-4">

        <!-- Pengurusan -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="mb-3">
                        <div class="bg-primary bg-opacity-10 d-inline-flex p-3 rounded-3">
                            <i class="bi bi-building fs-3 text-primary"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold">
                        Pengurusan & Pentadbiran
                    </h4>

                    <p class="text-muted small mb-4">
                        Urus page berkaitan pentadbiran sekolah,
                        carta organisasi lain-lain.
                    </p>

                    <!-- submenu preview -->
                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Profil Sekolah
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Barisan Guru dan AKP
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Visi & Misi
                        </li>

                        <li>
                            <i class="bi bi-dot"></i> Sejarah Sekolah
                        </li>
                    </ul>

                    <a href="manage_category.php"
                       class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-gear me-2"></i>
                        Manage
                    </a>

                </div>
            </div>
        </div>

        <!-- Kurikulum -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="mb-3">
                        <div class="bg-success bg-opacity-10 d-inline-flex p-3 rounded-3">
                            <i class="bi bi-book fs-3 text-success"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold">
                        Kurikulum
                    </h4>

                    <p class="text-muted small mb-4">
                        Urus page berkaitan akademik,
                        panitia dan program pembelajaran.
                    </p>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Pentaksiran dan Peperiksaan
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Pusat Sumber Sekolah
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Kecemerlangan Program Akademik
                        </li>

                        <li>
                            <i class="bi bi-dot"></i> Pilihan Mata Pelajaran
                        </li>
                    </ul>
<br>
                    <a href="manage_kurikulum.php"
                       class="btn btn-success w-100 rounded-pill">
                        <i class="bi bi-gear me-2"></i>
                        Manage
                    </a>

                </div>
            </div>
        </div>

        <!-- HEM -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">

                    <div class="mb-3">
                        <div class="bg-warning bg-opacity-10 d-inline-flex p-3 rounded-3">
                            <i class="bi bi-people fs-3 text-warning"></i>
                        </div>
                    </div>

                    <h4 class="fw-bold">
                        Hal Ehwal Murid
                    </h4>

                    <p class="text-muted small mb-4">
                        Urus page berkaitan disiplin,
                        kokurikulum dan kebajikan pelajar.
                    </p>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Enrolmen Murid
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Bilangan Kelas (Gambar)
                        </li>

                        <li class="mb-2">
                            <i class="bi bi-dot"></i> Unit Kaunseling 
                        </li>

                        <li>
                            <i class="bi bi-dot"></i> Pemimpin Murid
                        </li>
                    </ul>
<br>
                    <a href="manage_hem.php"
                       class="btn btn-warning w-100 rounded-pill text-dark">
                        <i class="bi bi-gear me-2"></i>
                        Manage
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>