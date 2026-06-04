<?php
$page_title = 'Pilihan Mata Pelajaran';

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$pdo = getConnection();

$stmt = $pdo->query("
    SELECT * 
    FROM pilihan_mata_pelajaran 
    ORDER BY id DESC 
    LIMIT 1
");

$data = $stmt->fetch();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5" style="background:#d8f9ff;">
<div class="container">

    <h2 class="text-center fw-bold mb-4">
        Pilihan Mata Pelajaran
    </h2>
            <p class="text-muted">Senarai mata pelajaran yang boleh dipilih oleh pelajar mengikut tingkatan dan aliran. </p>

    <?php if(!empty($data['file_pdf'])): ?>

        <div class="card shadow-sm border-0">

            <div class="card-body p-0">

                <iframe 
                    src="uploads/pilihan_mata_pelajaran/<?= $data['file_pdf'] ?>"
                    width="100%"
                    height="800px"
                    style="border:none;">
                </iframe>

            </div>

        </div>

    <?php else: ?>

        <div class="alert alert-info text-center">
            Tiada PDF dimuat naik lagi
        </div>

    <?php endif; ?>

</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>