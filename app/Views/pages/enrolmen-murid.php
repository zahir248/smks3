<?php
$is_editor = !empty($is_editor);
$page_meta = is_array($page_meta ?? null) ? $page_meta : smks3_get_page_meta('enrolmen-murid');
$enrolmen = is_array($enrolmen ?? null) ? $enrolmen : smks3_get_enrolmen_content();
$blokA = is_array($enrolmen['blok_a'] ?? null) ? $enrolmen['blok_a'] : [];
$blokB = is_array($enrolmen['blok_b'] ?? null) ? $enrolmen['blok_b'] : [];
?>
<style>
.enrolmen-hero {
    margin-top: 0.5rem;
    margin-bottom: 2.5rem;
}
.enrolmen-slide {
    padding: 0.5rem 0 1.25rem;
}
.enrolmen-slide__title {
    margin: 0 0 1.25rem;
    letter-spacing: 2px;
}
.img-enrolment {
    width: 100%;
    height: auto;
    max-height: 560px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
    border: 0 !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    outline: none;
    background: transparent;
}
#enrolmentCarousel .carousel-inner,
#enrolmentCarousel .carousel-item {
    border: 0;
    background: transparent;
}
.floor-plan {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.room {
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    border: 1px solid #e5e7eb;
}

.grid-7 { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; }
.grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
.grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.grid-1 { display: grid; grid-template-columns: 1fr; gap: 10px; }

/* Color by Tingkatan */
.t1 { background:#dbeafe; }
.t2 { background:#fde68a; }
.t3 { background:#bbf7d0; }
.t4 { background:#fecaca; }
.t5 { background:#c7d2fe; }

.special { background:#e5e7eb; }
.library { background:#374151; color:white; }

.room:hover {
    transform: translateY(-3px);
    transition: 0.2s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.surau {
    grid-column: 1 / 6;  /* dari column 1 sampai sebelum 6 (1-5) */
}
.letter-spacing {
    letter-spacing: 2px;
}
.enrolmen-carousel-wrap {
    position: relative;
}
.enrolmen-carousel-wrap .carousel-control-prev,
.enrolmen-carousel-wrap .carousel-control-next {
    width: 2.75rem;
    height: 2.75rem;
    top: 50%;
    transform: translateY(-50%);
    bottom: auto;
    opacity: 0;
    visibility: hidden;
    border: 0;
    border-radius: 999px;
    background: #0b2a4a;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(11, 42, 74, 0.28);
    transition: background-color 0.2s ease, opacity 0.2s ease, visibility 0.2s ease;
}
.enrolmen-carousel-wrap:hover .carousel-control-prev,
.enrolmen-carousel-wrap:hover .carousel-control-next,
.enrolmen-carousel-wrap:has(:focus-visible) .carousel-control-prev,
.enrolmen-carousel-wrap:has(:focus-visible) .carousel-control-next {
    opacity: 1;
    visibility: visible;
}
.enrolmen-carousel-wrap .carousel-control-prev { left: 0.5rem; }
.enrolmen-carousel-wrap .carousel-control-next { right: 0.5rem; }
.enrolmen-carousel-wrap .carousel-control-prev:hover,
.enrolmen-carousel-wrap .carousel-control-next:hover,
.enrolmen-carousel-wrap .carousel-control-prev:focus-visible,
.enrolmen-carousel-wrap .carousel-control-next:focus-visible {
    background: #143a63;
    color: #fff;
    opacity: 1;
    visibility: visible;
}
.enrolmen-carousel-wrap .carousel-control-prev:focus,
.enrolmen-carousel-wrap .carousel-control-next:focus {
    outline: none;
    box-shadow: none;
}
.enrolmen-carousel-wrap .carousel-control-prev i,
.enrolmen-carousel-wrap .carousel-control-next i {
    font-size: 1.35rem;
    line-height: 1;
}
@media (hover: none) {
    .enrolmen-carousel-wrap .carousel-control-prev,
    .enrolmen-carousel-wrap .carousel-control-next {
        opacity: 0.9;
        visibility: visible;
    }
}
@media (max-width: 768px) {
    .img-enrolment {
        max-height: none;
        object-fit: contain;
    }

    .enrolmen-slide__title {
        margin-bottom: 1rem;
        font-size: 1.15rem;
    }

    .grid-7 {
        grid-template-columns: repeat(2, 1fr);
    }

    .room {
        font-size: 11px;
        padding: 10px;
    }
}
@media (max-width: 768px) {
    .bilangan-kelas-title {
        font-size: 1.2rem;
        line-height: 1.4;
    }
}
</style>
<!-- ENROLMENT SECTION -->
<section class="page-section">
    <div class="container text-center">
        <div class="mb-3"
             <?php if ($is_editor): ?>
             data-edit-block="kurikulum_meta"
             data-edit-label="Sunting pengenalan enrolmen"
             data-edit-hint="Teks pengenalan di bahagian atas sahaja."
             data-page-key="enrolmen-murid"
             data-intro="<?= htmlspecialchars((string) ($page_meta['intro'] ?? 'Susun atur kelas mengikut blok dan aras di SMK Seremban 3.'), ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <p class="text-muted lead mb-0" data-bind="kurikulum_intro"><?= htmlspecialchars((string) ($page_meta['intro'] ?? 'Susun atur kelas mengikut blok dan aras di SMK Seremban 3.')) ?></p>
        </div>
        <div class="mb-4">
            <a href="#blok-a" class="btn btn-outline-primary btn-sm me-2">Blok Akademik A</a>
            <a href="#blok-b" class="btn btn-outline-primary btn-sm">Blok Akademik B</a>
        </div>

<?php
smks3_ensure_enrolmen_sort($pdo);
$enrolments = $pdo->query('
    SELECT *
    FROM enrolmen_murid
    ORDER BY sort_order ASC, id ASC
')->fetchAll(PDO::FETCH_ASSOC);
$enrolmenGalleryItems = [];
foreach ($enrolments as $row) {
    $id = (int) ($row['id'] ?? 0);
    if ($id < 1) {
        continue;
    }
    $imgSrc = smks3_enrolmen_img_src((string) ($row['image'] ?? ''));
    $enrolmenGalleryItems[] = [
        'src' => $imgSrc,
        'key' => (string) $id,
        'id' => $id,
        'title' => (string) ($row['title'] ?? ''),
    ];
}
$enrolmenGalleryJson = htmlspecialchars(json_encode($enrolmenGalleryItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8');
$slideCount = count($enrolments);
?>

<?php if ($enrolments): ?>
<div id="enrolmentCarousel"
     class="carousel slide enrolmen-hero enrolmen-carousel-wrap"
     <?= $is_editor ? 'data-bs-interval="false"' : 'data-bs-ride="carousel"' ?>
     <?php if ($is_editor): ?>
     data-edit-block="enrolmen_gallery"
     data-edit-label="Urus gambar enrolmen"
     data-edit-hint="Tambah, buang, susun semula, dan edit tajuk setiap slaid."
     data-images-json="<?= $enrolmenGalleryJson ?>"
     <?php endif; ?>>
    <div class="carousel-inner">
        <?php foreach ($enrolments as $index => $item):
            $imgSrc = smks3_enrolmen_img_src((string) ($item['image'] ?? ''));
            ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="enrolmen-slide">
                <h3 class="fw-bold enrolmen-slide__title">
                    <?= htmlspecialchars((string) ($item['title'] ?? '')) ?>
                </h3>
                <?php if ($imgSrc !== ''): ?>
                <img src="<?= htmlspecialchars($imgSrc, ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars((string) ($item['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     class="img-fluid img-enrolment">
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <?php if ($slideCount > 1): ?>
    <button class="carousel-control-prev" type="button"
            data-bs-target="#enrolmentCarousel" data-bs-slide="prev"
            aria-label="Gambar sebelumnya">
        <i class="bi bi-chevron-left" aria-hidden="true"></i>
    </button>
    <button class="carousel-control-next" type="button"
            data-bs-target="#enrolmentCarousel" data-bs-slide="next"
            aria-label="Gambar seterusnya">
        <i class="bi bi-chevron-right" aria-hidden="true"></i>
    </button>
    <?php endif; ?>
</div>
<?php elseif (!$is_editor): ?>
<p class="text-muted">Tiada gambar enrolmen.</p>
<?php endif; ?>

        <?php if ($is_editor): ?>
        <div class="text-center mb-4">
            <button type="button" class="btn btn-outline-primary"
                    data-edit-block="enrolmen_gallery"
                    data-edit-label="Urus gambar enrolmen"
                    data-edit-hint="Tambah, buang, susun semula, dan edit tajuk setiap slaid."
                    data-images-json="<?= $enrolmenGalleryJson ?>">
                <i class="bi bi-images me-1"></i> Urus gambar
            </button>
            <p class="small text-muted mt-2 mb-0">Urus semua slaid dalam satu panel — termasuk tajuk, susunan, dan muat naik berbilang.</p>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php
$renderBlok = static function (string $blokKey, array $blok, string $sectionId) use ($is_editor): void {
    $title = (string) ($blok['title'] ?? 'Blok');
    $floors = is_array($blok['floors'] ?? null) ? $blok['floors'] : [];
    ?>
<section class="page-section" id="<?= htmlspecialchars($sectionId, ENT_QUOTES, 'UTF-8') ?>">
<div class="container">
    <div class="text-center mb-4"
         <?php if ($is_editor): ?>
         data-edit-block="enrolmen_blok"
         data-edit-label="Sunting tajuk blok"
         data-blok="<?= htmlspecialchars($blokKey, ENT_QUOTES, 'UTF-8') ?>"
         data-title="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
        <h2 class="fw-bold"><?= htmlspecialchars($title) ?></h2>
    </div>

    <div class="floor-plan">
        <?php foreach ($floors as $floorIndex => $floor):
            if (!is_array($floor)) {
                continue;
            }
            $floorName = (string) ($floor['name'] ?? 'Aras');
            $grid = (string) ($floor['grid'] ?? 'grid-7');
            $rooms = is_array($floor['rooms'] ?? null) ? $floor['rooms'] : [];
            ?>
        <h6 class="fw-bold mt-4"
            <?php if ($is_editor): ?>
            data-edit-block="enrolmen_floor"
            data-edit-label="Sunting nama aras"
            data-blok="<?= htmlspecialchars($blokKey, ENT_QUOTES, 'UTF-8') ?>"
            data-floor-index="<?= (int) $floorIndex ?>"
            data-name="<?= htmlspecialchars($floorName, ENT_QUOTES, 'UTF-8') ?>"
            <?php endif; ?>><?= htmlspecialchars($floorName) ?></h6>
        <div class="<?= htmlspecialchars($grid, ENT_QUOTES, 'UTF-8') ?>">
            <?php foreach ($rooms as $roomIndex => $room):
                if (!is_array($room)) {
                    continue;
                }
                $label = (string) ($room['label'] ?? '-');
                $class = (string) ($room['class'] ?? 'special');
                $baseClass = explode(' ', trim($class))[0] ?: 'special';
                ?>
            <div class="room <?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>"
                 <?php if ($is_editor): ?>
                 data-edit-block="enrolmen_room"
                 data-edit-label="Sunting bilik / kelas"
                 data-blok="<?= htmlspecialchars($blokKey, ENT_QUOTES, 'UTF-8') ?>"
                 data-floor-index="<?= (int) $floorIndex ?>"
                 data-room-index="<?= (int) $roomIndex ?>"
                 data-label="<?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>"
                 data-class="<?= htmlspecialchars($baseClass, ENT_QUOTES, 'UTF-8') ?>"
                 <?php endif; ?>><?= htmlspecialchars($label) ?></div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</section>
    <?php
};

$renderBlok('blok_a', $blokA, 'blok-a');
$renderBlok('blok_b', $blokB, 'blok-b');
?>

<!-- BILANGAN KELAS -->
<section class="page-section">
    <div class="container"
         <?php if ($is_editor): ?>
         data-edit-block="enrolmen_summary"
         data-edit-label="Sunting bilangan kelas"
         data-title="<?= htmlspecialchars((string) ($enrolmen['summary_title'] ?? 'Bilangan Kelas ( IKRAM/ IHSAN/ IKHLAS/ ITQAN )'), ENT_QUOTES, 'UTF-8') ?>"
         data-items="<?= htmlspecialchars(smks3_format_lines_list($enrolmen['summary'] ?? []), ENT_QUOTES, 'UTF-8') ?>"
         <?php endif; ?>>
        <div class="text-center mb-4">
            <h2 class="fw-bold bilangan-kelas-title" data-bind="enrolmen_summary_title">
                <?= htmlspecialchars((string) ($enrolmen['summary_title'] ?? 'Bilangan Kelas ( IKRAM/ IHSAN/ IKHLAS/ ITQAN )')) ?>
            </h2>
        </div>

        <div class="row g-4 text-center justify-content-center">
            <?php foreach (($enrolmen['summary'] ?? []) as $item): ?>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold"><?= htmlspecialchars((string) $item) ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
