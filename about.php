<?php
$page_title = 'Perihal Kami';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="h3 fw-bold mb-4">Perihal <?= htmlspecialchars($settings['school_name']) ?></h2>
        <?php if (!empty($settings['tagline'])) : ?>
        <p class="lead text-muted"><?= htmlspecialchars($settings['tagline']) ?></p>
        <?php endif; ?>
        <div class="row mt-4">
            <div class="col-lg-8">
                <p><?= nl2br(htmlspecialchars($settings['about_summary'] ?? 'SMK S3 ialah sekolah menengah yang komited menyediakan pendidikan vokasional berkualiti dan siap bekerja.')) ?></p>
                <p>Kami mengendalikan program pendidikan vokasional yang bersepadu dengan keperluan industri. Pelajar dibekalkan kemahiran teknikal dan soft skill agar siap bersaing di dunia pekerjaan mahupun melanjutkan ke institusi pengajian tinggi.</p>
                <h5 class="mt-4">Visi</h5>
                <p>Menjadi sekolah vokasional unggul yang melahirkan graduan berkompeten, berakhlak mulia, dan siap bersaing di era global.</p>
                <h5>Misi</h5>
                <ul>
                    <li>Mengendalikan pendidikan vokasional yang bermutu dan relevan dengan dunia pekerjaan</li>
                    <li>Membina karakter dan akhlak pelajar</li>
                    <li>Memperkukuh kerjasama dengan industri dan dunia perniagaan</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-3">Hubungi</h6>
                        <p class="mb-2"><i class="bi bi-geo-alt text-primary me-2"></i><?= htmlspecialchars($settings['address'] ?? '') ?></p>
                        <p class="mb-2"><i class="bi bi-telephone text-primary me-2"></i><?= htmlspecialchars($settings['phone'] ?? '') ?></p>
                        <p class="mb-0"><i class="bi bi-envelope text-primary me-2"></i><?= htmlspecialchars($settings['email'] ?? '') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
