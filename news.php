<?php
$page_title = 'Berita';
require_once __DIR__ . '/includes/functions.php';
$news_static = [
    ['id' => 1, 'title' => 'Kemasukan Pelajar Baru 2025', 'slug' => 'ppdb-2025', 'excerpt' => 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', 'content' => '<p>Pendaftaran kemasukan pelajar baharu bagi sesi persekolahan 2025/2026 akan dibuka secara rasmi bermula 1 April 2025. Ibu bapa dan penjaga digalakkan membuat semakan dokumen asas awal supaya proses permohonan berjalan lancar.</p><p>Maklumat lengkap mengenai syarat kelayakan, borang, dan tarikh penting akan diumumkan melalui laman web sekolah, papan notis, serta media sosial rasmi. Sebarang pertanyaan boleh dibuat melalui pejabat sekolah pada waktu pejabat.</p>', 'published_at' => '2025-02-10 09:00:00'],
    ['id' => 2, 'title' => 'Aktiviti Latihan Industri', 'slug' => 'pkl-2025', 'excerpt' => 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', 'content' => '<p>Program latihan industri (PKL) dijalankan bagi tempoh tiga bulan untuk melengkapkan komponen vokasional pelajar tingkatan lima. Pelajar ditempatkan di syarikat rakan industri yang telah dipersetujui bersama pihak sekolah.</p><p>Semasa PKL, pelajar mempraktikkan kemahiran teknikal dan kerja berpasukan di persekitaran sebenar. Penilaian dan lawatan penyeliaan akan dijalankan bagi memastikan objektif pembelajaran tercapai.</p>', 'published_at' => '2025-03-05 10:30:00'],
    ['id' => 3, 'title' => 'Program Kokurikulum 2025', 'slug' => 'kokurikulum-2025', 'excerpt' => 'Pelbagai aktiviti kokurikulum dijadualkan untuk pembangunan holistik pelajar sepanjang tahun.', 'content' => '<p>Sekolah merancang pelbagai aktiviti kokurikulum sepanjang tahun 2025 bagi memupuk kepimpinan, kerjasama, dan kesihatan mental serta fizikal pelajar. Kelab dan unit beruniform akan meneruskan sesi latihan dan pertandingan mengikut takwim.</p><p>Senarai aktiviti, tarikh, dan sebarang perubahan akan dikemas kini dari semasa ke semasa melalui papan notis sekolah dan saluran rasmi. Pelajar dinasihatkan merujuk guru penyelaras masing-masing untuk maklumat terperinci.</p>', 'published_at' => '2025-04-18 14:00:00'],
];
$slugParam = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
$legacyId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int) $_GET['id'] : 0;
$news_item = null;

if ($slugParam !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slugParam)) {
    $news_item = smks3_fetch_news_by_slug($slugParam);
    if ($news_item === null) {
        foreach ($news_static as $n) {
            if (($n['slug'] ?? '') === $slugParam) {
                $news_item = $n;
                break;
            }
        }
    }
} elseif ($legacyId > 0) {
    $row = smks3_fetch_news_by_id($legacyId);
    if ($row === null) {
        foreach ($news_static as $n) {
            if ((int) $n['id'] === $legacyId) {
                $row = $n;
                break;
            }
        }
    }
    if ($row !== null) {
        $slugOut = isset($row['slug']) ? trim((string) $row['slug']) : '';
        if ($slugOut !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slugOut)) {
            header('Location: news.php?' . http_build_query(['slug' => $slugOut]), true, 301);
            exit;
        }
        $news_item = $row;
    }
}

$news_per_page = 3;
$news_page_items = [];
$pagination = ['page' => 1, 'per_page' => $news_per_page, 'total' => 0, 'total_pages' => 1];

if (!$news_item) {
    $listPage = isset($_GET['page']) && ctype_digit((string) $_GET['page']) ? max(1, (int) $_GET['page']) : 1;
    $paginated = smks3_fetch_published_news_paginated($listPage, $news_per_page);
    if ($paginated !== null && $paginated['total'] > 0) {
        $news_page_items = $paginated['items'];
        $pagination = $paginated;
    } else {
        $news_all = smks3_sort_news_by_published_desc($news_static);
        $total = count($news_all);
        $totalPages = max(1, (int) ceil($total / $news_per_page));
        $listPage = min($listPage, $totalPages);
        $offset = ($listPage - 1) * $news_per_page;
        $news_page_items = array_slice($news_all, $offset, $news_per_page);
        $pagination = [
            'page' => $listPage,
            'per_page' => $news_per_page,
            'total' => $total,
            'total_pages' => $totalPages,
        ];
    }
}

