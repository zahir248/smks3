<?php
$is_editor = !empty($is_editor);
$db_files = is_array($db_files ?? null) ? $db_files : [];
$publicDir = 'uploads/pbd_panduan';

$images = [];
$pdfs = [];
foreach ($db_files as $row) {
    $name = basename(str_replace('\\', '/', (string) ($row['file'] ?? '')));
    if ($name === '') {
        continue;
    }
    $src = $publicDir . '/' . $name;
    if (!is_file(BASE_PATH . '/' . $src)) {
        continue;
    }
    $ext = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
    $item = [
        'id' => (int) ($row['id'] ?? 0),
        'src' => $src,
        'key' => $name,
        'name' => $name,
        'is_pdf' => $ext === 'pdf',
    ];
    if ($item['is_pdf']) {
        $pdfs[] = $item;
    } else {
        $images[] = $item;
    }
}

$allItems = array_merge($images, $pdfs);
$galleryJson = htmlspecialchars(
    json_encode(array_map(static fn (array $item): array => [
        'src' => $item['src'],
        'key' => $item['key'],
    ], $allItems), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    ENT_QUOTES,
    'UTF-8'
);
?>

<style>
.pbd-panduan-img {
    width: 100%;
    max-width: 520px;
    height: auto;
    cursor: pointer;
}
.pbd-panduan-images {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}
.pbd-panduan-images__item {
    width: 100%;
    max-width: 520px;
    text-align: center;
    margin: 0;
}
.pbd-panduan-pdf {
    max-width: 620px;
    margin-left: auto;
    margin-right: auto;
}
.pbd-panduan-pdf .smks3-pdf-block--grid .smks3-pdf-pages canvas,
.pbd-panduan-pdf .smks3-pdf-block--grid .smks3-pdf-pages:has(> canvas:only-child) canvas {
    width: min(100%, 580px);
}
@media (max-width: 576px) {
    .pbd-panduan-img,
    .pbd-panduan-images__item,
    .pbd-panduan-pdf {
        max-width: 100%;
    }
}
</style>

<section class="page-section">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Maklumat PBD Dan Panduan</h2>
            <p class="text-muted">Paparan imej dan fail PDF maklumat PBD serta panduan pelaksanaan.</p>
        </div>

        <?php if ($allItems === []): ?>
            <div class="alert alert-light border text-center" role="alert">
                Tiada fail lagi.
                <?php if ($is_editor): ?>
                    Klik <strong>Urus Fail</strong> untuk muat naik imej atau PDF.
                <?php else: ?>
                    Sila cuba lagi kemudian.
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php if ($images !== []): ?>
                <div class="pbd-panduan-images mb-5"
                     <?php if ($is_editor): ?>
                     data-edit-block="pbd_panduan_gallery"
                     data-edit-label="Urus fail Maklumat PBD Dan Panduan"
                     data-edit-hint="Tambah, buang, atau susun semula imej dan PDF."
                     data-images-json="<?= $galleryJson ?>"
                     <?php endif; ?>>
                    <?php foreach ($images as $item): ?>
                        <figure class="pbd-panduan-images__item">
                            <img
                                src="<?= htmlspecialchars($item['src'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?>"
                                class="img-fluid rounded shadow pbd-panduan-img"
                                <?php if (!$is_editor): ?>
                                style="cursor:pointer;"
                                onclick="smks3OpenMediaOverlay(this.src)"
                                <?php endif; ?>>
                        </figure>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($pdfs !== []): ?>
                <?php foreach ($pdfs as $idx => $item): ?>
                    <div class="card mb-4 shadow-sm border-0 pbd-panduan-pdf">
                        <div class="card-body">
                            <?php
                            smks3_pdf_viewer($item['src'], [
                                'id' => 'pbd-panduan-pdf-' . ($item['id'] > 0 ? $item['id'] : ($idx + 1)),
                                'label' => 'Buka / Muat Turun PDF',
                                'btn_class' => 'btn btn-outline-primary btn-sm',
                                'fit' => 'grid',
                            ]);
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($is_editor): ?>
            <div class="text-center mt-4">
                <button type="button"
                        class="btn btn-outline-primary"
                        data-edit-block="pbd_panduan_gallery"
                        data-edit-label="Urus fail Maklumat PBD Dan Panduan"
                        data-edit-hint="Tambah, buang, atau susun semula imej dan PDF."
                        data-images-json="<?= $galleryJson ?>">
                    <i class="bi bi-cloud-upload me-1"></i> Urus Fail
                </button>
                <p class="small text-muted mt-2 mb-0">
                    Muat naik imej (JPG/PNG/WEBP/GIF) atau PDF terus dari halaman ini. PDF dipaparkan di bawah imej.
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>
