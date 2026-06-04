<?php
$page_title = 'Profil Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$pdo = getConnection();

$stmt = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1");
$school = $stmt->fetch() ?? [];

// Mapping data untuk cards, kekalkan warna & icon sama
$profil_data = [
    ['title'=>'Nama Pengetua','value'=>$school['nama_pengetua'],'icon'=>'bi-person-badge','color'=>'#1e3a8a'],
    ['title'=>'Bilangan Guru','value'=>$school['bilangan_guru'].' orang','icon'=>'bi-people','color'=>'#0d9488'],
    ['title'=>'Bilangan Murid','value'=>$school['bilangan_murid'].' orang','icon'=>'bi-people-fill','color'=>'#f59e0b'],
    ['title'=>'Keluasan Sekolah','value'=>$school['keluasan_sekolah'],'icon'=>'bi-arrows-fullscreen','color'=>'#9333ea'],
    ['title'=>'Sesi Persekolahan','value'=>$school['sesi_persekolahan'],'icon'=>'bi-clock','color'=>'#ef4444'],
    ['title'=>'Tingkatan Tertinggi','value'=>$school['tingkatan_tertinggi'],'icon'=>'bi-mortarboard','color'=>'#2563eb'],
    ['title'=>'Alamat Sekolah','value'=>$school['alamat_sekolah'],'icon'=>'bi-geo-alt','color'=>'#f97316'],
    ['title'=>'Kod Sekolah','value'=>$school['kod_sekolah'],'icon'=>'bi-hash','color'=>'#10b981'],
    ['title'=>'Lokasi','value'=>$school['lokasi'],'icon'=>'bi-map','color'=>'#3b82f6'],
    ['title'=>'Daerah Pentadbiran','value'=>$school['daerah_pentadbiran'],'icon'=>'bi-building','color'=>'#8b5cf6'],
    ['title'=>'Gred Sekolah','value'=>$school['gred_sekolah'],'icon'=>'bi-award','color'=>'#fbbf24'],
    ['title'=>'Pejabat Pendidikan Daerah','value'=>$school['pejabat_pendidikan_daerah'],'icon'=>'bi-building','color'=>'#ef4444'],
    ['title'=>'Jenis Bantuan','value'=>$school['jenis_bantuan'],'icon'=>'bi-bank2','color'=>'#22c55e'],
];
?>
<style>
.card-hover{
    transition: all 0.3s ease;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    background: #fff;
}

.card-hover:hover{
    transform: translateY(-5px);
    box-shadow: 0 12px 35px rgba(0,0,0,0.15);
}

.row.g-4{
    overflow: visible;
}

.card-text{
    color: #4b5563;
}
</style>
<!-- Profil Sekolah Creative Cards -->
<section class="py-5" id="maklumat-sekolah" style="background:#d8f9ff;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="text-center fw-bold mb-3">Mengenai SMK Seremban 3</h2>
            <p class="text-muted">Maklumat Am dan ringkas tentang sekolah kami.</p>
        </div>

        <div class="row g-4">
            <?php 
            $profil_count = count($profil_data);
            foreach ($profil_data as $index => $item) :
                $is_last_card = ($index === $profil_count - 1);
            ?>
            <div class="col-md-6 col-lg-4<?= $is_last_card ? ' offset-md-3 offset-lg-4' : '' ?>">
                <div class="card card-hover h-100 border-0">
                    <div class="card-body d-flex align-items-start">
                        <div class="me-3">
                            <i class="bi <?= $item['icon'] ?> fs-1" style="color: <?= $item['color'] ?>;"></i>
                        </div>
                        <div>
                            <h5 class="card-title fw-bold mb-1"><?= $item['title'] ?></h5>
                            <p class="card-text mb-0"><?= $item['value'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>