if ($news_item) {
    $page_title = $news_item['title'];
    $custom_breadcrumbs = [
        ['label' => 'Laman Utama', 'href' => 'index.php'],
        ['label' => 'Berita', 'href' => 'news.php'],
        ['label' => $news_item['title'], 'current' => true],
    ];
}
require_once __DIR__ . '/includes/header.php';
?>

<?php if ($news_item) : ?>
<section class="py-5">
    <div class="container">
        <article class="mx-auto" style="max-width: 720px;">
            <small class="text-muted"><?= date('d F Y', strtotime($news_item['published_at'])) ?></small>
            <h1 class="fw-bold mt-2 mb-4"><?= htmlspecialchars($news_item['title']) ?></h1>
            <div class="content news-article-content">
                <?= smks3_news_body_html($news_item['content'] ?? '', $news_item['excerpt'] ?? '') ?>
            </div>
            <a href="news.php" class="btn btn-outline-primary mt-4">← Kembali ke Berita</a>
        </article>
    </div>
</section>
<?php else : ?>
<style>
    .news-archive-feed { max-width: 720px; margin: 0 auto; }
    .news-archive-feed__post {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(11, 60, 93, 0.08);
        border: 1px solid var(--school-border, #e2e8f0);
        margin-bottom: 1.25rem;
        transition: box-shadow 0.2s ease;
    }
    .news-archive-feed__post:last-child { margin-bottom: 0; }
    .news-archive-feed__post:hover { box-shadow: 0 8px 24px rgba(11, 60, 93, 0.1); }
    .news-archive-feed__title {
        color: var(--school-primary-dark, #082a42);
        font-weight: 700;
        font-size: 1.15rem;
        line-height: 1.35;
    }
    .news-archive-feed__title:hover { color: var(--school-primary, #0B3C5D); }
    .news-archive-feed__body {
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.65;
    }
    .news-archive-pagination .page-item.active .page-link {
        color: #fff !important;
        background-color: var(--school-primary, #0B3C5D);
        border-color: var(--school-primary, #0B3C5D);
    }
    .news-archive-pagination .page-item.active .page-link:hover {
        color: #fff !important;
        background-color: var(--school-primary-dark, #082a42);
        border-color: var(--school-primary-dark, #082a42);
    }
</style>
<section class="py-5 bg-light">
    <div class="container">
        <p class="text-muted lead mb-4 text-center">Informasi terbaru dari sekolah.</p>
        <?php $p = $pagination; ?>
        <div class="news-archive-feed">
            <?php foreach ($news_page_items as $n) : ?>
            <article class="news-archive-feed__post">
                <div class="card-body p-4">
                    <time class="text-muted small d-block mb-2" datetime="<?= date('Y-m-d', strtotime($n['published_at'])) ?>"><?= date('d F Y', strtotime($n['published_at'])) ?></time>
                    <h2 class="news-archive-feed__title mb-2 h5">
                        <a href="<?= htmlspecialchars(smks3_news_article_url($n), ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none"><?= htmlspecialchars($n['title']) ?></a>
                    </h2>
                    <div class="news-archive-feed__body news-article-content mb-0">
                        <?= smks3_news_body_html($n['content'] ?? '', $n['excerpt'] ?? '') ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if ($p['total_pages'] > 1) : ?>
        <nav class="mt-4 news-archive-pagination" aria-label="Penomboran halaman berita">
            <ul class="pagination justify-content-center flex-wrap mb-0">
                <li class="page-item<?= $p['page'] <= 1 ? ' disabled' : '' ?>">
                    <a class="page-link" href="<?= $p['page'] <= 1 ? '#' : 'news.php?' . http_build_query(['page' => $p['page'] - 1]) ?>"<?= $p['page'] <= 1 ? ' tabindex="-1" aria-disabled="true"' : '' ?>>Sebelum</a>
                </li>
                <?php for ($i = 1; $i <= $p['total_pages']; $i++) : ?>
                <li class="page-item<?= $i === $p['page'] ? ' active' : '' ?>">
                    <a class="page-link" href="news.php?<?= http_build_query(['page' => $i]) ?>"<?= $i === $p['page'] ? ' aria-current="page"' : '' ?>><?= $i ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item<?= $p['page'] >= $p['total_pages'] ? ' disabled' : '' ?>">
                    <a class="page-link" href="<?= $p['page'] >= $p['total_pages'] ? '#' : 'news.php?' . http_build_query(['page' => $p['page'] + 1]) ?>"<?= $p['page'] >= $p['total_pages'] ? ' tabindex="-1" aria-disabled="true"' : '' ?>>Seterusnya</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
