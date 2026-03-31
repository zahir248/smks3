<?php
$page_title = 'Carta Organisasi & Galeri';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Carta Organisasi -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Carta Organisasi Sekolah</h2>
            <p class="text-muted">Carta organisasi ini memaparkan struktur pengurusan sekolah, daripada pengetua hingga ke guru-guru.</p>
        </div>

        <?php if(false): // Ganti false dengan query data guru sebenar ?>
            <div class="row justify-content-center">
                <!-- Loop data guru di sini -->
            </div>
        <?php else: ?>
            <div class="alert alert-info text-center">
                Tiada data guru atau pengetua lagi.
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Galeri Murid -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Galeri Murid</h2>
            <p class="text-muted">Beberapa gambar aktiviti dan pelajar sekolah semasa sesi pembelajaran dan kokurikulum.</p>
        </div>

        <?php if(false): // Ganti false dengan query data murid sebenar ?>
            <div class="row g-4 justify-content-center">
                <!-- Loop gambar murid di sini -->
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Tiada data murid lagi.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>