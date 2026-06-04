<?php
$page_title = 'Pelan Kedudukan Kelas';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';
$pdo = getConnection();
$settings = getSettings();
require_once __DIR__ . '/includes/header.php';
?>
<style>
.img-enrolment {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: contain;
    display: block;
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
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(1);
}
@media (max-width: 768px) {
    .img-enrolment {
        max-height: none;
        object-fit: contain;
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
<section class="py-5" style="background:#d8f9ff;">
    <div class="container text-center">
        <p class="text-muted lead mb-3">Susun atur kelas mengikut blok dan aras di SMK Seremban 3.</p>
        <div class="mb-4">
            <a href="#blok-a" class="btn btn-outline-primary btn-sm me-2">Blok Akademik A</a>
            <a href="#blok-b" class="btn btn-outline-primary btn-sm">Blok Akademik B</a>
        </div>

<?php
$enrolments = $pdo->query("
    SELECT *
    FROM enrolmen_murid
    ORDER BY id DESC
")->fetchAll();
?>

<?php if($enrolments): ?>

<div id="enrolmentCarousel" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-inner">

        <?php foreach($enrolments as $index => $item): ?>

        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">

            <div class="mb-4">
                <img src="uploads/enrolmen/<?= htmlspecialchars($item['image']) ?>"
                     alt="<?= htmlspecialchars($item['title']) ?>"
                     class="img-fluid rounded shadow-sm img-enrolment">
            </div>

            <h3 class="fw-bold letter-spacing">
                <?= htmlspecialchars($item['title']) ?>
            </h3>

        </div>

        <?php endforeach; ?>

    </div>

    <button class="carousel-control-prev"
            type="button"
            data-bs-target="#enrolmentCarousel"
            data-bs-slide="prev">

        <span class="carousel-control-prev-icon"></span>

    </button>

    <button class="carousel-control-next"
            type="button"
            data-bs-target="#enrolmentCarousel"
            data-bs-slide="next">

        <span class="carousel-control-next-icon"></span>

    </button>

</div>

<?php endif; ?>

        <div class="mb-4">
        <img src="images/ENROLMENT FEB.jpg" 
             alt="Enrolment February"
             class="img-fluid rounded shadow-sm img-enrolment">
        </div>

        <h3 class="fw-bold letter-spacing">ENROLMENT FEBRUARY</h3>

    </div>
</section>

<section class="py-5" style="background:#d8f9ff;" id="blok-a">
<div class="container">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Blok Akademik A</h2>
    </div>

    <div class="floor-plan">

        <!-- ARAS 3 -->
        <h6 class="fw-bold mt-4">Aras 3</h6>
        <div class="grid-7">
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">B. BAHASA TAMIL</div>
            <div class="room special">B. BAHASA CINA</div>
            <div class="room t2">2 IKHLAS</div>
        </div>

        <!-- ARAS 2 -->
        <h6 class="fw-bold mt-4">Aras 2</h6>
        <div class="grid-7">
            <div class="room t1">1 IKRAM</div>
            <div class="room t1">1 IHSAN</div>
            <div class="room t1">1 IKHLAS</div>
            <div class="room t1">1 ITQAN</div>
            <div class="room t2">2 IKRAM</div>
            <div class="room t2">2 IHSAN</div>
            <div class="room t2">2 IKHLAS</div>
        </div>

        <!-- ARAS 1 -->
        <h6 class="fw-bold mt-4">Aras 1</h6>
        <div class="grid-7">
            <div class="room t4">4 IHSAN</div>
            <div class="room t4">4 IKRAM</div>
            <div class="room t3">3 ITQAN</div>
            <div class="room t3">3 IKHLAS</div>
            <div class="room t3">3 IHSAN</div>
            <div class="room t3">3 IKRAM</div>
            <div class="room t2">2 ITQAN</div>
        </div>

        <!-- ARAS BAWAH -->
        <h6 class="fw-bold mt-4">Aras Bawah</h6>
            <div class="grid-7">
                <div class="room special surau">SURAU</div>
                <div class="room special">BILIK PAK21</div>
                <div class="room special">BILIK MATEMATIK</div>
            </div>

    </div>
</div>
</section>


<section class="py-5" style="background:#d8f9ff;" id="blok-b">
<div class="container">
    <div class="text-center mb-4">
        <h2 class="fw-bold">Blok Akademik B</h2>
    </div>

    <div class="floor-plan">

        <!-- ARAS 2 -->
        <h6 class="fw-bold mt-4">Aras 3</h6>
        <div class="grid-7">
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
            <div class="room special">-</div>
        </div>

        <!-- ARAS 2 -->
        <h6 class="fw-bold mt-4">Aras 2</h6>
        <div class="grid-7">
            <div class="room special">BILIK MULTIMEDIA</div>
            <div class="room special">BILIK TAYANGAN</div>
            <div class="room t5">5 IKRAM</div>
            <div class="room t5">5 IHSAN</div>
            <div class="room t5">5 IKHLAS</div>
            <div class="room t4">4 ITQAN</div>
            <div class="room t4">4 IKHLAS</div>
        </div>

        <!-- ARAS 1 -->
        <h6 class="fw-bold mt-4">Aras 1</h6>
        <div class="grid-1">
            <div class="room library">PERPUSTAKAAN AL-GHAZALI</div>
        </div>

        <!-- ARAS BAWAH -->
        <h6 class="fw-bold mt-4">Aras Bawah</h6>
        <div class="grid-7">
            <div class="room special">BILIK DISIPLIN</div>
            <div class="room t5">5 ITQAN</div>
            <div class="room special">LORONG MAKMAL</div>
            <div class="room special">BILIK PEND. ISLAM</div>
            <div class="room special">GALERI SEJARAH</div>
            <div class="room special">BILIK BAHASA</div>
            <div class="room special">BILIK PERSALINAN</div>
        </div>

    </div>
</div>
</section>


<!-- BILANGAN KELAS -->
<section class="py-5" style="background:#d8f9ff;">
    <div class="container">
        <div class="text-center mb-4">
<h2 class="fw-bold bilangan-kelas-title">
    Bilangan Kelas ( IKRAM/ IHSAN/ IKHLAS/ ITQAN )
</h2>
        </div>

        <div class="row g-4 text-center">
            <?php 
            $kelas_summary = [
                "Tingkatan 1 – 4 Kelas",
                "Tingkatan 2 – 4 Kelas",
                "Tingkatan 3 – 4 Kelas",
                "Tingkatan 4 – 4 Kelas",
                "Tingkatan 5 – 4 Kelas",
                "Pra – 1 Kelas (Spectra)"
            ];

            foreach($kelas_summary as $item): ?>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold"><?= $item ?></h5>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>