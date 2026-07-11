<style>
/* ── Hero ── */
.hero.hero-home-image > .container.hero-home-shell {
    width: 100%;
    display: flex;
    justify-content: center;
}
.hero-home-cluster {
    display: flex;
    align-items: center;
    justify-content: center;
    width: auto;
    max-width: 100%;
}
.hero-home-cluster .hero-home-enter-text {
    flex: 0 1 auto;
    width: auto;
    max-width: 36rem;
}
.hero-home-cluster .hero-home-enter-logo {
    flex: 0 0 auto;
    display: none;
    align-items: center;
    justify-content: center;
}
@media (min-width: 1200px) {
    .hero-home-cluster {
        gap: clamp(2.5rem, 5vw, 4.5rem);
    }
    .hero-home-cluster .hero-home-enter-logo {
        display: flex;
    }
    .hero-home-cluster .hero-home-subtitle {
        max-width: 30rem;
    }
    .hero-home-cluster .hero-home-logo-img {
        margin-left: 0;
        max-height: clamp(8.5rem, 20vh, 12.5rem);
        width: auto;
    }
}
.hero-school-name {
    font-size: clamp(1.2rem, 2.4vw + 0.5rem, 2.25rem);
    line-height: 1.22;
    letter-spacing: -0.02em;
    overflow-wrap: break-word;
    word-wrap: break-word;
}
.hero-school-name .hero-school-line--name {
    font-size: clamp(1.1rem, 2.1vw + 0.4rem, 1.75rem);
    line-height: 1.28;
}
.hero-home-subtitle {
    font-size: clamp(0.875rem, 1.2vw + 0.55rem, 1.05rem);
    color: rgba(255, 255, 255, 0.88) !important;
    max-width: 34rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}
@media (max-width: 1199.98px) {
    .hero-school-name {
        font-size: clamp(1.15rem, 2vw + 0.45rem, 1.85rem);
    }
    .hero-school-name .hero-school-line--name {
        font-size: clamp(1.05rem, 1.8vw + 0.4rem, 1.55rem);
    }
    .hero-home-subtitle {
        font-size: clamp(0.875rem, 1.1vw + 0.5rem, 1rem);
        max-width: 32rem;
    }
}
@media (max-width: 767.98px) {
    .hero-school-name {
        font-size: clamp(1.05rem, 4.2vw + 0.35rem, 1.5rem);
        line-height: 1.25;
    }
    .hero-school-name .hero-school-line--name {
        font-size: clamp(0.98rem, 3.8vw + 0.3rem, 1.35rem);
    }
    .hero-home-subtitle {
        font-size: 0.9rem;
        max-width: 100%;
        padding-inline: 0.15rem;
    }
    .hero-home-actions .btn {
        font-size: 0.875rem;
        padding: 0.55rem 1rem;
    }
}
.hero-home-logo-img {
    max-width: 100%;
    height: auto;
    max-height: clamp(7rem, 18vh, 14rem);
    filter: drop-shadow(0 12px 28px rgba(0, 0, 0, 0.35));
    animation: hero-logo-float 5s ease-in-out infinite;
}
@keyframes hero-logo-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
.hero-home-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.65rem;
    justify-content: flex-start;
}
@media (max-width: 1199.98px) {
    .hero-home-cluster {
        width: 100%;
    }
    .hero-home-enter-logo {
        display: none !important;
    }
    .hero-home-enter-text {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
        text-align: center !important;
    }
    .hero-home-subtitle {
        margin-left: auto;
        margin-right: auto;
        max-width: 36rem;
    }
    .hero-home-actions {
        justify-content: center;
    }
}
.hero-home-actions .btn-light {
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.22);
}
.hero-home-actions .btn-outline-light:hover {
    transform: translateY(-1px);
}

.hero-home-enter-text,
.hero-home-enter-logo {
    opacity: 0;
    will-change: transform, opacity;
    transition:
        opacity 0.75s cubic-bezier(0.22, 1, 0.36, 1),
        transform 0.75s cubic-bezier(0.22, 1, 0.36, 1);
}
.hero-home-enter-text {
    transform: translateX(-2.5rem);
    transition-delay: 0.08s;
    min-width: 0;
    overflow-wrap: break-word;
}
.hero-home-enter-logo {
    transform: translateX(2.5rem) scale(0.96);
    transition-delay: 0.2s;
}
.hero-home-enter-text.is-visible {
    opacity: 1;
    transform: translateX(0);
}
.hero-home-enter-logo.is-visible {
    opacity: 1;
    transform: translateX(0) scale(1);
}
.hero-home-enter-text.is-scroll-out {
    opacity: 0;
    transform: translateX(-2.5rem);
    transition-delay: 0s;
    pointer-events: none;
}
.hero-home-enter-logo.is-scroll-out {
    opacity: 0;
    transform: translateX(2.5rem) scale(0.96);
    transition-delay: 0s;
    pointer-events: none;
}
.hero-home-enter-logo.is-scroll-out .hero-home-logo-img {
    animation: none;
}
@media (max-width: 767.98px) {
    .hero-home-enter-text {
        text-align: center !important;
    }
}
@media (max-width: 576px) {
    .hero-home-enter-text {
        text-align: center !important;
    }
    .hero-home-actions .btn {
        width: 100%;
        justify-content: center;
    }
    .home-quick-grid .col-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}
