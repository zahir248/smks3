<?php
$page_title = 'Berita';
require_once __DIR__ . '/includes/functions.php';
$news_static = [
    ['id' => 1, 'title' => 'Kemasukan Pelajar Baru 2025', 'slug' => 'ppdb-2025', 'excerpt' => 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', 'content' => '<p>Maklumat lengkap pendaftaran akan diumumkan melalui laman web dan media sosial sekolah.</p>', 'published_at' => date('Y-m-d H:i:s')],
    ['id' => 2, 'title' => 'Aktiviti Latihan Industri', 'slug' => 'pkl-2025', 'excerpt' => 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', 'content' => '<p>Program latihan industri dijalankan selama 3 bulan untuk mempersiapkan pelajar memasuki dunia pekerjaan.</p>', 'published_at' => date('Y-m-d H:i:s')],
];
$single = isset($_GET['id']) && is_numeric($_GET['id']);
$news_item = null;
$news_list = $news_static;
if ($single) {
    $id = (int) $_GET['id'];
    foreach ($news_static as $n) {
        if ((int) $n['id'] === $id) {
            $news_item = $n;
            $page_title = $n['title'];
            break;
        }
    }
}
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($single && $news_item) : ?>
<section class="py-5">
    <div class="container">
        <article class="mx-auto" style="max-width: 720px;">
            <small class="text-muted"><?= date('d F Y', strtotime($news_item['published_at'])) ?></small>
            <h1 class="fw-bold mt-2 mb-4"><?= htmlspecialchars($news_item['title']) ?></h1>
            <div class="content">
                <?= $news_item['content'] ?: '<p>' . nl2br(htmlspecialchars($news_item['excerpt'])) . '</p>' ?>
            </div>
            <a href="news.php" class="btn btn-outline-primary mt-4">← Kembali ke Berita</a>
        </article>
    </div>
</section>
<?php else : ?>
<section class="py-5 bg-light">
    <div class="container">
        <h1 class="fw-bold mb-2">Berita & Pengumuman</h1>
        <p class="text-muted lead">Informasi terbaru dari sekolah.</p>
        <div class="row g-4 mt-4">
            <?php foreach ($news_list as $n) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted"><?= date('d M Y', strtotime($n['published_at'])) ?></small>
                        <h6 class="card-title mt-1"><?= htmlspecialchars($n['title']) ?></h6>
                        <p class="card-text small text-muted"><?= htmlspecialchars($n['excerpt'] ?? '') ?></p>
                        <a href="news.php?id=<?= (int)$n['id'] ?>" class="btn btn-link p-0">Baca selanjutnya</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
