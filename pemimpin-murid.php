<?php
$page_title = 'Pemimpin Murid';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

$stmt = $pdo->query('SELECT * FROM pemimpin_murid ORDER BY id ASC');
$db_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$static_image = is_file(__DIR__ . '/images/barisanmpp.JPG') ? 'images/barisanmpp.JPG' : '';

require_once __DIR__ . '/includes/header.php';
?>
<style>
.pemimpin-info-card {
    transition: all 0.3s ease;
    border-radius: 15px;
}

.pemimpin-info-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.pemimpin-icon-box {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.pemimpin-photo {
    max-width: 1000px;
    width: 100%;
    cursor: pointer;
}
</style>

<section class="page-section" id="barisan">
    <div class="container">
        <h2 class="fw-bold text-center mb-4">Barisan Pemimpin Murid</h2>

        <p class="text-center text-muted lead mb-3">
            Barisan kepimpinan murid yang berwibawa, berdisiplin dan komited dalam membantu pengurusan serta pembangunan sahsiah pelajar di sekolah.
        </p>

        <div class="text-center mb-4">
            <a href="#info" class="btn btn-outline-primary btn-sm">Maklumat Lanjut</a>
        </div>

        <div class="text-center">
            <?php if (!empty($db_images)) : ?>
                <?php foreach ($db_images as $img) :
                    $src = 'uploads/pemimpin_murid/' . ($img['image'] ?? '');
                    if (!is_file(__DIR__ . '/' . $src)) {
                        continue;
                    }
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <img
                    src="<?= $srcEsc ?>"
                    alt="Barisan Pemimpin Murid"
                    class="img-fluid rounded shadow mb-4 pemimpin-photo"
                    onclick="window.open(this.src, '_blank')">
                <?php endforeach; ?>
            <?php elseif ($static_image !== '') : ?>
                <a href="<?= htmlspecialchars($static_image, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer">
                    <img
                        src="<?= htmlspecialchars($static_image, ENT_QUOTES, 'UTF-8') ?>"
                        alt="Barisan Pemimpin Murid"
                        class="img-fluid rounded shadow pemimpin-photo">
                </a>
            <?php endif; ?>

            <p class="text-muted mt-3">
                Klik pada gambar untuk paparan lebih jelas.
            </p>
        </div>
    </div>
</section>

<section class="page-section" id="info">
    <div class="container">
        <h3 class="fw-bold text-center mb-5">Maklumat Berkaitan</h3>

        <div class="row g-4">
            <div class="col-md-4">
                <a href="images/CARTA ORGANISASI PENGAWAS.pdf" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 pemimpin-info-card">
                        <div class="pemimpin-icon-box bg-danger text-white mx-auto mb-3">
                            <i class="bi bi-file-earmark-pdf-fill" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Carta Organisasi</h5>
                        <p class="text-muted small">
                            Lihat struktur kepimpinan pengawas sekolah dalam format PDF.
                        </p>
                        <span class="btn btn-outline-danger mt-2">Buka Dokumen</span>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="https://nextgenleaders3.my.canva.site/" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 pemimpin-info-card">
                        <div class="pemimpin-icon-box bg-success text-white mx-auto mb-3">
                            <i class="bi bi-globe" aria-hidden="true"></i>
                        </div>
                        <h5 class="fw-bold text-dark">NextGen Leaders 3</h5>
                        <p class="text-muted small">
                            Portal rasmi program pembangunan kepimpinan murid.
                        </p>
                        <span class="btn btn-outline-success mt-2">Lawati Portal</span>
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="images/SENARAI NAMA MPP 2026.pdf" target="_blank" rel="noopener noreferrer" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-lg text-center p-4 pemimpin-info-card">
                        <div class="pemimpin-icon-box bg-warning text-white mx-auto mb-3">
                            <i class="bi bi-file-earmark-text-fill" aria-hidden="true"></i>
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
