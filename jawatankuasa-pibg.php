<?php
$page_title = 'Jawatankuasa PIBG';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Pilihan Mata Pelajaran -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Jawatankuasa PIBG</h2>
            <p class="text-muted">Senarai mata pelajaran yang boleh dipilih oleh pelajar mengikut tingkatan dan aliran. </p>
        </div>

    <div class="text-center mb-4">
        <a href="files/pibg.pdf" target="_blank" class="btn btn-primary">
            Buka PDF di Tab Baru
        </a>
    </div>
    
    <div class="ratio ratio-4x3 shadow rounded">
        <iframe src="images/SENARAI AJK PIBG SESI 2026.docx.pdf"></iframe>
    </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>