@media (prefers-reduced-motion: reduce) {
    .hero-home-enter-text,
    .hero-home-enter-logo {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    .home-reveal,
    .home-reveal--from-right,
    .home-reveal-fade {
        animation: none !important;
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    .hero-home-logo-img {
        animation: none !important;
    }
    .home-quick-link:hover,
    .home-news-feed__post:hover,
    .home-cta:hover,
    .home-slideshow-wrap .carousel-item:hover .slideshow-img {
        transform: none !important;
    }
}

/* ── Scroll reveals ── */
.home-reveal {
    opacity: 0;
    transform: translateY(1.25rem);
    transition:
        opacity 0.65s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms),
        transform 0.65s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms);
}
.home-reveal--from-right {
    transform: translateX(1.25rem);
}
.home-reveal.is-visible,
.home-reveal-fade.is-visible {
    opacity: 1;
}
.home-reveal:not(.home-reveal--from-right).is-visible {
    transform: translateY(0);
}
.home-reveal--from-right.is-visible {
    transform: translateX(0);
}
.home-reveal-fade {
    opacity: 0;
    transition: opacity 0.6s cubic-bezier(0.22, 1, 0.36, 1) var(--home-reveal-delay, 0ms);
}


/* ── Section headers ── */
.home-section {
    position: relative;
    z-index: 1;
    background: transparent;
    border-top: none;
}
.home-section:nth-child(even) {
    background: var(--school-pastel-section);
}
.home-section > .container {
    position: relative;
    z-index: 1;
}
.home-section-head {
    max-width: 36rem;
    margin-left: auto;
    margin-right: auto;
}
.home-section-head__title {
    font-size: clamp(1.35rem, 2.2vw, 1.65rem);
    font-weight: 700;
    color: var(--school-primary-dark);
    margin-bottom: 0.35rem;
    letter-spacing: -0.02em;
    position: relative;
    display: inline-block;
}
.home-section-head__title::after {
    content: '';
    display: block;
    width: 2.5rem;
    height: 3px;
    margin: 0.5rem auto 0;
    border-radius: 2px;
    background: linear-gradient(90deg, var(--school-accent), var(--school-primary));
    transition: width var(--motion-duration) var(--motion-ease);
}
.home-section-head:hover .home-section-head__title::after,
.home-section-head.is-visible .home-section-head__title::after {
    width: 4rem;
}
.home-section-head__desc {
    color: #64748b;
    font-size: 0.95rem;
    margin-bottom: 0;
    line-height: 1.6;
}

/* ── Quick links ── */
.home-quick-grid {
    --home-quick-gap: 1rem;
}
.home-quick-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    height: 100%;
    padding: 1.25rem 0.85rem;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.9);
    box-shadow: 0 2px 12px rgba(11, 60, 93, 0.05);
    text-decoration: none;
    color: inherit;
    transition:
        border-color var(--motion-duration) ease,
        box-shadow var(--motion-duration) var(--motion-ease),
        transform var(--motion-duration) var(--motion-ease),
        background var(--motion-duration) ease;
}
.home-quick-link--add {
    border-style: dashed;
    border-color: rgba(11, 60, 93, 0.35);
    background: rgba(11, 60, 93, 0.03);
    cursor: pointer;
}
.home-quick-link:hover {
    border-color: rgba(11, 60, 93, 0.22);
    box-shadow: 0 12px 32px rgba(11, 60, 93, 0.12);
    transform: translateY(-6px);
    background: rgba(255, 255, 255, 0.96);
    color: inherit;
}
.home-quick-link:hover .home-quick-link__icon {
    transform: scale(1.12) rotate(-6deg);
    background: rgba(11, 60, 93, 0.14);
}
.home-quick-link:hover .home-quick-link__title {
    color: var(--school-accent);
}
.home-quick-link__icon {
    margin-bottom: 0.65rem;
    transition:
        transform var(--motion-duration) var(--motion-ease),
        background var(--motion-duration) ease;
}
.home-quick-link__title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--school-primary-dark);
    margin-bottom: 0.1rem;
    transition: color var(--motion-duration) ease;
}
.home-quick-link__subtitle {
    font-size: 0.78rem;
    color: #94a3b8;
}

