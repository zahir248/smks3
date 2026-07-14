<?php
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
            <div class="staff-card"
                 <?php if ($is_editor): ?>
                 data-edit-block="guru_item"
                 data-edit-label="Sunting guru"
                 data-id="<?= (int) $g['id'] ?>"
                 data-nama="<?= htmlspecialchars((string) $g['nama'], ENT_QUOTES, 'UTF-8') ?>"
                 data-jawatan="<?= htmlspecialchars((string) ($g['jawatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 data-dg="<?= htmlspecialchars((string) ($g['dg'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>

                <div class="image-wrapper mb-2">
                    <img src="<?= !empty($g['image']) ? 'uploads/' . htmlspecialchars($g['image']) : htmlspecialchars($placeholderImage ?? '/smks3/images/placeholder.png') ?>"
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
<?php if ($is_editor): ?>
<div class="text-center mt-2 mb-2">
    <button type="button" class="btn btn-outline-primary btn-sm"
            data-edit-block="guru_add"
            data-edit-label="Tambah guru"
            data-edit-hint="Tambah guru baharu.">
        <i class="bi bi-plus-lg me-1"></i> Tambah Guru
    </button>
</div>
<?php endif; ?>
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
            <div class="staff-card"
                 <?php if ($is_editor): ?>
                 data-edit-block="akp_item"
                 data-edit-label="Sunting AKP"
                 data-id="<?= (int) $a['id'] ?>"
                 data-nama="<?= htmlspecialchars((string) $a['nama'], ENT_QUOTES, 'UTF-8') ?>"
                 data-jawatan="<?= htmlspecialchars((string) ($a['jawatan'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 data-dg="<?= htmlspecialchars((string) ($a['dg'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>>

                <div class="image-wrapper mb-2">
                    <img src="<?= !empty($a['image']) ? 'uploads/' . htmlspecialchars($a['image']) : htmlspecialchars($placeholderImage ?? '/smks3/images/placeholder.png') ?>"
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
<?php if ($is_editor): ?>
<div class="text-center mt-2 mb-2">
    <button type="button" class="btn btn-outline-primary btn-sm"
            data-edit-block="akp_add"
            data-edit-label="Tambah AKP"
            data-edit-hint="Tambah AKP baharu.">
        <i class="bi bi-plus-lg me-1"></i> Tambah AKP
    </button>
</div>
<?php endif; ?>
</div>
</section>
