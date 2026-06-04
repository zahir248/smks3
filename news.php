<?php
$page_title = 'Berita';
require_once __DIR__ . '/includes/functions.php';

/* =========================
   1. GET INPUT (WAJIB AWAL)
========================= */
$slugParam   = isset($_GET['slug']) ? trim($_GET['slug']) : '';
$legacyId    = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$yearFilter  = isset($_GET['year']) ? $_GET['year'] : '';
$listPage    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$news_per_page = 3;
$news_item = null;

/* =========================
   2. FETCH SINGLE NEWS
========================= */
if ($slugParam !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slugParam)) {
    $news_item = smks3_fetch_news_by_slug($slugParam);

} elseif ($legacyId > 0) {
    $news_item = smks3_fetch_news_by_id($legacyId);

    if ($news_item && !empty($news_item['slug'])) {
        header("Location: news.php?slug=" . $news_item['slug'], true, 301);
        exit;
    }
}

/* =========================
   3. LIST VIEW
========================= */
$news_page_items = [];
$pagination = [
    'page' => 1,
    'per_page' => $news_per_page,
    'total' => 0,
    'total_pages' => 1
];

if (!$news_item) {

    $paginated = smks3_fetch_published_news_paginated($listPage, $news_per_page, $yearFilter);

    if ($paginated && $paginated['total'] > 0) {

        $news_page_items = $paginated['items'];
        $pagination = $paginated;

    } else {

        $news_static = [
            [
                'id' => 1,
                'title' => 'Kemasukan Pelajar Baru 2025',
                'slug' => 'ppdb-2025',
                'excerpt' => 'Pendaftaran kemasukan tahun 2025/2026 dibuka.',
                'content' => '<p>Maklumat pendaftaran pelajar baru...</p>',
                'published_at' => '2025-02-10 09:00:00'
            ],
            [
                'id' => 2,
                'title' => 'Aktiviti Latihan Industri',
                'slug' => 'pkl-2025',
                'excerpt' => 'Pelajar menjalani latihan industri.',
                'content' => '<p>Program PKL dijalankan...</p>',
                'published_at' => '2025-03-05 10:30:00'
            ],
            [
                'id' => 3,
                'title' => 'Program Kokurikulum',
                'slug' => 'kokurikulum-2025',
                'excerpt' => 'Aktiviti kokurikulum sekolah.',
                'content' => '<p>Pelbagai aktiviti dijalankan...</p>',
                'published_at' => '2025-04-18 14:00:00'
            ],
        ];

        $news_all = smks3_sort_news_by_published_desc($news_static);

        if ($yearFilter !== '') {
            $news_all = array_filter($news_all, function ($n) use ($yearFilter) {
                return substr($n['published_at'], 0, 4) == $yearFilter;
            });
        }

        $total = count($news_all);
        $totalPages = max(1, ceil($total / $news_per_page));
        $listPage = min($listPage, $totalPages);

        $offset = ($listPage - 1) * $news_per_page;
        $news_page_items = array_slice($news_all, $offset, $news_per_page);

        $pagination = [
            'page' => $listPage,
            'per_page' => $news_per_page,
            'total' => $total,
            'total_pages' => $totalPages
        ];
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<style>
    .news-archive-feed__post {
    transition: all 0.2s ease;
}

.news-archive-feed__post:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.pdf-thumb {
    width: 100%;
    max-height: 220px;
    border-radius: 10px;
    background: #f1f1f1;
}
</style>
<!-- =========================
     DETAIL PAGE
========================= -->
<?php if ($news_item) : ?>
<section class="py-5">
<div class="container">

<article class="mx-auto" style="max-width: 750px;">

    <small class="text-muted d-block mb-2">
        <?= date('d F Y', strtotime($news_item['published_at'])) ?>
    </small>

    <h1 class="fw-bold mb-3">
        <?= htmlspecialchars($news_item['title']) ?>
    </h1>

<?php if (!empty($news_item['pdf_file'])): ?>
    <a href="uploads/pdf/<?= htmlspecialchars($news_item['pdf_file']) ?>" target="_blank">
        <canvas class="pdf-thumb mb-3"
                data-pdf="uploads/pdf/<?= htmlspecialchars($news_item['pdf_file']) ?>">
        </canvas>
    </a>
<?php endif; ?>

    <div class="content">
        <?= $news_item['content'] ?>
    </div>

    <a href="news.php" class="btn btn-outline-primary mt-4">
        ← Kembali
    </a>

</article>

</div>
</section>

<!-- =========================
     LIST PAGE
========================= -->
<?php else : ?>
<section class="py-5 bg-light">
<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

    <p class="text-muted mb-0 fw-semibold">
        Informasi terbaru dari sekolah
    </p>

    <form method="GET" class="m-0">
        <select name="year"
                class="form-select form-select-sm shadow-sm"
                onchange="this.form.submit()"
                style="min-width:140px;">
            <option value="">Semua Tahun</option>
            <option value="2025" <?= $yearFilter=='2025'?'selected':'' ?>>2025</option>
            <option value="2026" <?= $yearFilter=='2026'?'selected':'' ?>>2026</option>
        </select>
    </form>

</div>

<!-- NEWS LIST -->
<div class="news-archive-feed">

<?php foreach ($news_page_items as $n): ?>
<article class="news-archive-feed__post mb-3 shadow-sm border-0 rounded-3 bg-white">
<div class="p-3 p-md-4">

    <!-- YEAR BADGE -->
    <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill">
        <?= htmlspecialchars($n['year'] ?? date('Y', strtotime($n['published_at']))) ?>
    </span>

    <!-- TITLE -->
    <h5 class="fw-bold mb-1">
    <a href="news-details.php?id=<?= (int)$n['id'] ?>" class="text-decoration-none">
        <?= htmlspecialchars($n['title']) ?>
    </a>
    </h5>

    <!-- DATE -->
    <small class="text-muted d-block mb-2">
        <?= date('d F Y', strtotime($n['published_at'])) ?>
    </small>

    <!-- PDF -->
    <?php if (!empty($n['pdf_file'])): ?>
        <a href="news-details.php?id=<?= (int)$n['id'] ?>">
            <canvas class="pdf-thumb mb-2"
                    data-pdf="uploads/pdf/<?= htmlspecialchars($n['pdf_file']) ?>">
            </canvas>
        </a>
    <?php endif; ?>

    <!-- EXCERPT -->
    <p class="text-muted mb-0">
        <?= htmlspecialchars($n['excerpt']) ?>
    </p>

</div>

</article>
<?php endforeach; ?>

</div>

<!-- =========================
     PAGINATION FIXED
========================= -->
<?php if ($pagination['total_pages'] > 1): ?>
<nav class="mt-4">
<ul class="pagination justify-content-center">

    <!-- PREVIOUS -->
    <li class="page-item <?= $pagination['page'] <= 1 ? 'disabled' : '' ?>">
        <a class="page-link"
           href="<?= $pagination['page'] <= 1 ? '#' : 'news.php?' . http_build_query(['page'=>$pagination['page']-1,'year'=>$yearFilter]) ?>">
            Sebelumnya
        </a>
    </li>

    <!-- NUMBERS -->
    <?php for ($i=1; $i <= $pagination['total_pages']; $i++): ?>
        <li class="page-item <?= $i==$pagination['page']?'active':'' ?>">
            <a class="page-link"
               href="news.php?<?= http_build_query(['page'=>$i,'year'=>$yearFilter]) ?>">
                <?= $i ?>
            </a>
        </li>
    <?php endfor; ?>

    <!-- NEXT -->
    <li class="page-item <?= $pagination['page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
        <a class="page-link"
           href="<?= $pagination['page'] >= $pagination['total_pages'] ? '#' : 'news.php?' . http_build_query(['page'=>$pagination['page']+1,'year'=>$yearFilter]) ?>">
            Seterusnya
        </a>
    </li>

</ul>
</nav>
<?php endif; ?>

</div>
</section>
<?php endif; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

<script>
document.querySelectorAll('.pdf-thumb').forEach(canvas => {

    const url = canvas.getAttribute('data-pdf');

    if (!url) return;

    pdfjsLib.getDocument(url).promise.then(pdf => {

        pdf.getPage(1).then(page => {

            const ctx = canvas.getContext('2d');

            const viewport = page.getViewport({ scale: 1 });

            const scale = canvas.parentElement.offsetWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale });

            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;

            page.render({
                canvasContext: ctx,
                viewport: scaledViewport
            });

        });

    }).catch(err => {
        console.log("PDF load error:", url, err);
    });

});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>