/* ── Slideshow ── */
.home-slideshow-wrap {
    max-width: 560px;
    margin-left: auto;
    margin-right: auto;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(11, 60, 93, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.8);
}
.home-slideshow-wrap .carousel-inner {
    border-radius: 17px;
}
.home-slideshow-wrap .carousel-item {
    background: #0f172a;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.home-slideshow-wrap .carousel-item a {
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 0;
    overflow: hidden;
    width: 100%;
}
.slideshow-img {
    width: 100%;
    height: auto;
    max-height: 720px;
    object-fit: contain;
    object-position: center;
    display: block;
    background: #0f172a;
    transition: transform 0.55s var(--motion-ease);
}
.home-slideshow-wrap .carousel-item:hover .slideshow-img {
    transform: scale(1.03);
}
.home-slideshow-wrap .carousel-control-prev,
.home-slideshow-wrap .carousel-control-next {
    width: 3rem;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.home-slideshow-wrap:hover .carousel-control-prev,
.home-slideshow-wrap:hover .carousel-control-next {
    opacity: 1;
}
.home-slideshow-wrap .carousel-control-prev-icon,
.home-slideshow-wrap .carousel-control-next-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.92);
    background-size: 45% 45%;
    filter: none;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
    transition: transform var(--motion-duration) var(--motion-ease), box-shadow var(--motion-duration) ease;
}
.home-slideshow-wrap .carousel-control-prev:hover .carousel-control-prev-icon,
.home-slideshow-wrap .carousel-control-next:hover .carousel-control-next-icon {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.28);
}
.home-slideshow-wrap .carousel-indicators {
    margin-bottom: 0.85rem;
}
.home-slideshow-wrap .carousel-indicators button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background-color: rgba(255, 255, 255, 0.55);
    opacity: 1;
    transition: transform 0.2s ease, background-color 0.2s ease;
}
.home-slideshow-wrap .carousel-indicators .active {
    background-color: #fff;
    transform: scale(1.25);
}

/* ── News feed ── */
.home-news-feed__post {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.95);
    border-radius: var(--school-radius);
    box-shadow: 0 2px 12px rgba(11, 60, 93, 0.05);
    margin-bottom: 1rem;
    transition:
        border-color var(--motion-duration) ease,
        box-shadow var(--motion-duration) var(--motion-ease),
        transform var(--motion-duration) var(--motion-ease);
}
.home-news-feed__post:last-of-type {
    margin-bottom: 0;
}
.home-news-feed__post:hover {
    border-color: rgba(11, 60, 93, 0.22);
    box-shadow: 0 10px 28px rgba(11, 60, 93, 0.1);
    transform: translateY(-4px);
}
.home-news-feed__meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    flex-wrap: wrap;
}
.home-news-feed__date {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--school-primary);
    background: rgba(11, 60, 93, 0.07);
    padding: 0.3rem 0.7rem;
    border-radius: 999px;
}
.home-news-feed__title {
    color: var(--school-primary-dark);
    font-weight: 700;
    font-size: clamp(1.1rem, 2vw, 1.35rem);
    line-height: 1.4;
    margin-bottom: 0.75rem;
}
.home-news-feed__title a {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}
.home-news-feed__title a:hover {
    color: var(--school-accent);
}
.home-news-feed__body {
    color: #475569;
    font-size: 0.93rem;
    line-height: 1.7;
}
.home-news-feed__readmore {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--school-primary);
    text-decoration: none;
    margin-top: 0.85rem;
    transition: gap 0.2s ease, color 0.2s ease;
}
.home-news-feed__readmore:hover {
    color: var(--school-accent);
    gap: 0.5rem;
}
.news-image img,
.pdf-thumb {
    width: 100%;
    border-radius: 12px;
}
.news-image img {
    height: auto;
    max-height: none;
    object-fit: contain;
    object-position: center;
}
.pdf-thumb {
    max-height: none;
    width: 100%;
    height: auto !important;
    border-radius: 12px;
    background: #f1f5f9;
}
.news-image a {
    display: block;
    border-radius: 12px;
    overflow: hidden;
    transition: box-shadow var(--motion-duration) var(--motion-ease);
}
.news-image a:hover {
    box-shadow: 0 8px 24px rgba(11, 60, 93, 0.12);
}
.news-image img,
.news-image .pdf-thumb {
    transition: transform 0.5s var(--motion-ease);
}
.news-image a:hover img,
.news-image a:hover .pdf-thumb {
    transform: scale(1.04);
}

