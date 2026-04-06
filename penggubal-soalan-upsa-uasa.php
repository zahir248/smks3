<?php
$page_title = 'Penggubal Soalan UPSA & UASA';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Unit Peperiksaan Dalaman -->
<section class="py-5">
    <div class="container">
        <p class="text-center text-muted lead mb-4">
            
        </p>

        <div class="text-center mb-5">
            <h2 class="fw-bold">Penggubal Soalan UPSA & UASA</h2>
            <p class="text-muted">
                
            </p>
        </div>

        <div class="row">

    <!-- CARD 1 -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm card-hover h-100">
            <div class="card-body text-center p-4">

                <i class="bi bi-folder2-open text-primary display-5 mb-3"></i>
                <h5 class="mb-3">PENGGUBAL SOALAN 2025</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse1">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse1">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">ANALISIS TINGKATAN 1</a>
                        <a href="#" class="list-group-item">ANALISIS TINGKATAN 2</a>
                        <a href="#" class="list-group-item">ANALISIS TINGKATAN 3</a>
                        <a href="#" class="list-group-item">ANALISIS TINGKATAN 4</a>
                        <a href="#" class="list-group-item">ANALISIS TINGKATAN 5</a>
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
                <h5 class="mb-3">PENGGUBAL SOALAN 2026</h5>

                <button class="btn btn-outline-primary"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse2">
                    Lihat Bahagian
                </button>

                <div class="collapse mt-4" id="collapse2">
                    <div class="list-group text-start">
                        <a href="#" class="list-group-item">TINGKATAN 1</a>
                        <a href="#" class="list-group-item">TINGKATAN 2</a>
                        <a href="#" class="list-group-item">TINGKATAN 3</a>
                        <a href="#" class="list-group-item">TINGKATAN 4</a>
                        <a href="#" class="list-group-item">TINGKATAN 5</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>