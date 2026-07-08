<?php
$page_title = 'Profil Sekolah';
$page_lead = 'Maklumat am dan ringkas tentang sekolah kami.';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/config/database.php';

$pdo = getConnection();

$stmt = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1");
$school = $stmt->fetch() ?? [];

$profil_data = [
    ['title' => 'Nama Pengetua', 'value' => $school['nama_pengetua'] ?? '', 'icon' => 'bi-person-badge'],
    ['title' => 'Bilangan Guru', 'value' => ($school['bilangan_guru'] ?? '') . ' orang', 'icon' => 'bi-people'],
    ['title' => 'Bilangan Murid', 'value' => ($school['bilangan_murid'] ?? '') . ' orang', 'icon' => 'bi-people-fill'],
    ['title' => 'Keluasan Sekolah', 'value' => $school['keluasan_sekolah'] ?? '', 'icon' => 'bi-arrows-fullscreen'],
    ['title' => 'Sesi Persekolahan', 'value' => $school['sesi_persekolahan'] ?? '', 'icon' => 'bi-clock'],
    ['title' => 'Tingkatan Tertinggi', 'value' => $school['tingkatan_tertinggi'] ?? '', 'icon' => 'bi-mortarboard'],
    ['title' => 'Alamat Sekolah', 'value' => $school['alamat_sekolah'] ?? '', 'icon' => 'bi-geo-alt'],
    ['title' => 'Kod Sekolah', 'value' => $school['kod_sekolah'] ?? '', 'icon' => 'bi-hash'],
    ['title' => 'Lokasi', 'value' => $school['lokasi'] ?? '', 'icon' => 'bi-map'],
    ['title' => 'Daerah Pentadbiran', 'value' => $school['daerah_pentadbiran'] ?? '', 'icon' => 'bi-building'],
    ['title' => 'Gred Sekolah', 'value' => $school['gred_sekolah'] ?? '', 'icon' => 'bi-award'],
    ['title' => 'Pejabat Pendidikan Daerah', 'value' => $school['pejabat_pendidikan_daerah'] ?? '', 'icon' => 'bi-building'],
    ['title' => 'Jenis Bantuan', 'value' => $school['jenis_bantuan'] ?? '', 'icon' => 'bi-bank2'],
];

require_once __DIR__ . '/includes/header.php';
?>
<section class="page-section" id="maklumat-sekolah">
    <div class="container">
        <div class="row g-3 g-md-4">
            <?php
            $profil_count = count($profil_data);
            foreach ($profil_data as $index => $item) :
                $is_last_card = ($index === $profil_count - 1);
            ?>
            <div class="col-md-6 col-lg-4<?= $is_last_card ? ' offset-md-3 offset-lg-4' : '' ?>">
                <div class="info-card">
                    <div class="info-card__body">
                        <span class="icon-box" aria-hidden="true">
                            <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i>
                        </span>
                        <div>
                            <div class="info-card__label"><?= htmlspecialchars($item['title']) ?></div>
                            <p class="info-card__value"><?= htmlspecialchars($item['value']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
