<?php
session_start();

// ðŸ”’ superadmin only
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
        <h2 class="fw-bold">Pengurusan Hal Ehwal Murid</h2>
        <p class="text-muted">Urus page berkaitan disiplin, kokurikulum dan kebajikan pelajar.</p>
    </div>

    <div class="row g-4">

    <!-- HAL EHWAL MURID -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-4">
    
                <h5 class="fw-bold mb-3">Hal Ehwal Murid</h5>
    
                <p class="text-muted small mb-4">
                    Urus page berkaitan disiplin, kokurikulum dan kebajikan pelajar.
                </p>
    
                <a href="enrolmen_murid.php" class="btn btn-outline-danger w-100 mb-2">
                    Enrolmen Murid
                </a>
    
                <a href="bilangan_kelas.php" class="btn btn-outline-danger w-100 mb-2">
                    Bilangan Kelas (Gambar)
                </a>
    
                <a href="unit_kaunseling.php" class="btn btn-outline-danger w-100 mb-2">
                    Unit Bimbingan Kaunseling
                </a>
                
                <a href="peraturan_sekolah.php" class="btn btn-outline-danger w-100 mb-2">
                    Peraturan Sekolah
                </a>
    
                <a href="pemimpin_murid.php" class="btn btn-outline-danger w-100">
                    Pemimpin Murid
                </a>
    
            </div>
        </div>
    </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>