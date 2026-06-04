<?php
$page_title = 'Laman Utama';

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

$pdo = getConnection();

$settings = getSettings();

// pastikan function ni memang wujud dalam functions.php
$news_list = getLatestNewsByYear($pdo);

if (!is_array($news_list)) {
    $news_list = [];
}

$news_list = smks3_sort_news_by_published_desc($news_list);
$news_latest = array_slice($news_list, 0, 3);
require_once __DIR__ . '/includes/header.php';
?>
<style>
body {
    overflow-x: hidden;
}
.hero-school-name {
    font-size: clamp(1.8rem, 4vw, 3rem);
    line-height: 1.2;
}

.hero-home-logo-img {
    max-width: 100%;
    height: auto;
}
.icon-section {
    background: #d8f9ff;
}
.berita-section {
    background: #d8f9ff;
}
@media (max-width: 576px) {
    .hero-home-enter-text {
        text-align: center !important;
    }
    .hero-home-enter-logo {
        text-align: center !important;
    }
}
    @keyframes hero-home-enter-left {
        from {
            opacity: 0;
            transform: translateX(-2.25rem);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    @keyframes hero-home-enter-right {
        from {
            opacity: 0;
            transform: translateX(2.25rem);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .hero-home-enter-text {
        opacity: 0;
        animation: hero-home-enter-left 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        animation-delay: 0.08s;
    }
    .hero-home-enter-logo {
        opacity: 0;
        animation: hero-home-enter-right 0.85s cubic-bezier(0.22, 1, 0.36, 1) forwards;
        animation-delay: 0.22s;
        background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    }
    @media (prefers-reduced-motion: reduce) {
        .hero-home-enter-text,
        .hero-home-enter-logo {
            animation: none;
            opacity: 1;
            transform: none;
            background: transparent !important;
    border: none !important;
    box-shadow: none !important;
        }
        .home-reveal,
        .home-reveal--from-right,
        .home-reveal-fade {
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
    }
    /* Scroll-in reveals (toggled with .is-visible via Intersection Observer) */
    .home-reveal {
        opacity: 0;
        transform: translateY(1.35rem);
        transition:
            opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms),
            transform 0.7s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms);
        will-change: opacity, transform;
    }
    .home-reveal--from-right {
        transform: translateX(1.35rem);
    }
    .home-reveal.is-visible {
        opacity: 1;
    }
    .home-reveal:not(.home-reveal--from-right).is-visible {
        transform: translateY(0);
    }
    .home-reveal--from-right.is-visible {
        transform: translateX(0);
    }
    /* Cards: fade only so hover lift is not fighting scroll transform */
    .home-reveal-fade {
        opacity: 0;
        transition: opacity 0.65s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms);
    }
    .home-reveal-fade.is-visible {
        opacity: 1;
    }
    .home-berita-layout .maklumat-sekolah-sidebar__head {
        background: #383838;
        color: #fff;
        padding: 0.7rem 1rem;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 8px 8px 0 0;
        letter-spacing: 0.02em;
    }
    .home-berita-layout .maklumat-sekolah-sidebar__head .accent {
        color: #7dd3fc;
        font-weight: 700;
    }
    .home-berita-layout .maklumat-sekolah-sidebar__body {
        border-radius: 0 0 8px 8px;
    }
    .home-berita-layout .maklumat-sekolah-sidebar__body a[href^="tel:"],
    .home-berita-layout .maklumat-sekolah-sidebar__body a[href^="mailto:"] {
        text-decoration: none !important;
    }
    .home-berita-layout .maklumat-sekolah-sidebar__body a[href^="tel:"]:hover,
    .home-berita-layout .maklumat-sekolah-sidebar__body a[href^="mailto:"]:hover {
        text-decoration: none !important;
        color: var(--school-primary-dark, #082a42) !important;
    }
    .home-news-feed {
        width: 100%;
    }
    .home-news-feed__post {
        position: relative;
        background: #fff;  /* card berita*/
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(11, 60, 93, 0.08);
        border: none;
        margin-bottom: 1.25rem;
        transition: box-shadow 0.25s ease, transform 0.28s ease, border-color 0.2s ease;
    }
    .home-news-feed__post:last-of-type {
        margin-bottom: 0;
    }
    .home-news-feed__post:hover {
        box-shadow: 0 8px 24px rgba(11, 60, 93, 0.12);
        transform: translateY(-4px);
    }
    .home-news-feed__title {
        color: var(--school-primary-dark, #082a42);
        font-weight: 700;
        font-size: font-size: clamp(1.3rem, 3vw, 1.6rem);;
        line-height: 1.35;
    }
    .home-news-feed__title:hover {
        color: var(--school-primary, #0B3C5D);
    }
    .home-news-feed__body {
        color: #334155;
        font-size: 0.95rem;
        line-height: 1.65;
    }
    .feature-box {
        background: #fff;
        border: 1px solid #eee;
        transition: all 0.25s ease;
        cursor: pointer;
    
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .feature-box:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    }
    
    .feature-box i {
        font-size: 2rem;
    }
    
    /* bagi container nampak center & tak melebar sangat */
    .container {
        max-width: 1140px;
    }
    .news-image img {
        width: 100%;
        height: auto;
        max-height: 260px;
        object-fit: cover;
        border-radius: 10px;
    }
    @media (max-width: 991px) {
        .maklumat-sekolah-sidebar {
            margin-top: 2rem;
        }
    }
    @media (max-width: 576px) {
        .news-image img {
            max-height: 200px;
        }
        .btn {
        width: 100%;
    }
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    }
.slideshow-section {
    background: #d8f9ff;
}

/* gambar slideshow */
.slideshow-img {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 16px;
    background: #000; /* elak nampak kosong putih */
}

/* control arrow lebih nampak */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-size: 60% 60%;
    filter: invert(1); /* bagi putih */
}

/* indicator (dot) */
.carousel-indicators button {
    background-color: #0B3C5D;
    opacity: 0.5;
}

.carousel-indicators .active {
    opacity: 1;
}
.pdf-thumb {
    width: 100%;
    max-height: 260px;
    border-radius: 10px;
    background: #f1f1f1;
}
.news-image a {
    display: block;
    cursor: pointer;
}

.news-image a:hover {
    opacity: 0.9;
    transition: 0.2s;
}
</style>

<section class="hero hero-home-image text-white py-5">
    <div class="container py-4">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7 col-xl-6 hero-home-enter-text text-center text-lg-start">
                <h1 class="display-4 fw-bold mb-0 hero-school-name">
                    <span class="hero-school-line d-md-none">Selamat Datang</span>
                    <span class="hero-school-line d-md-none">Ke Portal</span>
                    <span class="hero-school-line d-none d-md-block">Selamat Datang Ke Portal</span>
                    <span class="hero-school-line hero-school-line--name"><?= htmlspecialchars($settings['school_name'], ENT_QUOTES, 'UTF-8') ?></span>
                </h1>
            </div>
            <div class="col-lg-5 col-xl-6 text-center text-lg-end hero-home-enter-logo">
                <img src="images/logosmks3 new.png" alt="<?= htmlspecialchars($settings['school_name']) ?>" class="hero-home-logo-img img-fluid" width="320" height="120" decoding="async">
            </div>
        </div>
    </div>
</section>

<section class="py-5 icon-section border-bottom">
    <div class="container">
        <div class="row text-center g-4 justify-content-center">

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="profil-sekolah.php" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-diagram-3 fs-2 text-primary"></i>
                        <h6 class="mt-3 mb-1">Pengurusan</h6>
                        <small class="text-muted">Pentadbiran</small>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="pentaksiran-peperiksaan.php" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-book fs-2 text-success"></i>
                        <h6 class="mt-3 mb-1">Kurikulum</h6>
                        <small class="text-muted">Akademik</small>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="enrolmen-murid.php" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-people-fill fs-2 text-danger"></i>
                        <h6 class="mt-3 mb-1">Hal Ehwal</h6>
                        <small class="text-muted">Murid</small>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="unit-badan-beruniform.php" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-trophy fs-2 text-warning"></i>
                        <h6 class="mt-3 mb-1">Kokurikulum</h6>
                        <small class="text-muted">Aktiviti</small>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="jawatankuasa-pibg.php" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-bank fs-2 text-info"></i>
                        <h6 class="mt-3 mb-1">PIBG</h6>
                        <small class="text-muted">Kerjasama</small>
                    </div>
                </a>
            </div>

            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="https://www.tiktok.com/@smkseremban3?lang=en" target="_blank" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-camera-video fs-2 text-secondary"></i>
                        <h6 class="mt-3 mb-1">Media</h6>
                        <small class="text-muted">Sekolah</small>
                    </div>
                </a>
            </div>
            
            <!-- NILAM -->
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="pusat-sumber.php" class="text-decoration-none text-dark" target=_blank>
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-book-half fs-2 text-primary"></i>
                        <h6 class="mt-3 mb-1">NILAM</h6>
                        <small class="text-muted">Bacaan</small>
                    </div>
                </a>
            </div>
            
            <!-- DELIMA -->
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="https://delima.moe-dl.edu.my/" class="text-decoration-none text-dark" target=_blank>
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-award fs-2 text-warning"></i>
                        <h6 class="mt-3 mb-1">DELIMA</h6>
                        <small class="text-muted">Digital</small>
                    </div>
                </a>
            </div>
            
            <!-- SUKAN -->
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="https://laporan-sukan-permainan-s3.my.canva.site/" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-trophy-fill fs-2 text-success"></i>
                        <h6 class="mt-3 mb-1">Sukan</h6>
                        <small class="text-muted">Aktiviti</small>
                    </div>
                </a>
            </div>
            
            <!-- IDME -->
            <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="feature-box p-3 rounded-3 h-100">
                        <i class="bi bi-person-badge fs-2 text-info"></i>
                        <h6 class="mt-3 mb-1">IDME</h6>
                        <small class="text-muted">Sistem</small>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<section class="py-5 slideshow-section">
    <div class="container">
        
        <div id="homeSlideshow" class="carousel slide" data-bs-ride="carousel">

            <!-- indicators (dot bawah) -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#homeSlideshow" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#homeSlideshow" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#homeSlideshow" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner rounded-4 shadow">
            
                <div class="carousel-item active">
                    <a href="https://sites.google.com/moe-dl.edu.my/vfr/kategori?authuser=0" target="_blank">
                        <img src="images/POSTER FUN RUN 2026.jpg" class="d-block w-100 slideshow-img" alt="Slide 1">
                    </a>
                </div>
            
                <div class="carousel-item">
                    <a href="https://example.com/link2" target="_blank">
                        <img src="images/slide2.jpg" class="d-block w-100 slideshow-img" alt="Slide 2">
                    </a>
                </div>
            
                <div class="carousel-item">
                    <a href="https://example.com/link3" target="_blank">
                        <img src="images/slide3.jpg" class="d-block w-100 slideshow-img" alt="Slide 3">
                    </a>
                </div>
            
            </div>

            <!-- button kiri -->
            <button class="carousel-control-prev" type="button" data-bs-target="#homeSlideshow" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <!-- button kanan -->
            <button class="carousel-control-next" type="button" data-bs-target="#homeSlideshow" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>

    </div>
</section>

<?php if (!empty($news_list)) : ?>
<section class="py-5 berita-section home-berita-layout">
    <div class="container">
        <div class="row mb-0">
            <div class="col-12 col-lg-9">
                <h2 class="text-center fw-bold mb-4 home-reveal">Berita Terkini</h2>
            </div>
            <div class="col-lg-3 d-none d-lg-block" aria-hidden="true"></div>
        </div>
        <div class="row g-3 align-items-start">
            <div class="col-lg-9">
                <div class="home-news-feed">
                    <?php foreach ($news_latest as $idx => $n) : ?>
                    <article class="home-news-feed__post home-reveal-fade" style="--home-reveal-delay: <?= (int) $idx * 85 ?>ms">
                        <div class="card-body p-4">
                            <h3 class="home-news-feed__title mb-3">
                            <a href="<?= htmlspecialchars('news-details.php?id=' . $n['id'], ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none">
                                <?= htmlspecialchars($n['title']) ?>
                            </a>
                            </h3>
<?php if (!empty($n['pdf_file']) && file_exists(__DIR__ . '/uploads/pdf/' . $n['pdf_file'])): ?>
    <div class="news-image mb-3">
        <a href="<?= htmlspecialchars('news-details.php?id=' . $n['id'], ENT_QUOTES, 'UTF-8') ?>">
            <canvas class="pdf-thumb"
                    data-pdf="/smks3/uploads/pdf/<?= htmlspecialchars($n['pdf_file']) ?>">
            </canvas>
        </a>
    </div>
<?php endif; ?>
                            <div class="home-news-feed__body news-article-content mb-0">
                                <?= smks3_news_body_html($n['content'] ?? '', $n['excerpt'] ?? '') ?>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4 home-reveal" style="--home-reveal-delay: <?= (int) (count($news_latest) * 85 + 60) ?>ms">
                    <a href="news.php" class="btn btn-primary">Semua Berita</a>
                </div>
            </div>
            <div class="col-lg-3">
                <aside class="maklumat-sekolah-sidebar home-reveal home-reveal--from-right" style="--home-reveal-delay: 120ms" aria-label="Maklumat sekolah">
                    <div class="maklumat-sekolah-sidebar__head">
                        <span class="accent">Maklumat</span> Sekolah
                    </div>
                    <div class="card maklumat-sekolah-sidebar__body border-0 shadow-sm">
                        <div class="card-body text-center text-lg-start">
                            <h3 class="h6 fw-bold text-primary mb-3"><?= htmlspecialchars($settings['school_name']) ?></h3>
                            <ul class="list-unstyled small mb-0">
                                <li class="mb-2 d-flex gap-2">
                                    <i class="bi bi-geo-alt text-primary flex-shrink-0 mt-1"></i>
                                    <span><?= nl2br(htmlspecialchars($settings['address'] ?? '')) ?></span>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    <a href="tel:<?= preg_replace('/\s+/', '', (string)($settings['phone'] ?? '')) ?>"><?= htmlspecialchars($settings['phone'] ?? '') ?></a>
                                </li>
                                <li class="mb-3">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <a href="mailto:<?= htmlspecialchars($settings['email'] ?? '') ?>"><?= htmlspecialchars($settings['email'] ?? '') ?></a>
                                </li>
                            </ul>
                            <a href="profil-sekolah.php" class="btn btn-outline-primary btn-sm w-100">Profil Sekolah</a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="py-5" style="background: #d8f9ff;">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Sedia Menyertai?</h2>
        <p class="text-muted mb-4">Daftar sekarang dan capai impian anda di <?= htmlspecialchars($settings['school_name']) ?>.</p>
        <a href="contact.php" class="btn btn-primary btn-lg px-4">Hubungi Kami</a>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
(function () {
    var nodes = document.querySelectorAll('.home-reveal, .home-reveal-fade');
    if (!nodes.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    if (!('IntersectionObserver' in window)) {
        nodes.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
        });
    }, { root: null, rootMargin: '0px 0px -6% 0px', threshold: 0.06 });
    nodes.forEach(function (el) { obs.observe(el); });
})();
</script>
<script>
document.querySelectorAll('.pdf-thumb').forEach(canvas => {
    const url = canvas.getAttribute('data-pdf');

    pdfjsLib.getDocument(url).promise.then(pdf => {
        pdf.getPage(1).then(page => {

            const context = canvas.getContext('2d');
            const viewport = page.getViewport({ scale: 1 });

            // scale ikut container width
            const scale = canvas.parentElement.offsetWidth / viewport.width;
            const scaledViewport = page.getViewport({ scale: scale });

            canvas.height = scaledViewport.height;
            canvas.width = scaledViewport.width;

            page.render({
                canvasContext: context,
                viewport: scaledViewport
            });
        });
    });
});
</script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