/* ── Sidebar (sticky on desktop through news section) ── */
@media (min-width: 992px) {
    .home-berita-layout .home-berita-row {
        align-items: stretch;
    }
    .home-berita-sidebar-col {
        display: flex;
        flex-direction: column;
    }
    .home-berita-sidebar-track {
        flex: 1 1 auto;
        width: 100%;
    }
    .maklumat-sekolah-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: calc(var(--site-navbar-height, 4.75rem) + 1rem);
        z-index: 2;
        align-self: flex-start;
        width: 100%;
    }
}
.maklumat-sekolah-sidebar__contact li {
    display: flex;
    align-items: flex-start;
    gap: 0.55rem;
    padding: 0.45rem 0;
    font-size: 0.88rem;
    color: #475569;
}
.maklumat-sekolah-sidebar__contact li + li {
    border-top: 1px solid var(--school-border);
}
.maklumat-sekolah-sidebar__contact a {
    color: var(--school-primary);
    text-decoration: none;
    transition: color var(--motion-duration) ease, transform var(--motion-duration) var(--motion-ease);
}
.maklumat-sekolah-sidebar__contact a:hover {
    color: var(--school-accent);
    transform: translateX(3px);
}
.maklumat-sekolah-sidebar__contact i {
    color: var(--school-accent);
    margin-top: 0.15rem;
    flex-shrink: 0;
}

/* ── CTA ── */
.home-cta {
    background: var(--school-primary);
    border-radius: 12px;
    padding: clamp(2rem, 5vw, 2.75rem);
    box-shadow: none;
    position: relative;
    overflow: hidden;
    transition: box-shadow var(--motion-duration) var(--motion-ease), transform var(--motion-duration) var(--motion-ease);
}
.home-cta::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 30%, rgba(255, 255, 255, 0.08) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.8s var(--motion-ease);
    pointer-events: none;
}
.home-cta:hover {
    box-shadow: 0 16px 40px rgba(11, 60, 93, 0.28);
    transform: translateY(-3px);
}
.home-cta:hover::before {
    transform: translateX(100%);
}
.home-cta__inner {
    position: relative;
}
.home-cta h2 {
    color: #fff !important;
    font-size: clamp(1.4rem, 3vw, 1.85rem);
    margin-bottom: 0.65rem;
}
.home-cta p {
    color: rgba(255, 255, 255, 0.82) !important;
    max-width: 32rem;
    margin-left: auto;
    margin-right: auto;
    font-size: 0.95rem;
}
.home-cta .btn-light {
    font-weight: 700;
    padding: 0.7rem 1.75rem;
    border-radius: 10px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.home-cta .btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.28);
}

@media (max-width: 991px) {
    .maklumat-sekolah-sidebar {
        position: static;
        margin-top: 2rem;
    }
}
@media (max-width: 576px) {
    .home-cta {
        border-radius: 16px;
    }
}
</style>

<!-- ═══ HERO ═══ -->
<section class="hero hero-home-image text-white">
    <div class="container hero-home-shell">
        <div class="hero-home-cluster">
            <div class="hero-home-enter-text text-center text-xl-start">
                <h1 class="fw-bold mb-0 hero-school-name">
                    <span class="hero-school-line"
                          <?php if ($is_editor): ?>
                          data-edit-block="hero_welcome"
                          data-edit-label="Teks selamat datang"
                          data-edit-hint="Baris ucapan di bahagian atas laman utama."
                          data-value="<?= htmlspecialchars($home_content['hero_welcome'], ENT_QUOTES, 'UTF-8') ?>"
                          <?php endif; ?>
                    ><span data-bind="text"><?= htmlspecialchars($home_content['hero_welcome'], ENT_QUOTES, 'UTF-8') ?></span></span>
                    <span class="hero-school-line hero-school-line--name"
                          <?php if ($is_editor): ?>
                          data-edit-block="school_info"
                          data-edit-label="Nama & maklumat sekolah"
                          data-edit-hint="Kemaskini nama sekolah (juga digunakan di seluruh portal)."
                          data-school-name="<?= htmlspecialchars($settings['school_name'], ENT_QUOTES, 'UTF-8') ?>"
                          data-address="<?= htmlspecialchars($settings['address'], ENT_QUOTES, 'UTF-8') ?>"
                          data-phone="<?= htmlspecialchars($settings['phone'], ENT_QUOTES, 'UTF-8') ?>"
                          data-email="<?= htmlspecialchars($settings['email'], ENT_QUOTES, 'UTF-8') ?>"
                          <?php endif; ?>
                          data-bind="school_name"><?= htmlspecialchars($settings['school_name'], ENT_QUOTES, 'UTF-8') ?></span>
                </h1>
                <p class="hero-home-subtitle mt-3 mb-0"
                   <?php if ($is_editor): ?>
                   data-edit-block="hero_subtitle"
                   data-edit-label="Subtitle hero"
                   data-edit-hint="Teks di bawah nama sekolah pada bahagian atas laman."
                   data-value="<?= htmlspecialchars($home_content['hero_subtitle'], ENT_QUOTES, 'UTF-8') ?>"
                   <?php endif; ?>>
                    <span data-bind="text"><?= htmlspecialchars($home_content['hero_subtitle'], ENT_QUOTES, 'UTF-8') ?></span>
                </p>
                <div class="hero-home-actions mt-4">
                    <a href="profil-sekolah" class="btn btn-light">
                        <i class="bi bi-building me-1" aria-hidden="true"></i> Profil Sekolah
                    </a>
                    <a href="contact" class="btn btn-outline-light">
                        <i class="bi bi-envelope me-1" aria-hidden="true"></i> Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="hero-home-enter-logo">
                <img src="images/hero-logo.png" alt="<?= htmlspecialchars($settings['school_name']) ?>" class="hero-home-logo-img img-fluid" width="320" height="286" decoding="async">
            </div>
        </div>
    </div>
