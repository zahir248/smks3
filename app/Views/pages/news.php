<?php if ($news_item) : ?>
<section class="page-section">
    <div class="container">
        <article class="content-narrow news-article-content">
            <p class="text-muted small mb-2">
                <?= date('d F Y', strtotime($news_item['published_at'])) ?>
            </p>
            <h1 class="fw-bold mb-3"><?= htmlspecialchars($news_item['title']) ?></h1>
            <?php if (!empty($news_item['pdf_file'])) : ?>
            <a href="uploads/pdf/<?= htmlspecialchars($news_item['pdf_file']) ?>" target="_blank" rel="noopener noreferrer">
                <canvas class="pdf-thumb mb-3"
                        data-pdf="uploads/pdf/<?= htmlspecialchars($news_item['pdf_file']) ?>">
                </canvas>
            </a>
            <?php endif; ?>
            <?= $news_item['content'] ?>
            <a href="news" class="btn btn-outline-primary mt-4">← Kembali ke Berita</a>
        </article>
    </div>
</section>
<?php else : ?>
<section class="page-section page-section--muted">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <p class="text-muted mb-0">Tapis mengikut tahun</p>
            <form method="GET" class="m-0">
                <select name="year" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:140px;">
                    <option value="">Semua Tahun</option>
                    <option value="2025" <?= $yearFilter === '2025' ? 'selected' : '' ?>>2025</option>
                    <option value="2026" <?= $yearFilter === '2026' ? 'selected' : '' ?>>2026</option>
                </select>
            </form>
        </div>
        <div class="news-archive-feed">
            <?php foreach ($news_page_items as $n) :
                $newsTitle = (string) ($n['title'] ?? '');
                $newsUrl = smks3_news_article_url($n);
            ?>
            <article class="news-archive-feed__post"
                     <?php if ($is_editor): ?>
                     data-edit-block="news_item"
                     data-edit-label="Sunting di halaman berita"
                     data-edit-hint="Anda akan dibuka ke halaman butiran untuk sunting berita ini."
                     data-edit-goto="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                <div class="p-3 p-md-4">
                    <p class="text-muted small mb-2">
                        <?= date('d F Y', strtotime($n['published_at'])) ?>
                    </p>
                    <h2 class="post-title h5 mb-2">
                        <a href="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($newsTitle) ?>
                        </a>
                    </h2>
                    <?php if (!empty($n['pdf_file'])) : ?>
                    <a href="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>">
                        <canvas class="pdf-thumb mb-2"
                                data-pdf="uploads/pdf/<?= htmlspecialchars($n['pdf_file']) ?>">
                        </canvas>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($n['excerpt'])) : ?>
                    <p class="text-muted mb-0"><?= htmlspecialchars($n['excerpt']) ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if ($is_editor): ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="news_add"
                    data-edit-label="Tambah berita"
                    data-edit-hint="Cipta berita baharu dengan teks, gambar atau PDF.">
                <i class="bi bi-plus-lg me-1"></i> Tambah Berita
            </button>
        </div>
        <?php endif; ?>
        <?php if ($pagination['total_pages'] > 1) : ?>
        <nav class="mt-4" aria-label="Halaman berita">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= $pagination['page'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="<?= $pagination['page'] <= 1 ? '#' : 'news?' . http_build_query(['page' => $pagination['page'] - 1, 'year' => $yearFilter]) ?>">
                        Sebelumnya
                    </a>
                </li>
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++) : ?>
                <li class="page-item <?= $i === $pagination['page'] ? 'active' : '' ?>">
                    <a class="page-link" href="news?<?= http_build_query(['page' => $i, 'year' => $yearFilter]) ?>">
                        <?= $i ?>
                    </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $pagination['page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
                    <a class="page-link"
                       href="<?= $pagination['page'] >= $pagination['total_pages'] ? '#' : 'news?' . http_build_query(['page' => $pagination['page'] + 1, 'year' => $yearFilter]) ?>">
                        Seterusnya
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
<style>
.pagination .page-item.active .page-link {
    color: #fff;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
document.querySelectorAll('.pdf-thumb').forEach(function (canvas) {
    var url = canvas.getAttribute('data-pdf');
    if (!url) return;
    pdfjsLib.getDocument(url).promise.then(function (pdf) {
        pdf.getPage(1).then(function (page) {
            var ctx = canvas.getContext('2d');
            var viewport = page.getViewport({ scale: 1 });
            var scale = canvas.parentElement.offsetWidth / viewport.width;
            var scaledViewport = page.getViewport({ scale: scale });
            canvas.width = scaledViewport.width;
            canvas.height = scaledViewport.height;
            page.render({ canvasContext: ctx, viewport: scaledViewport });
        });
    }).catch(function (err) {
        console.log('PDF load error:', url, err);
    });
});
</script>
