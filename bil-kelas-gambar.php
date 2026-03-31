<?php
$page_title = 'Bilangan Kelas Gambar';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Pilihan Mata Pelajaran -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Bilangan Kelas (Gambar)</h2>
            <p class="text-muted">Senarai bilangan kelas (gambar).
            <br>Untuk sementara, tiada data tersedia.</p>
        </div>

        <?php if(false): // Ganti false dengan query data mata pelajaran sebenar ?>
            <div class="row g-4 justify-content-center">
                <!-- Loop data mata pelajaran di sini -->
                <!-- Contoh: -->
                <!--
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <h5>Mata Pelajaran XYZ</h5>
                            <p class="text-muted">Deskripsi ringkas mata pelajaran.</p>
                        </div>
                    </div>
                </div>
                -->
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">
                Tiada data lagi.
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>