</section>

<div class="home-page-content">

<!-- ═══ QUICK LINKS ═══ -->
<section class="home-section py-5">
    <div class="container">
        <div class="home-section-head text-center mb-4 mb-lg-5 home-reveal"
             <?php if ($is_editor): ?>
             data-edit-block="nav_section_title"
             data-edit-label="Tajuk navigasi"
             data-value="<?= htmlspecialchars($home_content['nav_section_title'], ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="home-section-head__title" data-bind="text"><?= htmlspecialchars($home_content['nav_section_title'], ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
        <div class="row home-quick-grid g-3 g-md-4 justify-content-center">
            <?php foreach ($home_quick_links as $idx => $link) :
                $ext = !empty($link['external']);
            ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2 home-reveal-fade" style="--home-reveal-delay: <?= (int) $idx * 45 ?>ms"
                 <?php if ($is_editor): ?>
                 data-edit-block="quick_link"
                 data-edit-label="Sunting pautan navigasi"
                 data-edit-hint="Kemaskini tajuk, pautan dan ikon kad ini."
                 data-index="<?= (int) $idx ?>"
                 data-title="<?= htmlspecialchars($link['title'], ENT_QUOTES, 'UTF-8') ?>"
                 data-subtitle="<?= htmlspecialchars($link['subtitle'], ENT_QUOTES, 'UTF-8') ?>"
                 data-href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>"
                 data-icon="<?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?>"
                 data-external="<?= $ext ? '1' : '0' ?>"
                 <?php endif; ?>>
                <a href="<?= htmlspecialchars($link['href'], ENT_QUOTES, 'UTF-8') ?>"
                   class="home-quick-link"
                   <?= $ext ? 'target="_blank" rel="noopener noreferrer"' : '' ?>>
                    <span class="icon-box home-quick-link__icon" aria-hidden="true">
                        <i class="bi <?= htmlspecialchars($link['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                    </span>
                    <span class="home-quick-link__title"><?= htmlspecialchars($link['title']) ?></span>
                    <span class="home-quick-link__subtitle"><?= htmlspecialchars($link['subtitle']) ?></span>
                </a>
            </div>
            <?php endforeach; ?>
            <?php if ($is_editor): ?>
            <div class="col-6 col-md-4 col-lg-3 col-xl-2 home-reveal-fade"
                 data-edit-block="quick_link_add"
                 data-edit-label="Tambah pautan navigasi"
                 data-edit-hint="Tambah kad navigasi baharu pada laman utama.">
                <div class="home-quick-link home-quick-link--add text-center">
                    <span class="icon-box home-quick-link__icon" aria-hidden="true"><i class="bi bi-plus-lg"></i></span>
                    <span class="home-quick-link__title">Tambah</span>
                    <span class="home-quick-link__subtitle">Pautan</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ SLIDESHOW ═══ -->
