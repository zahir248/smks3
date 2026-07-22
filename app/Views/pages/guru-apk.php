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

/**
 * Pick cards per row (desktop) while keeping the original 5-column card width.
 * Only 3–5 cards fit per row at that fixed size; prefers an even last row.
 */
$smks3_staff_grid_cols = static function (int $total, int $min = 3, int $max = 5): int {
    if ($total < 1) {
        return $max;
    }
    if ($total <= $min) {
        return $total;
    }

    for ($cols = $max; $cols >= $min; $cols--) {
        if ($total % $cols === 0) {
            return $cols;
        }
    }

    $best = $max;
    $bestEmpty = PHP_INT_MAX;
    for ($cols = $max; $cols >= $min; $cols--) {
        $rem = $total % $cols;
        $empty = $rem === 0 ? 0 : ($cols - $rem);
        if ($empty < $bestEmpty) {
            $bestEmpty = $empty;
            $best = $cols;
        }
    }

    return $best;
};

$guruCols = $smks3_staff_grid_cols(count($guru));
$akpCols = $smks3_staff_grid_cols(count($akp));
$guruRows = $guru !== [] ? array_chunk($guru, $guruCols) : [];
$akpRows = $akp !== [] ? array_chunk($akp, $akpCols) : [];
?>

<!-- =========================
    STYLE
========================= -->
<style>
.staff-card {
    text-align: center;
    margin-bottom: 0;
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

.staff-grid {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
}

.staff-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    width: 100%;
    margin-bottom: 2rem;
}

.staff-col {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 0.75rem;
}

@media (min-width: 577px) and (max-width: 991px) {
    .staff-col {
        flex: 0 0 33.333%;
        max-width: 33.333%;
    }
}

@media (min-width: 992px) {
    .staff-col {
        flex: 0 0 20%;
        max-width: 20%;
    }
}
</style>

<!-- =========================
    SECTION GURU
========================= -->
<section class="page-section">
<div class="container">

<h3 class="text-center fw-bold mb-4">Barisan Guru</h3>

<div class="staff-grid">

<?php if ($guruRows !== []): ?>
    <?php foreach ($guruRows as $row): ?>
        <div class="staff-row">
            <?php foreach ($row as $g): ?>
                <div class="staff-col">
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

                        <h6 class="mb-0 fw-bold" data-bind="staff_nama"><?= htmlspecialchars($g['nama']) ?></h6>
                        <small class="text-muted" data-bind="staff_dg"><?= htmlspecialchars($g['dg']) ?></small><br>
                        <small data-bind="staff_jawatan"><?= htmlspecialchars($g['jawatan']) ?></small>

                    </div>
                </div>
            <?php endforeach; ?>
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

<div class="staff-grid">

<?php if ($akpRows !== []): ?>
    <?php foreach ($akpRows as $row): ?>
        <div class="staff-row">
            <?php foreach ($row as $a): ?>
                <div class="staff-col">
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

                        <h6 class="mb-0 fw-bold" data-bind="staff_nama"><?= htmlspecialchars($a['nama']) ?></h6>
                        <small class="text-muted" data-bind="staff_dg"><?= htmlspecialchars($a['dg']) ?></small><br>
                        <small data-bind="staff_jawatan"><?= htmlspecialchars($a['jawatan']) ?></small>

                    </div>
                </div>
            <?php endforeach; ?>
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
