<?php
session_start();

// 🔒 superadmin only
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'superadmin'){
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
    
    <a href="manage_page.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <div class="text-center mb-5">
        <h2 class="fw-bold">Pengurusan Kandungan Sekolah</h2>
        <p class="text-muted">Klik untuk edit setiap halaman</p>
    </div>

    <div class="row g-4">

        <!-- PROFIL SEKOLAH -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Profil Sekolah</h5>

                    <a href="profil_sekolah.php" class="btn btn-outline-primary w-100 mb-2">
                        Profil Sekolah
                    </a>

                    <a href="misi_visi.php" class="btn btn-outline-primary w-100 mb-2">
                        Misi & Visi
                    </a>

                    <a href="sejarah_sekolah.php" class="btn btn-outline-primary w-100 mb-2">
                        Sejarah Sekolah
                    </a>

                    <a href="senarai_pengetua.php" class="btn btn-outline-primary w-100 mb-2">
                        Senarai Pengetua
                    </a>

                    <a href="pelan_sekolah.php" class="btn btn-outline-primary w-100 mb-2">
                        Pelan Sekolah
                    </a>

                    <a href="lencana_lagu.php" class="btn btn-outline-primary w-100">
                        Lencana & Lagu Sekolah
                    </a>

                </div>
            </div>
        </div>

        <!-- PENTADBIRAN -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Pentadbiran</h5>

                    <a href="pengurusan_tertinggi.php" class="btn btn-outline-success w-100 mb-2">
                        Pengurusan Tertinggi
                    </a>

                    <a href="barisan_guru_akp.php" class="btn btn-outline-success w-100">
                        Barisan Guru & AKP
                    </a>

                </div>
            </div>
        </div>

        <!-- AKADEMIK -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">Akademik</h5>

                    <a href="kalendar_akademik.php" class="btn btn-outline-warning w-100 mb-2">
                        Kalendar Akademik
                    </a>

                    <a href="cuti_perayaan.php" class="btn btn-outline-warning w-100">
                        Cuti Perayaan
                    </a>

                </div>
            </div>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>