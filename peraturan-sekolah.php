<?php
$page_title = 'Peraturan Sekolah';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

$stmt = $pdo->query('SELECT * FROM peraturan_sekolah ORDER BY id ASC');
$db_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$static_images = [];
foreach (['images/peraturansekolah1.jpeg', 'images/peraturansekolah2.jpeg'] as $path) {
    if (is_file(__DIR__ . '/' . $path)) {
        $static_images[] = $path;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<style>
.peraturan-img {
    width: 100%;
    max-width: 1000px;
    cursor: pointer;
}
</style>

<section class="page-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Peraturan Sekolah</h2>
            <p class="text-muted">
                Berikut merupakan garis panduan dan peraturan yang perlu dipatuhi oleh semua pelajar
                bagi memastikan disiplin dan suasana pembelajaran yang kondusif di sekolah.
            </p>
        </div>

        <div class="row justify-content-center g-4">
            <?php if (!empty($db_images)) : ?>
                <?php foreach ($db_images as $img) :
                    $src = 'uploads/peraturan/' . ($img['image'] ?? '');
                    if (!is_file(__DIR__ . '/' . $src)) {
                        continue;
                    }
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col-12 text-center">
                    <img
                        src="<?= $srcEsc ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        onclick="smks3OpenMediaOverlay(this.src)">
                </div>
                <?php endforeach; ?>
            <?php else : ?>
                <?php foreach ($static_images as $src) :
                    $srcEsc = htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
                ?>
                <div class="col-12 text-center">
                    <img
                        src="<?= $srcEsc ?>"
                        alt="Peraturan Sekolah"
                        class="img-fluid rounded shadow peraturan-img"
                        onclick="smks3OpenMediaOverlay(this.src)">
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="alert alert-warning text-center mt-4">
            Maklumat lanjut akan dikemaskini dari semasa ke semasa.
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
