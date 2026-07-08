<?php
$page_title = 'Bilangan Kelas Gambar';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

require_once __DIR__ . '/includes/header.php';

$data = $pdo->query('
    SELECT *
    FROM bilangan_kelas
    ORDER BY tingkatan, id DESC
')->fetchAll();

$group = [];
foreach ($data as $d) {
    $group[$d['tingkatan']][] = $d;
}
?>

<style>
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1);
}
</style>

<section class="page-section">
<div class="container">

<div class="text-center mb-5">
    <h2 class="fw-bold">Bilangan Kelas (Gambar)</h2>
    <p class="text-muted">Susunan kelas mengikut tingkatan</p>
</div>

<?php if (!$group) : ?>
    <div class="text-center text-muted">
        Tiada data bilangan kelas.
    </div>
<?php endif; ?>

<?php foreach ($group as $tingkatan => $items) : ?>

<div class="mb-5">
    <h3 class="fw-bold mb-4 text-primary">
        <?= htmlspecialchars($tingkatan) ?>
    </h3>

    <div id="carousel-<?= md5($tingkatan) ?>" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($items as $index => $item) : ?>
            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                <div class="text-center">
                    <img src="uploads/bil_kelas/<?= htmlspecialchars($item['image']) ?>"
                         class="img-fluid rounded shadow"
                         style="max-height:420px;object-fit:contain;cursor:pointer;"
                         onclick="smks3OpenMediaOverlay(this.src)"
                         alt="<?= htmlspecialchars($item['title']) ?>">
                    <h5 class="mt-3 fw-bold">
                        <?= htmlspecialchars($item['title']) ?>
                    </h5>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button"
                data-bs-target="#carousel-<?= md5($tingkatan) ?>"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
                data-bs-target="#carousel-<?= md5($tingkatan) ?>"
                data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>

<?php endforeach; ?>

</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
