<?php
$page_title = 'Butiran Buletin';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$pdo = getConnection();

/* =========================
   GET ID
========================= */
$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("ID tidak sah");
}

/* =========================
   GET NEWS
========================= */
$stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
$stmt->execute([$id]);
$news_item = $stmt->fetch();

if (!$news_item) {
    die("Buletin tidak dijumpai");
}

/* =========================
   PDF PATH
========================= */
$pdfPath = null;

if (!empty($news_item['pdf_file'])) {
    $pdfPath = "uploads/pdf/" . basename($news_item['pdf_file']);

    if (!file_exists($pdfPath)) {
        $pdfPath = null;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5">
<div class="container">

<article class="mx-auto" style="max-width: 1000px;">

    <!-- TITLE -->
    <h1 class="fw-bold mb-2">
        <?= htmlspecialchars($news_item['title']) ?>
    </h1>

    <!-- DATE -->
    <?php if (!empty($news_item['published_at'])): ?>
        <small class="text-muted d-block mb-3">
            <?= date('d F Y', strtotime($news_item['published_at'])) ?>
        </small>
    <?php endif; ?>

    <hr>

    <!-- PDF VIEW -->
    <?php if ($pdfPath): ?>

        <div id="pdf-container" class="pdf-grid mt-4"></div>

    <?php else: ?>
        <p class="text-muted">Tiada PDF untuk buletin ini.</p>
    <?php endif; ?>

    <!-- BACK BUTTON -->
    <a href="news.php" class="btn btn-outline-primary mt-4">
        ← Kembali ke Buletin
    </a>

</article>

</div>
</section>

<style>
.pdf-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.pdf-grid canvas {
    width: 100%;
    border-radius: 10px;
    border: 1px solid #ddd;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    background: white;
}

@media (max-width: 768px) {
    .pdf-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php if ($pdfPath): ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

<script>
const url = "<?= $pdfPath ?>";

pdfjsLib.getDocument(url).promise.then(async function(pdf) {

    const container = document.getElementById('pdf-container');

    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {

        const page = await pdf.getPage(pageNum);

        const scale = 1.2;
        const viewport = page.getViewport({ scale: scale });

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        canvas.height = viewport.height;
        canvas.width = viewport.width;

        container.appendChild(canvas);

        await page.render({
            canvasContext: context,
            viewport: viewport
        }).promise;
    }
});
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>