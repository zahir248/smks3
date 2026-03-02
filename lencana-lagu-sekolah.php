<?php
$page_title = 'Lencana & Lagu Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="display-4 fw-bold">Lencana & Lagu Sekolah</h1>
                <p class="lead">Kenali lencana dan lagu rasmi Sekolah Menengah Kebangsaan Seremban 3.</p>
            </div>
            <div class="col-lg-5 text-center d-none d-lg-block">
                <i class="bi bi-award-fill opacity-50" style="font-size: 7rem;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Lencana Sekolah Section -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Lencana Sekolah</h2>
        <div class="row align-items-center g-4">
            <div class="col-md-4 text-center">
                <img src="images/logosmks3.jpg" alt="Lencana Sekolah SMK Seremban 3" class="img-fluid shadow rounded">
            </div>
            <div class="col-md-8">
                <p><strong>Moto:</strong> "Berilmu, Berdisiplin, Berbakti"</p>
                <ul>
                    <li><strong>Warna biru tua:</strong> Perpaduan semua warga sekolah yang terdiri daripada pelbagai kaum.</li>
                    <li><strong>Warna merah:</strong> Keberanian dan bersedia menghadapi cabaran semasa dan yang akan datang.</li>
                    <li><strong>Warna putih:</strong> Kesucian dan keikhlasan untuk meningkatkan nama baik sekolah.</li>
                    <li><strong>Empat gelung (hijau, merah, kuning, biru):</strong> Semangat untuk meningkatkan nama sekolah dalam aktiviti kokurikulum dan melambangkan empat buah rumah sukan.</li>
                    <li><strong>Anak bulan & bintang:</strong> Melambangkan raja berperlembagaan dan Islam sebagai agama rasmi negara.</li>
                    <li><strong>Buku:</strong> SMK Seremban 3 sebagai pusat ilmu.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Lagu Sekolah Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Lagu Sekolah</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <pre class="p-4 bg-light rounded shadow text-center" style="white-space: pre-wrap; font-family: 'Plus Jakarta Sans', sans-serif;">
SMK Seremban 3
Puncak ilmu abadi
Tersergam pesona
Menuju cita

SMK Seremban 3
Gedung wawasan kita
Berjanji Bersatu
Demi negara

Kami kan berusaha
Hingga berjaya
Tanpa rasa jemu
Kami berusaha

SMK Seremban 3
Puncak ilmu abadi
Tersergam pesona
Menuju cita
                </pre>
                <p class="text-center mt-2"><small>Lagu: Samsudin Ahmad | Lirik: Jamaluddin Ahmad</small></p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>