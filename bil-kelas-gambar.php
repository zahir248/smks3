<?php
$page_title = 'Bilangan Kelas Gambar';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

require_once __DIR__ . '/includes/header.php';

/* =========================
   GET DATA + GROUP BY TINGKATAN
========================= */
$data = $pdo->query("
    SELECT *
    FROM bilangan_kelas
    ORDER BY tingkatan, id DESC
")->fetchAll();

$group = [];
foreach($data as $d){
    $group[$d['tingkatan']][] = $d;
}
?>

<style>
.lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.95);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.lightbox img {
    max-width: 90%;
    max-height: 90%;
    transition: transform 0.15s ease;
    touch-action: none;
}

.lightbox .close {
    position: absolute;
    top: 20px;
    right: 25px;
    font-size: 40px;
    color: #fff;
    cursor: pointer;
}
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1); /* hitam */
}
</style>

<section class="py-5" style="background:#d8f9ff;">
<div class="container">

<div class="text-center mb-5">
    <h2 class="fw-bold">Bilangan Kelas (Gambar)</h2>
    <p class="text-muted">Susunan kelas mengikut tingkatan</p>
</div>

<?php if(!$group): ?>
    <div class="text-center text-muted">
        Tiada data bilangan kelas.
    </div>
<?php endif; ?>

<?php foreach($group as $tingkatan => $items): ?>

<div class="mb-5">

    <h3 class="fw-bold mb-4 text-primary">
        <?= htmlspecialchars($tingkatan) ?>
    </h3>

    <!-- CAROUSEL -->
    <div id="carousel-<?= md5($tingkatan) ?>" class="carousel slide" data-bs-ride="carousel">

        <div class="carousel-inner">

            <?php foreach($items as $index => $item): ?>

            <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

                <div class="text-center">

                    <img src="uploads/bil_kelas/<?= htmlspecialchars($item['image']) ?>"
                         class="img-fluid rounded shadow"
                         style="max-height:420px;object-fit:contain;cursor:pointer;"
                         onclick="openLightbox(this.src)">

                    <h5 class="mt-3 fw-bold">
                        <?= htmlspecialchars($item['title']) ?>
                    </h5>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- controls -->
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

<!-- LIGHTBOX -->
<div class="lightbox" id="lightbox">
    <span class="close" onclick="closeLightbox()">×</span>
    <img id="lightbox-img">
</div>

<script>
function openLightbox(src){
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').style.display = 'flex';
}

function closeLightbox(){
    document.getElementById('lightbox').style.display = 'none';
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>