<section class="home-section py-5">
    <div class="container">
        <div class="home-section-head text-center mb-4 home-reveal"
             <?php if ($is_editor): ?>
             data-edit-block="slideshow_section_title"
             data-edit-label="Tajuk Berita & Acara"
             data-value="<?= htmlspecialchars($home_content['slideshow_section_title'], ENT_QUOTES, 'UTF-8') ?>"
             <?php endif; ?>>
            <h2 class="home-section-head__title" data-bind="text"><?= htmlspecialchars($home_content['slideshow_section_title'], ENT_QUOTES, 'UTF-8') ?></h2>
        </div>
        <?php if (!empty($home_slideshow)) : ?>
        <div id="homeSlideshow" class="carousel slide home-slideshow-wrap home-reveal<?= count($home_slideshow) < 2 ? ' home-slideshow-wrap--single' : '' ?>" data-bs-ride="carousel" style="--home-reveal-delay: 80ms">
            <?php if (count($home_slideshow) > 1) : ?>
            <div class="carousel-indicators">
                <?php foreach ($home_slideshow as $idx => $slide) : ?>
                <button type="button"
                        data-bs-target="#homeSlideshow"
                        data-bs-slide-to="<?= (int) $idx ?>"
                        class="<?= $idx === 0 ? 'active' : '' ?>"
                        aria-label="Slaid <?= (int) $idx + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div class="carousel-inner">
                <?php foreach ($home_slideshow as $idx => $slide) :
                    $imgSrc = htmlspecialchars($slide['image'], ENT_QUOTES, 'UTF-8');
                    $imgAlt = htmlspecialchars($slide['alt'], ENT_QUOTES, 'UTF-8');
                    $href = trim((string) ($slide['href'] ?? ''));
                ?>
                <div class="carousel-item<?= $idx === 0 ? ' active' : '' ?>"
                     <?php if ($is_editor): ?>
                     data-edit-block="slideshow_slide"
                     data-edit-label="Sunting slaid / poster"
                     data-edit-hint="Kemaskini teks, pautan atau ganti gambar poster."
                     data-index="<?= (int) $idx ?>"
                     data-alt="<?= $imgAlt ?>"
                     data-href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
                     data-external="<?= !empty($slide['external']) ? '1' : '0' ?>"
                     <?php endif; ?>>
                    <?php if ($href !== '' && !$is_editor) :
                        $hrefEsc = htmlspecialchars($href, ENT_QUOTES, 'UTF-8');
                        $extAttrs = !empty($slide['external']) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                    <a href="<?= $hrefEsc ?>"<?= $extAttrs ?>>
                        <img src="<?= $imgSrc ?>" class="d-block slideshow-img" alt="<?= $imgAlt ?>">
                    </a>
                    <?php else : ?>
                    <img src="<?= $imgSrc ?>" class="d-block slideshow-img" alt="<?= $imgAlt ?>">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($home_slideshow) > 1) : ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeSlideshow" data-bs-slide="prev" aria-label="Slaid sebelumnya">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeSlideshow" data-bs-slide="next" aria-label="Slaid seterusnya">
                <span class="carousel-control-next-icon"></span>
            </button>
            <?php endif; ?>
        </div>
        <?php elseif ($is_editor): ?>
        <p class="text-center text-muted">Tiada slaid lagi. Tambah poster di bawah.</p>
        <?php endif; ?>
        <?php if ($is_editor): ?>
        <div class="text-center mt-3"
             data-edit-block="slideshow_add"
             data-edit-label="Tambah slaid / poster"
             data-edit-hint="Muat naik gambar poster baharu untuk paparan Berita & Acara.">
            <button type="button" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah slaid
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($news_list)) : ?>
<!-- ═══ BERITA ═══ -->
<section class="home-section py-5 home-berita-layout">
    <div class="container">
        <div class="row g-4 align-items-start home-berita-row">
            <div class="col-lg-9 home-berita-main">
                <div class="home-section-head text-start mb-4 home-reveal"
                     <?php if ($is_editor): ?>
                     data-edit-block="news_section_title"
                     data-edit-label="Tajuk berita terkini"
                     data-value="<?= htmlspecialchars($home_content['news_section_title'], ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                    <h2 class="home-section-head__title" data-bind="text"><?= htmlspecialchars($home_content['news_section_title'], ENT_QUOTES, 'UTF-8') ?></h2>
                </div>
                <div class="home-news-feed">
                    <?php foreach ($news_latest as $idx => $n) :
                        $newsUrl = smks3_news_article_url($n);
                        $pubDate = !empty($n['published_at']) ? date('d M Y', strtotime($n['published_at'])) : '';
                        $newsTitle = (string) ($n['title'] ?? '');
                    ?>
                    <article class="home-news-feed__post home-reveal-fade" style="--home-reveal-delay: <?= (int) $idx * 80 ?>ms"
                             <?php if ($is_editor): ?>
                             data-edit-block="news_item"
                             data-edit-label="Sunting berita"
                             data-edit-hint="Buka halaman butiran untuk sunting berita ini."
                             data-edit-goto="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>"
                             <?php endif; ?>>
                        <div class="card-body p-4 ps-4">
                            <?php if ($pubDate) : ?>
                            <div class="home-news-feed__meta">
                                <span class="home-news-feed__date">
                                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    <?= htmlspecialchars($pubDate) ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            <h3 class="home-news-feed__title">
                                <a href="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($newsTitle) ?>
                                </a>
                            </h3>
                            <?php if (!empty($n['pdf_file']) && file_exists(BASE_PATH . '/uploads/pdf/' . $n['pdf_file'])) : ?>
                            <div class="news-image mb-3">
                                <a href="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>">
                                    <canvas class="pdf-thumb"
                                            data-pdf="/smks3/uploads/pdf/<?= htmlspecialchars($n['pdf_file']) ?>">
                                    </canvas>
                                </a>
                            </div>
                            <?php endif; ?>
                            <div class="home-news-feed__body news-article-content mb-0">
                                <?= smks3_news_body_html($n['content'] ?? '', $n['excerpt'] ?? '') ?>
                            </div>
                            <a href="<?= htmlspecialchars($newsUrl, ENT_QUOTES, 'UTF-8') ?>" class="home-news-feed__readmore">
                                Baca selanjutnya <i class="bi bi-arrow-right" aria-hidden="true"></i>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4 home-reveal" style="--home-reveal-delay: <?= (int) (count($news_latest) * 80 + 60) ?>ms">
                    <a href="news" class="btn <?= $is_editor ? 'btn-outline-primary' : 'btn-primary' ?> px-4"
                       <?php if ($is_editor): ?>
                       data-edit-block="news_hub"
                       data-edit-label="Urus semua berita"
                       data-edit-hint="Buka halaman berita untuk tambah atau urus senarai berita."
                       data-edit-goto="news"
                       <?php endif; ?>>
                        <i class="bi bi-newspaper me-1" aria-hidden="true"></i> Semua Berita
                    </a>
                </div>
            </div>
            <div class="col-lg-3 home-berita-sidebar-col">
                <div class="home-berita-sidebar-track">
                <aside class="maklumat-sekolah-sidebar home-reveal home-reveal--from-right" style="--home-reveal-delay: 100ms" aria-label="Maklumat sekolah">
                    <div class="panel-card">
                        <h3 class="panel-card__head h6 mb-0"
                            <?php if ($is_editor): ?>
                            data-edit-block="sidebar_title"
                            data-edit-label="Tajuk sidebar"
                            data-value="<?= htmlspecialchars($home_content['sidebar_title'], ENT_QUOTES, 'UTF-8') ?>"
                            <?php endif; ?>
                            ><span data-bind="text"><?= htmlspecialchars($home_content['sidebar_title'], ENT_QUOTES, 'UTF-8') ?></span></h3>
                        <div class="panel-card__body"
                             <?php if ($is_editor): ?>
                             data-edit-block="school_info"
                             data-edit-label="Maklumat sekolah"
                             data-edit-hint="Nama, alamat, telefon dan emel sekolah."
                             data-school-name="<?= htmlspecialchars($settings['school_name'], ENT_QUOTES, 'UTF-8') ?>"
                             data-address="<?= htmlspecialchars($settings['address'], ENT_QUOTES, 'UTF-8') ?>"
                             data-phone="<?= htmlspecialchars($settings['phone'], ENT_QUOTES, 'UTF-8') ?>"
                             data-email="<?= htmlspecialchars($settings['email'], ENT_QUOTES, 'UTF-8') ?>"
                             <?php endif; ?>>
                            <p class="fw-semibold text-primary mb-3" data-bind="school_name"><?= htmlspecialchars($settings['school_name']) ?></p>
                            <ul class="list-unstyled maklumat-sekolah-sidebar__contact mb-3">
                                <li>
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                    <span data-bind="address"><?= nl2br(htmlspecialchars($settings['address'] ?? '')) ?></span>
                                </li>
                                <li>
                                    <i class="bi bi-telephone" aria-hidden="true"></i>
                                    <a data-bind="phone" href="tel:<?= preg_replace('/\s+/', '', (string)($settings['phone'] ?? '')) ?>"><?= htmlspecialchars($settings['phone'] ?? '') ?></a>
                                </li>
                                <li>
                                    <i class="bi bi-envelope" aria-hidden="true"></i>
                                    <a data-bind="email" href="mailto:<?= htmlspecialchars($settings['email'] ?? '') ?>"><?= htmlspecialchars($settings['email'] ?? '') ?></a>
                                </li>
                            </ul>
                            <a href="profil-sekolah" class="btn btn-outline-primary btn-sm w-100">Profil Sekolah</a>
                        </div>
                    </div>
                </aside>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
