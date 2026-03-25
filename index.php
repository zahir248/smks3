<?php
$page_title = 'Laman Utama';
require_once __DIR__ . '/includes/functions.php';
$settings = getSettings();
$news_list = smks3_fetch_published_news();
if ($news_list === []) {
    $news_list = [
        ['id' => 1, 'title' => 'Kemasukan Pelajar Baru 2025', 'slug' => 'ppdb-2025', 'excerpt' => 'Pendaftaran kemasukan tahun persekolahan 2025/2026 dibuka bermula 1 April 2025.', 'content' => '<p>Pendaftaran kemasukan pelajar baharu bagi sesi persekolahan 2025/2026 akan dibuka secara rasmi bermula 1 April 2025. Ibu bapa dan penjaga digalakkan membuat semakan dokumen asas awal supaya proses permohonan berjalan lancar.</p><p>Maklumat lengkap mengenai syarat kelayakan, borang, dan tarikh penting akan diumumkan melalui laman web sekolah, papan notis, serta media sosial rasmi. Sebarang pertanyaan boleh dibuat melalui pejabat sekolah pada waktu pejabat.</p>', 'published_at' => '2025-02-10 09:00:00'],
        ['id' => 2, 'title' => 'Aktiviti Latihan Industri', 'slug' => 'pkl-2025', 'excerpt' => 'Pelajar tingkatan lima menjalani latihan industri di pelbagai syarikat rakan.', 'content' => '<p>Program latihan industri (PKL) dijalankan bagi tempoh tiga bulan untuk melengkapkan komponen vokasional pelajar tingkatan lima. Pelajar ditempatkan di syarikat rakan industri yang telah dipersetujui bersama pihak sekolah.</p><p>Semasa PKL, pelajar mempraktikkan kemahiran teknikal dan kerja berpasukan di persekitaran sebenar. Penilaian dan lawatan penyeliaan akan dijalankan bagi memastikan objektif pembelajaran tercapai.</p>', 'published_at' => '2025-03-05 10:30:00'],
        ['id' => 3, 'title' => 'Program Kokurikulum 2025', 'slug' => 'kokurikulum-2025', 'excerpt' => 'Pelbagai aktiviti kokurikulum dijadualkan untuk pembangunan holistik pelajar sepanjang tahun.', 'content' => '<p>Sekolah merancang pelbagai aktiviti kokurikulum sepanjang tahun 2025 bagi memupuk kepimpinan, kerjasama, dan kesihatan mental serta fizikal pelajar. Kelab dan unit beruniform akan meneruskan sesi latihan dan pertandingan mengikut takwim.</p><p>Senarai aktiviti, tarikh, dan sebarang perubahan akan dikemas kini dari semasa ke semasa melalui papan notis sekolah dan saluran rasmi. Pelajar dinasihatkan merujuk guru penyelaras masing-masing untuk maklumat terperinci.</p>', 'published_at' => '2025-04-18 14:00:00'],
    ];
}
$news_list = smks3_sort_news_by_published_desc($news_list);
$news_latest = array_slice($news_list, 0, 3);
require_once __DIR__ . '/includes/header.php';
?>
<style>
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
    }
    @media (prefers-reduced-motion: reduce) {
        .hero-home-enter-text,
        .hero-home-enter-logo {
            animation: none;
            opacity: 1;
            transform: none;
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
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(11, 60, 93, 0.08);
        border: 1px solid var(--school-border, #e2e8f0);
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
        font-size: 1.2rem;
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
                <img src="images/logosmks3.jpg" alt="<?= htmlspecialchars($settings['school_name']) ?>" class="hero-home-logo-img img-fluid" width="320" height="120" decoding="async">
            </div>
        </div>
    </div>
</section>

<?php if (!empty($news_list)) : ?>
<section class="py-5 bg-light home-berita-layout">
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
                            <time class="text-muted small d-block mb-2" datetime="<?= date('Y-m-d', strtotime($n['published_at'])) ?>"><?= date('d F Y', strtotime($n['published_at'])) ?></time>
                            <h3 class="home-news-feed__title mb-3">
                                <a href="<?= htmlspecialchars(smks3_news_article_url($n), ENT_QUOTES, 'UTF-8') ?>" class="text-decoration-none"><?= htmlspecialchars($n['title']) ?></a>
                            </h3>
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
                            <p class="small text-muted mb-3"><?= htmlspecialchars($settings['about_summary'] ?? '') ?></p>
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

<section class="py-5" style="background: linear-gradient(180deg, var(--school-bg-subtle) 0%, #fff 100%);">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Sedia Menyertai?</h2>
        <p class="text-muted mb-4">Daftar sekarang dan capai impian anda di <?= htmlspecialchars($settings['school_name']) ?>.</p>
        <a href="contact.php" class="btn btn-primary btn-lg px-4">Hubungi Kami</a>
    </div>
</section>

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

<?php require_once __DIR__ . '/includes/footer.php'; ?>
