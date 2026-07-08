<?php
$page_title = 'Kalendar Akademik 2026';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

$stmt = $pdo->prepare('SELECT * FROM pages WHERE page_key = ?');
$stmt->execute(['kalendar_akademik']);
$page = $stmt->fetch();

$stmt = $pdo->query('
    SELECT *
    FROM academic_calendar
    ORDER BY sort_order ASC, start_date ASC
');
$calendar_pdfs = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<style>
.akademik-content {
    font-size: clamp(0.9rem, 1.2vw, 1rem);
    line-height: 1.7;
    overflow-x: auto;
}

.akademik-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1.5rem;
    font-size: clamp(0.8rem, 1vw, 0.95rem);
}

.akademik-content th,
.akademik-content td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
}

.akademik-content th {
    background: #0B3C5D;
    color: #fff;
}

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

@media (max-width: 576px) {
    .akademik-content table {
        font-size: 0.7rem;
    }
}
</style>

<section class="page-section">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Kalendar Akademik 2026</h2>

        <p class="text-muted mb-4">
            <strong>Kumpulan B:</strong>
            Johor, Melaka, Negeri Sembilan, Pahang, Perak,
            Perlis, Pulau Pinang, Sabah, Sarawak, Selangor,
            Wilayah Persekutuan KL, Labuan &amp; Putrajaya
        </p>

        <?php if (!empty($page['content'])) : ?>
        <div class="akademik-content">
            <?= $page['content'] ?>
        </div>
        <?php endif; ?>

        <?php foreach ($calendar_pdfs as $row) : ?>
            <?php if (empty($row['file_pdf'])) {
                continue;
            } ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <div class="ratio ratio-16x9">
                    <iframe
                        src="uploads/kalendar/<?= htmlspecialchars($row['file_pdf'], ENT_QUOTES, 'UTF-8') ?>"
                        title="Kalendar Akademik PDF"
                        style="border:1px solid #ddd; border-radius:8px;">
                    </iframe>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($page['content']) && empty($calendar_pdfs)) : ?>
        <div class="text-center text-muted">
            Tiada data kalendar akademik.
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
