<?php
$page_title = 'Pra Sekolah';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

/* GET DATA */
$stmt = $pdo->query("SELECT * FROM pra_sekolah LIMIT 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);

$carta = $data['gambar_carta'] ?? '';
$galeri = $data['gambar_galeri'] ?? '';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Carta Organisasi -->
<section class="py-5" style="background:#d8f9ff;">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Carta Organisasi Sekolah</h2>

            <p class="text-muted">
                Carta organisasi ini memaparkan struktur pengurusan sekolah,
                daripada pengetua hingga ke guru-guru.
            </p>
        </div>

        <?php if($carta): ?>
        <div class="text-center">
            <img src="uploads/pra_sekolah/<?= htmlspecialchars($carta) ?>" 
                 alt="Carta Organisasi Sekolah"
                 class="img-fluid rounded shadow"
                 style="width:100%; max-width:1200px; cursor:pointer;"
                 onclick="window.open(this.src, '_blank')">
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Galeri Murid -->
<section class="py-5" style="background:#d8f9ff;">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Galeri Murid</h2>

            <p class="text-muted">
                Beberapa gambar aktiviti dan pelajar sekolah semasa sesi
                pembelajaran dan kokurikulum.
            </p>
        </div>

        <?php if($galeri): ?>
        <div class="text-center">
            <img src="uploads/pra_sekolah/<?= htmlspecialchars($galeri) ?>" 
                 alt="Galeri Murid"
                 class="img-fluid rounded shadow"
                 style="width:100%; max-width:1200px; cursor:pointer;"
                 onclick="window.open(this.src, '_blank')">
        </div>
        <?php endif; ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>