<?php
$page_title = 'Pengurusan Tertinggi Sekolah';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';

// Sambung database
require_once __DIR__ . '/config/database.php';
$pdo = getConnection();

// Ambil semua pengurusan ikut susunan
$stmt = $pdo->query("SELECT * FROM pengurusan ORDER BY susunan ASC");
$pengurusan = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group ikut kategori
$groups = [
    'pengetua' => [],
    'pk' => [],
    'gkmp' => [],
    'kaunselor' => []
];

foreach ($pengurusan as $p) {
    if (isset($groups[$p['kategori']])) {
        $groups[$p['kategori']][] = $p;
    }
}

// Placeholder gambar kalau tiada
$placeholderImage = '/smks3/images/placeholder.png';
?>

<section class="py-5 bg-light">
    <div class="container">

        <style>
            .pengurusan-card {
                text-align: center;
                margin-bottom: 2rem;
                cursor: pointer;
                background: #fff;
                padding: 10px;
                border-radius: 10px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.05);
                transition: transform 0.3s, box-shadow 0.3s;
                height: 300px; /* tinggi tetap supaya semua card konsisten */
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
            }
            .pengurusan-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(11,60,93,0.15);
            }
            .pengurusan-card .image-wrapper {
                width: 130px;
                height: 200px;
                overflow: hidden;
                border-radius: 50%;
                margin-bottom: 10px;
                flex-shrink: 0;
            }
            .pengurusan-card img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s;
            }
            .pengurusan-card:hover img {
                transform: scale(1.1);
            }
            .pengurusan-card h5 {
                margin: 2px 0;
                font-size: 14px;
                color: #333;
            }
            .pengurusan-card h5.jawatan {
                font-weight: 600;
                color: #0B3C5D;
            }

            /* Grid spacing */
            .pengurusan-row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                justify-content: center;
            }
            @media(max-width: 768px){
                .pengurusan-card { height: auto; width: 140px; padding: 8px; }
                .pengurusan-card .image-wrapper { width: 80px; height: 80px; }
            }
        </style>

        <?php
        $order = ['pengetua', 'pk', 'gkmp', 'kaunselor'];
        foreach ($order as $kategori):
            if (!empty($groups[$kategori])):
        ?>
                <div class="pengurusan-row mb-4">
                    <?php foreach ($groups[$kategori] as $p): ?>
                        <div class="pengurusan-card">
                            <div class="image-wrapper">
                                <img src="<?= !empty($p['gambar']) ? htmlspecialchars($p['gambar']) : $placeholderImage ?>" 
                                     alt="<?= htmlspecialchars($p['nama']) ?>">
                            </div>
                            <h5><?= htmlspecialchars($p['nama']) ?></h5>
                            <h5><?= htmlspecialchars($p['gred']) ?></h5>
                            <h5 class="jawatan"><?= htmlspecialchars($p['jawatan']) ?></h5>
                        </div>
                    <?php endforeach; ?>
                </div>
        <?php
            endif;
        endforeach;
        ?>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>