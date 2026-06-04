<?php
$page_title = 'Kalendar Akademik 2026';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();

/**
 * GET PAGE CONTENT
 */
function getPage($key)
{
    $pdo = getConnection();

    $stmt = $pdo->prepare("
        SELECT * 
        FROM pages 
        WHERE page_key = ?
    ");

    $stmt->execute([$key]);

    return $stmt->fetch();
}

$page = getPage('kalendar_akademik');

require_once __DIR__ . '/includes/header.php';
?>

<style>

/* TEXT CONTROL */
.akademik-content {
    font-size: clamp(0.9rem, 1.2vw, 1rem);
    line-height: 1.7;
    overflow-x: auto;
}

/* TABLE */
.akademik-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
    font-size: clamp(0.8rem, 1vw, 0.95rem);
}

/* TABLE CELL */
.akademik-content th,
.akademik-content td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

/* TABLE HEADER */
.akademik-content th {
    background: #0B3C5D;
    color: #fff;
}

/* MOBILE */
@media (max-width: 768px) {

    .akademik-content {
        font-size: 0.9rem;
    }

    .akademik-content table {
        font-size: 0.75rem;
    }

    .akademik-content th,
    .akademik-content td {
        padding: 6px;
    }
}

/* EXTRA SMALL */
@media (max-width: 576px) {

    .akademik-content table {
        font-size: 0.7rem;
    }
}

</style>

<section class="py-5" style="background:#d8f9ff;">

    <div class="container">

        <h2 class="text-center fw-bold mb-4">
            Kalendar Akademik 2026
        </h2>

        <p class="text-muted mb-4">
            <strong>Kumpulan B:</strong>
            Johor, Melaka, Negeri Sembilan, Pahang, Perak,
            Perlis, Pulau Pinang, Sabah, Sarawak, Selangor,
            Wilayah Persekutuan KL, Labuan & Putrajaya
        </p>

        <!-- DYNAMIC CONTENT -->
        <div class="akademik-content">
            <?= $page['content']; ?>
        </div>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<?php
$page_title = 'Kalendar Akademik 2026';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

/* FETCH DATA */
$stmt = $pdo->query("
    SELECT * 
    FROM academic_calendar 
    ORDER BY sort_order ASC, start_date ASC
");

$data = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5" style="background:#d8f9ff;">
<div class="container">

    <h2 class="text-center fw-bold mb-4">
        Kalendar Akademik 2026
    </h2>

    <p class="text-muted mb-4">
        <strong>Kumpulan B:</strong>
        Johor, Melaka, Negeri Sembilan, Pahang, Perak,
        Perlis, Pulau Pinang, Sabah, Sarawak, Selangor,
        Wilayah Persekutuan KL, Labuan & Putrajaya
    </p>

    <?php if(empty($data)): ?>
        <div class="text-center text-muted">
            Tiada data kalendar akademik.
        </div>
    <?php endif; ?>

    <?php foreach($data as $row): ?>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">

                <!-- PDF EMBED (MAIN PART) -->
                <?php if(!empty($row['file_pdf'])): ?>
                    <div class="mt-3">

                        <div class="ratio ratio-16x9">
                            <iframe 
                                src="uploads/kalendar/<?= htmlspecialchars($row['file_pdf']) ?>"
                                style="border:1px solid #ddd; border-radius:8px;">
                            </iframe>
                        </div>

                    </div>
                <?php else: ?>
                    <div class="text-muted mt-3">
                        Tiada PDF disediakan.
                    </div>
                <?php endif; ?>

            </div>
        </div>

    <?php endforeach; ?>

</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>