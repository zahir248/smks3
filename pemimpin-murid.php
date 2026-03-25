<?php
$page_title = 'Pemimpin Murid';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>
<style>
    .info-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.info-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}
</style>
<!-- Gambar Barisan Pemimpin Murid -->
<section class="py-5 bg-light" id="barisan">
    <div class="container">
        <p class="text-center text-muted lead mb-3">Barisan kepimpinan murid yang berwibawa, berdisiplin dan komited dalam membantu pengurusan serta pembangunan sahsiah pelajar di sekolah.</p>
        <div class="text-center mb-4">
            <a href="#info" class="btn btn-outline-primary btn-sm">Maklumat Lanjut</a>
        </div>
        <h2 class="fw-bold text-center mb-4">Barisan Pemimpin Murid</h2>

        <div class="text-center">
            <a href="images/barisanmpp.JPG" target="_blank">
                <img src="images/barisanmpp.JPG" 
                     alt="Barisan Pemimpin Murid" 
                     class="img-fluid rounded shadow">
            </a>
            <p class="text-muted mt-3">
                Klik pada gambar untuk paparan lebih jelas.
            </p>
        </div>
    </div>
</section>

<!-- Maklumat Berkaitan -->
<section class="py-5 bg-light" id="info">
    <div class="container">
        <h3 class="fw-bold text-center mb-5">Maklumat Berkaitan</h3>

        <div class="row g-4">

            <!-- Carta Organisasi -->
            <div class="col-md-4">
                <a href="images/CARTA ORGANISASI PENGAWAS.pdf" target="_blank" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 info-card">
                        <div class="icon-box bg-danger text-white mx-auto mb-3">
                            <i class="bi bi-file-earmark-pdf-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Carta Organisasi</h5>
                        <p class="text-muted small">
                            Lihat struktur kepimpinan pengawas sekolah dalam format PDF.
                        </p>
                        <span class="btn btn-outline-danger mt-2">Buka Dokumen</span>
                    </div>
                </a>
            </div>

            <!-- NextGen Leaders -->
            <div class="col-md-4">
                <a href="https://nextgenleaders3.my.canva.site/" target="_blank" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 info-card">
                        <div class="icon-box bg-success text-white mx-auto mb-3">
                            <i class="bi bi-globe"></i>
                        </div>
                        <h5 class="fw-bold text-dark">NextGen Leaders 3</h5>
                        <p class="text-muted small">
                            Portal rasmi program pembangunan kepimpinan murid.
                        </p>
                        <span class="btn btn-outline-success mt-2">Lawati Portal</span>
                    </div>
                </a>
            </div>

            <!-- Senarai Nama MPP -->
            <div class="col-md-4">
                <a href="images/SENARAI NAMA MPP 2026.pdf" target="_blank" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 info-card">
                        <div class="icon-box bg-warning text-white mx-auto mb-3">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Senarai Nama MPP</h5>
                        <p class="text-muted small">
                            Senarai penuh Majlis Perwakilan Pelajar 2026.
                        </p>
                        <span class="btn btn-outline-warning mt-2">Lihat Senarai</span>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>