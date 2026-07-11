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
$newsContentPlain = trim(html_entity_decode(strip_tags($newsContentRaw), ENT_QUOTES, 'UTF-8'));
$newsYear = (string) ($news_item['year'] ?? date('Y', strtotime((string) ($news_item['published_at'] ?? 'now'))));
$newsImage = trim((string) ($news_item['image'] ?? ''));
$imageSrc = '';
if ($newsImage !== '') {
    $imageSrc = str_starts_with($newsImage, 'uploads/') || str_starts_with($newsImage, 'images/')
        ? $newsImage
        : 'uploads/' . ltrim($newsImage, '/');
}
$pdfPath = $pdfPath ?? null;
?>
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
         data-content="<?= htmlspecialchars($newsContentPlain, ENT_QUOTES, 'UTF-8') ?>"
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

    <?php if ($imageSrc !== '' && is_file(BASE_PATH . '/' . $imageSrc)): ?>
        <div class="mb-4 text-center">
            <img src="<?= htmlspecialchars($imageSrc, ENT_QUOTES, 'UTF-8') ?>"
                 alt="<?= htmlspecialchars($newsTitle, ENT_QUOTES, 'UTF-8') ?>"
                 class="img-fluid rounded shadow-sm"
                 style="max-height: 420px; object-fit: contain;">
        </div>
    <?php endif; ?>

    <?php if ($newsContentRaw !== ''): ?>
        <div class="mb-4" data-bind="news_content">
            <?= $newsContentRaw ?>
        </div>
    <?php elseif ($is_editor): ?>
        <p class="text-muted mb-4">Tiada kandungan teks. Klik untuk sunting.</p>
    <?php endif; ?>

    <?php if ($pdfPath): ?>
        <?php
        smks3_pdf_viewer((string) $pdfPath, [
            'id' => 'news-pdf-' . (int) ($news_item['id'] ?? 0),
            'label' => 'Buka PDF',
            'btn_class' => 'btn btn-outline-primary btn-sm',
            'fit' => 'width',
        ]);
        ?>
    <?php elseif ($is_editor): ?>
        <p class="text-muted small mb-0">Tiada PDF dilampirkan. Muat naik melalui panel sunting.</p>
    <?php endif; ?>

    <a href="news" class="btn btn-outline-primary mt-4">
        ← Kembali ke Berita
    </a>

</article>

</div>
</section>
