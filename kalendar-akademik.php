<?php
$page_title = 'Kalendar Akademik 2026';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
require_once __DIR__ . '/config/database.php';
function getPage($key){
    $pdo = getConnection();      // pastikan function getConnection() wujud
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE page_key=?");
    $stmt->execute([$key]);
    return $stmt->fetch();
}
$page = getPage('kalendar_akademik');
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">
        <p class="text-muted lead mb-4">
            <strong>Kumpulan B:</strong> Johor, Melaka, Negeri Sembilan, Pahang, Perak, Perlis, Pulau Pinang, Sabah, Sarawak, Selangor, Wilayah Persekutuan KL, Labuan & Putrajaya
        </p>

        <!-- dynamic content -->
        <?= $page['content']; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>