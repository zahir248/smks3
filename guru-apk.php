<?php
$page_title = 'Guru APK';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/admin/config.php'; // <--- tambah ini
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';

// Ambil data guru APK dari database
// Anggapkan jadual `guru` ada: id, nama, jawatan, dg, image, kategori
$guru_apk = [];
$sql = "SELECT * FROM guru";
$result = mysqli_query($conn, $sql);
if($result){
    while($row = mysqli_fetch_assoc($result)){
        $guru_apk[] = $row;
    }
}
?>

<section class="hero text-white py-5">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold">Guru AKP Sekolah Menengah Kebangsaan Seremban 3</h1>
        <p class="lead">Kenali barisan guru AKP sekolah kita.</p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <style>
            .pengurusan-card {
                text-align: center;
                margin-bottom: 2rem;
                cursor: pointer;
            }
            .pengurusan-card .image-wrapper {
                width: 100%;
                padding-top: 100%;
                position: relative;
                overflow: hidden;
            }
            .pengurusan-card img {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                height: 100%;
                object-fit: contain;
                transform: translate(-50%, -50%);
                transition: transform 0.3s, box-shadow 0.3s;
            }
            .pengurusan-card:hover img {
                transform: translate(-50%, -50%) scale(1.05);
                box-shadow: 0 8px 25px rgba(11,60,93,0.2);
            }
            .pengurusan-card h5 {
                margin-top: 0.5rem;
                transition: color 0.3s;
            }
            .pengurusan-card:hover h5 {
                color: #0B3C5D;
            }
        </style>

        <div class="row justify-content-center">
            <?php if(count($guru_apk) > 0): ?>
                <?php foreach($guru_apk as $guru): ?>
                <div class="col-md-3 pengurusan-card">
                    <div class="image-wrapper">
                        <img src="uploads/<?= htmlspecialchars($guru['image']) ?>" alt="<?= htmlspecialchars($guru['nama']) ?>">
                    </div>
                    <h5><?= htmlspecialchars($guru['nama']) ?></h5>
                    <h5><?= htmlspecialchars($guru['dg']) ?></h5>
                    <h5><?= htmlspecialchars($guru['jawatan']) ?></h5>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Tiada data guru AKP.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>