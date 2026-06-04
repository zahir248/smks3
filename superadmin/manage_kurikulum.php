<?php
session_start();

/* 🔒 superadmin only */
if(
    !isset($_SESSION['username']) || 
    $_SESSION['role'] !== 'superadmin'
){
    header("Location: login.php");
    exit();
}

include "includes/header.php";
?>

<style>

.back-btn{
    display:inline-block;
    margin-bottom:15px;
    text-decoration:none;
    color:#0d9488;
    font-weight:600;
    transition:0.2s;
}

.back-btn:hover{
    color:#115e59;
    transform:translateX(-3px);
}

</style>

<div class="container py-5">

    <!-- BACK BUTTON -->
    <a href="manage_page.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- TITLE -->
    <div class="text-center mb-5">
        <h2 class="fw-bold">Pengurusan Kurikulum</h2>

        <p class="text-muted">
            Urus page berkaitan akademik, panitia dan program pembelajaran.
        </p>
    </div>

    <div class="row g-4">

        <!-- AKADEMIK -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Akademik & Pentaksiran
                    </h5>

                    <a href="pentaksiran_peperiksaan.php" 
                       class="btn btn-outline-primary w-100 mb-2">
                        Pentaksiran & Peperiksaan
                    </a>

                    <a href="pilihan_mata_pelajaran.php" 
                       class="btn btn-outline-primary w-100 mb-2">
                        Pilihan Mata Pelajaran
                    </a>

                    <a href="pra_sekolah.php" 
                       class="btn btn-outline-primary w-100">
                        Pra Sekolah
                    </a>

                </div>

            </div>
        </div>

        <!-- PUSAT SUMBER -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">
                        Pusat Sumber & Program
                    </h5>

                    <a href="pusat_sumber.php" 
                       class="btn btn-outline-success w-100 mb-2">
                        Pusat Sumber Sekolah
                    </a>

                    <a href="kecemerlangan_akademik.php" 
                       class="btn btn-outline-success w-100 mb-2">
                        Kecemerlangan Program Akademik
                    </a>

                </div>

            </div>
        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>