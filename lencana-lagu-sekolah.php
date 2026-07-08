<?php
$page_title = 'Lencana & Lagu Sekolah';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$settings = getSettings();
$pdo = getConnection();

/**
 * FETCH DATA FROM DB
 */
$stmt = $pdo->query("SELECT * FROM lencana_lagu_sekolah WHERE id = 1");
$data = $stmt->fetch(PDO::FETCH_ASSOC);

require_once __DIR__ . '/includes/header.php';
?>

<style>
.lencana-img {
    max-width: 220px;
    width: 100%;
    height: auto;
}

@media (max-width: 768px) {
    .lencana-img {
        max-width: 180px;
    }
}

.lagu-sekolah {
    font-size: clamp(0.9rem, 2.5vw, 1.1rem);
    line-height: 1.8;
    border: 1px solid #eee;
}
</style>

<!-- ================= LENCANA ================= -->

<section class="page-section">

    <div class="container">

        <h2 class="text-center fw-bold mb-5">Lencana Sekolah</h2>

        <div class="row align-items-center g-4 bg-white">

            <!-- IMAGE -->
            <div class="col-12 col-md-4 text-center">
                <img 
                    src="images/<?= htmlspecialchars($data['image']) ?>" 
                    class="img-fluid shadow rounded lencana-img"
                >
            </div>

            <!-- CONTENT -->
            <div class="col-12 col-md-8">

                <p class="mb-3">
                    <strong>Moto:</strong> <?= htmlspecialchars($data['moto']) ?>
                </p>

                <ul class="ps-3">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM lencana_item");
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                    ?>
                        <li>
                            <strong><?= htmlspecialchars($row['title']) ?>:</strong>
                            <?= htmlspecialchars($row['description']) ?>
                        </li>
                    <?php endwhile; ?>
                </ul>

            </div>

        </div>

    </div>

</section>

<!-- ================= LAGU ================= -->

<section class="page-section">

    <div class="container">

        <h2 class="text-center fw-bold mb-5">Lagu Sekolah</h2>

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="p-4 bg-light rounded shadow text-center lagu-sekolah">

                    <?= nl2br(htmlspecialchars($data['lirik'])) ?>

                </div>

                <p class="text-center mt-2">
                    <small>
                        Lagu: <?= htmlspecialchars($data['lirik_penggubah']) ?> |
                        Lirik: <?= htmlspecialchars($data['lirik_penulis']) ?>
                    </small>
                </p>

            </div>

        </div>

    </div>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>