$cta_text_display = str_replace(
    '{school_name}',
    $settings['school_name'],
    $home_content['cta_text']
);
?>
<!-- ═══ CTA ═══ -->
<section class="home-section py-5">
    <div class="container">
        <div class="home-cta text-center home-reveal">
            <div class="home-cta__inner">
                <h2 class="fw-bold"
                    <?php if ($is_editor): ?>
                    data-edit-block="cta_title"
                    data-edit-label="Tajuk CTA"
                    data-value="<?= htmlspecialchars($home_content['cta_title'], ENT_QUOTES, 'UTF-8') ?>"
                    <?php endif; ?>
                    ><span data-bind="text"><?= htmlspecialchars($home_content['cta_title'], ENT_QUOTES, 'UTF-8') ?></span></h2>
                <p class="mb-4"
                   <?php if ($is_editor): ?>
                   data-edit-block="cta_text"
                   data-edit-label="Teks CTA"
                   data-edit-hint="Guna {school_name} untuk paparkan nama sekolah secara automatik."
                   data-value="<?= htmlspecialchars($home_content['cta_text'], ENT_QUOTES, 'UTF-8') ?>"
                   <?php endif; ?>
                   ><span data-bind="text"><?= htmlspecialchars($cta_text_display, ENT_QUOTES, 'UTF-8') ?></span></p>
                <a href="contact" class="btn btn-light btn-lg">
                    <i class="bi bi-chat-dots me-1" aria-hidden="true"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

