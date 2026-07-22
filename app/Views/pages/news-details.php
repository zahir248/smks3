<?php
$is_editor = !empty($is_editor);
$news_item = is_array($news_item ?? null) ? $news_item : null;
if (!$news_item) {
    ?>
<section class="page-section">
    <div class="container text-center">
        <p class="text-muted mb-3">Berita tidak dijumpai.</p>
        <a href="news" class="btn btn-outline-primary">← Kembali ke Berita</a>
    </div>
</section>
    <?php
    return;
}

$newsTitle = (string) ($news_item['title'] ?? '');
$newsContentRaw = (string) ($news_item['content'] ?? '');
$newsYear = (string) ($news_item['year'] ?? date('Y', strtotime((string) ($news_item['published_at'] ?? 'now'))));
$newsImageSrcs = smks3_news_image_srcs($news_item['image'] ?? null);
$pdfPaths = smks3_news_pdf_srcs($news_item['pdf_file'] ?? null);
// Backward compat if controller still passes a single path.
if ($pdfPaths === [] && !empty($pdfPath)) {
    $pdfPaths = [(string) $pdfPath];
}
?>
<style>
.news-gallery {
    display: grid;
    gap: 0.85rem;
    margin-bottom: 1.25rem;
}
@media (min-width: 768px) {
    .news-gallery {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    .news-gallery--single {
        grid-template-columns: 1fr;
    }
}
.news-gallery__item {
    margin: 0;
    text-align: center;
}
.news-gallery__item img {
    width: 100%;
    max-height: 420px;
    object-fit: contain;
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.5rem rgba(15, 23, 42, 0.08);
    background: #f8fafc;
}
.news-pdf-list {
    display: grid;
    gap: 1.25rem;
}
</style>
<section class="page-section">
<div class="container">

<article class="mx-auto news-article-content"
         style="max-width: 1000px;"
         <?php if ($is_editor): ?>
         data-edit-block="news_item"
         data-edit-label="Sunting berita"
         data-edit-hint="Kemaskini tajuk, kandungan, gambar atau PDF berita ini."
         data-news-id="<?= (int) $news_item['id'] ?>"
         data-id="<?= (int) $news_item['id'] ?>"
         data-title="<?= htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8') ?>"
         data-year="<?= htmlspecialchars($newsYear, ENT_QUOTES, 'UTF-8') ?>"
         data-content="<?= htmlspecialchars($newsContentRaw, ENT_QUOTES, 'UTF-8') ?>"
         data-images-json="<?= htmlspecialchars(json_encode($newsImageSrcs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
         data-pdfs-json="<?= htmlspecialchars(json_encode($pdfPaths, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>

    <h1 class="fw-bold mb-2" data-bind="news_title">
        <?= htmlspecialchars($newsTitle) ?>
    </h1>

    <?php if (!empty($news_item['published_at'])): ?>
        <small class="text-muted d-block mb-3">
            <?= date('d F Y', strtotime((string) $news_item['published_at'])) ?>
        </small>
    <?php endif; ?>

    <hr>

    <?php if ($newsImageSrcs !== []): ?>
        <div class="news-gallery<?= count($newsImageSrcs) === 1 ? ' news-gallery--single' : '' ?>" data-bind="news_images">
            <?php foreach ($newsImageSrcs as $imgSrc): ?>
            <figure class="news-gallery__item">
                <img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8') ?>"
                     loading="lazy"
                     decoding="async">
            </figure>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    $newsBodyHtml = smks3_news_body_html($newsContentRaw, (string) ($news_item['excerpt'] ?? ''));
    if ($newsBodyHtml !== ''):
    ?>
        <div class="mb-4" data-bind="news_content">
            <?= $newsBodyHtml ?>
        </div>
    <?php elseif ($is_editor): ?>
        <p class="text-muted mb-4">Tiada kandungan teks. Klik untuk sunting.</p>
    <?php endif; ?>

    <?php if ($pdfPaths !== []): ?>
        <div class="news-pdf-list">
            <?php foreach ($pdfPaths as $idx => $onePdf): ?>
                <?php
                smks3_pdf_viewer((string) $onePdf, [
                    'id' => 'news-pdf-' . (int) ($news_item['id'] ?? 0) . '-' . (int) $idx,
                    'label' => count($pdfPaths) > 1 ? ('Buka PDF ' . ((int) $idx + 1)) : 'Buka PDF',
                    'btn_class' => 'btn btn-outline-primary btn-sm',
                    'fit' => 'width',
                ]);
                ?>
            <?php endforeach; ?>
        </div>
    <?php elseif ($is_editor): ?>
        <p class="text-muted small mb-0">Tiada PDF dilampirkan. Muat naik melalui panel sunting.</p>
    <?php endif; ?>

    <a href="news" class="btn btn-outline-primary mt-4">
        ← Kembali ke Berita
    </a>

</article>

</div>
</section>
