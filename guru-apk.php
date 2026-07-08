<?php
$page_title = 'Barisan Guru Dan AKP';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/admin/config.php';

$settings = getSettings();
require_once __DIR__ . '/includes/header.php';

// =========================
// CONNECT DATA
// =========================
$guru = [];
$akp = [];

try {
    // GURU
    $stmt1 = $pdo->query("SELECT * FROM guru");
    $guru = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // AKP
    $stmt2 = $pdo->query("SELECT * FROM akp");
    $akp = $stmt2->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!-- =========================
    STYLE
========================= -->
<style>
.background-page{
    
}
.staff-card {
    text-align: center;
    margin-bottom: 2rem;
}

.staff-card .image-wrapper {
    width: 100%;
    aspect-ratio: 1 / 1;
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    background: #f5f5f5;
}

.staff-card img {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transform: translate(-50%, -50%);
    transition: transform 0.3s ease;
}

.staff-card:hover img {
    transform: translate(-50%, -50%) scale(1.05);
}

/* 5 COLUMN GRID */
@media (min-width: 992px) {
    .col-5-grid {
        flex: 0 0 20%;
        max-width: 20%;
    }
}

/* tablet */
@media (max-width: 991px) {
    .col-5-grid {
        flex: 0 0 33.33%;
        max-width: 33.33%;
    }
}

/* mobile */
@media (max-width: 576px) {
    .col-5-grid {
        flex: 0 0 50%;
        max-width: 50%;
    }
}
</style>

<!-- =========================
    SECTION GURU
========================= -->
<section class="page-section">
<div class="container">

<h3 class="text-center fw-bold mb-4">Barisan Guru</h3>

<div class="row justify-content-center">

<?php if(count($guru) > 0): ?>
    <?php foreach($guru as $g): ?>
        <div class="col-6 col-md-4 col-lg-3 col-5-grid">
            <div class="staff-card">

                <div class="image-wrapper mb-2">
                    <img src="uploads/<?= htmlspecialchars($g['image']) ?>" 
                         alt="<?= htmlspecialchars($g['nama']) ?>">
                </div>

                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($g['nama']) ?></h6>
                <small class="text-muted"><?= htmlspecialchars($g['dg']) ?></small><br>
                <small><?= htmlspecialchars($g['jawatan']) ?></small>

            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center">Tiada data guru.</p>
<?php endif; ?>

</div>
</div>
</section>

<!-- =========================
    SECTION AKP
========================= -->
<section class="page-section">
<div class="container">

<h3 class="text-center fw-bold mb-4">Barisan AKP</h3>

<div class="row justify-content-center">

<?php if(count($akp) > 0): ?>
    <?php foreach($akp as $a): ?>
        <div class="col-6 col-md-4 col-lg-3 col-5-grid">
            <div class="staff-card">

                <div class="image-wrapper mb-2">
                    <img src="uploads/<?= htmlspecialchars($a['image']) ?>" 
                         alt="<?= htmlspecialchars($a['nama']) ?>">
                </div>

                <h6 class="mb-0 fw-bold"><?= htmlspecialchars($a['nama']) ?></h6>
                <small class="text-muted"><?= htmlspecialchars($a['dg']) ?></small><br>
                <small><?= htmlspecialchars($a['jawatan']) ?></small>

            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center">Tiada data AKP.</p>
<?php endif; ?>

</div>
</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>