</div><!-- /.home-page-content -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script>
(function () {
    var hero = document.querySelector('.hero-home-image');
    var cluster = document.querySelector('.hero-home-cluster');
    var text = document.querySelector('.hero-home-enter-text');
    var logo = document.querySelector('.hero-home-enter-logo');
    if (!hero || !text) return;

    function showHeroContent() {
        text.classList.add('is-visible');
        text.classList.remove('is-scroll-out');
        if (logo) {
            logo.classList.add('is-visible');
            logo.classList.remove('is-scroll-out');
        }
    }

    function hideHeroContent() {
        text.classList.add('is-scroll-out');
        if (logo) logo.classList.add('is-scroll-out');
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        showHeroContent();
        return;
    }

    var inHero = false;

    function updateHeroState(entering) {
        if (entering === inHero) return;
        inHero = entering;
        if (entering) {
            showHeroContent();
        } else {
            hideHeroContent();
        }
    }

    if ('IntersectionObserver' in window) {
        var navHeight = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--site-navbar-height')) || 76;
        var target = cluster || hero;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                var entering = entry.isIntersecting && entry.intersectionRatio >= 0.18;
                updateHeroState(entering);
            });
        }, {
            root: null,
            rootMargin: '-' + navHeight + 'px 0px -45% 0px',
            threshold: [0, 0.12, 0.18, 0.35, 0.55, 0.75]
        });

        observer.observe(target);

        requestAnimationFrame(function () {
            var rect = target.getBoundingClientRect();
            var viewHeight = window.innerHeight - navHeight;
            var visibleHeight = Math.min(rect.bottom, viewHeight + navHeight) - Math.max(rect.top, navHeight);
            if (visibleHeight > rect.height * 0.18) {
                updateHeroState(true);
            }
        });
    } else {
        showHeroContent();
    }
})();

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
    }, { root: null, rootMargin: '0px 0px -5% 0px', threshold: 0.08 });
    nodes.forEach(function (el) { obs.observe(el); });
})();

(function () {
    var layout = document.querySelector('.home-berita-layout');
    if (!layout) return;
    var main = layout.querySelector('.home-berita-main');
    var track = layout.querySelector('.home-berita-sidebar-track');
    if (!main || !track) return;

    function syncSidebarTrack() {
        if (window.innerWidth < 992) {
            track.style.minHeight = '';
            return;
        }
        track.style.minHeight = main.offsetHeight + 'px';
    }

    syncSidebarTrack();
    window.addEventListener('resize', syncSidebarTrack, { passive: true });
    if ('ResizeObserver' in window) {
        var ro = new ResizeObserver(syncSidebarTrack);
        ro.observe(main);
    }
})();
</script>
<script>
document.querySelectorAll('.pdf-thumb').forEach(function (canvas) {
    var url = canvas.getAttribute('data-pdf');
    pdfjsLib.getDocument(url).promise.then(function (pdf) {
        pdf.getPage(1).then(function (page) {
            var context = canvas.getContext('2d');
            var viewport = page.getViewport({ scale: 1 });
            var scale = canvas.parentElement.offsetWidth / viewport.width;
            var scaledViewport = page.getViewport({ scale: scale });
            canvas.height = scaledViewport.height;
            canvas.width = scaledViewport.width;
            page.render({ canvasContext: context, viewport: scaledViewport });
        });
    });
});
</script>
