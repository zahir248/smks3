<?php
require_once __DIR__ . '/visit_stats.php';
$smks3_visit_stats = smks3_get_visit_stats();
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
}
?>
<?php if ($current_page !== 'index') : ?>
</main>
<?php endif; ?>
    <footer class="text-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-2">SMK Seremban 3</h5>
                    <p class="text-white-50 small mb-0">Sekolah Menengah Kebangsaan dengan pendidikan berkualiti untuk masa depan pelajar.</p>
                </div>
                <div class="col-lg-4">
                    <h6 class="mb-2">Hubungi</h6>
                    <ul class="list-unstyled small text-white-50 mb-0">
                        <li class="mb-1"><i class="bi bi-geo-alt me-2"></i>Jalan Seremban Tiga 3 25, Seremban 3, 70300 Seremban, Negeri Sembilan</li>
                        <li class="mb-1"><i class="bi bi-telephone me-2"></i>011-65732533</li>
                        <li><i class="bi bi-envelope me-2"></i>nea4117@moe.edu.my</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="row g-4 g-lg-3">
                        <div class="col-md-6">
                            <h6 class="mb-2">Ikuti Kami</h6>
                            <a href="https://www.facebook.com/share/17rxCJHqUJ/" class="text-white me-3" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="bi bi-facebook fs-5"></i></a>
                            <a href="https://www.tiktok.com/@smkseremban3?lang=en" class="text-white me-3" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="bi bi-tiktok fs-5"></i></a>
                            <a href="https://www.youtube.com/@TVPSSSMKSEREMBAN3" class="text-white" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="bi bi-youtube fs-5"></i></a>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-statistik">
                                <h6 class="footer-statistik__title">Statistik Kunjungan</h6>
                                <div class="footer-statistik__total"><?= number_format($smks3_visit_stats['total']) ?></div>
                                <ul class="footer-statistik__list">
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__label">Hari Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['today']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__label">Semalam</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['yesterday']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__label">Minggu Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['week']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__label">Bulan Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['month']) ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <p class="text-center text-white-50 small mb-0">&copy; <?= date('Y') ?> SMK Seremban 3. Hak Cipta Terpelihara.</p>
        </div>
    </footer>
    <div id="site-media-overlay" role="dialog" aria-modal="true" aria-label="Paparan imej" hidden>
        <button type="button" id="site-media-overlay__close" aria-label="Tutup">&times;</button>
        <img id="site-media-overlay__img" alt="">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        var nav = document.getElementById('site-navbar');
        if (!nav) return;

        document.body.classList.add('site-nav-fixed');

        function setNavHeight() {
            document.documentElement.style.setProperty('--site-navbar-height', nav.offsetHeight + 'px');
        }
        setNavHeight();
        window.addEventListener('resize', setNavHeight);

        var lastY = window.scrollY || document.documentElement.scrollTop;

        function mobileMenuOpen() {
            var c = nav.querySelector('.navbar-collapse');
            return c && c.classList.contains('show');
        }

        function onScroll() {
            var y = window.scrollY || document.documentElement.scrollTop;
            var wasScrolled = nav.classList.contains('navbar-scrolled');

            if (y > 20) {
                nav.classList.add('navbar-scrolled');
            } else {
                nav.classList.remove('navbar-scrolled');
            }

            if (wasScrolled !== nav.classList.contains('navbar-scrolled')) {
                setNavHeight();
            }

            /* Keep navbar visible while scrolled (pill style) */
            nav.classList.remove('navbar-slide-hidden');

            if (mobileMenuOpen()) {
                lastY = y;
                return;
            }

            lastY = y;
        }

        onScroll();

        var collapse = nav.querySelector('.navbar-collapse');
        if (collapse) {
            collapse.addEventListener('show.bs.collapse', function () {
                nav.classList.add('navbar-menu-open');
            });
            collapse.addEventListener('hidden.bs.collapse', function () {
                nav.classList.remove('navbar-menu-open');
            });
        }

        var ticking = false;
        window.addEventListener('scroll', function () {
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    onScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    })();

    (function () {
        var page = document.querySelector('.site-page-content');
        if (!page) return;

        var selectors = [
            '.page-header',
            '.page-section > .container > .row > [class*="col"]',
            '.info-card',
            '.card-hover',
            '.panel-card',
            '.news-archive-feed__post'
        ];
        var seen = new Set();
        var nodes = [];

        selectors.forEach(function (sel) {
            page.querySelectorAll(sel).forEach(function (el) {
                if (seen.has(el)) return;
                seen.add(el);
                el.classList.add('site-reveal');
                nodes.push(el);
            });
        });

        if (!nodes.length) return;

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            nodes.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }
        if (!('IntersectionObserver' in window)) {
            nodes.forEach(function (el) { el.classList.add('is-visible'); });
            return;
        }

        nodes.forEach(function (el, i) {
            el.style.setProperty('--reveal-delay', (i % 8) * 55 + 'ms');
        });

        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                obs.unobserve(entry.target);
            });
        }, { root: null, rootMargin: '0px 0px -6% 0px', threshold: 0.06 });

        nodes.forEach(function (el) { obs.observe(el); });
    })();

    (function () {
        var overlay = document.getElementById('site-media-overlay');
        var img = document.getElementById('site-media-overlay__img');
        var closeBtn = document.getElementById('site-media-overlay__close');
        if (!overlay || !img || !closeBtn) return;

        function setOverlayOpen(open) {
            if (open) {
                overlay.hidden = false;
                overlay.classList.add('is-open');
                document.body.classList.add('media-overlay-open');
                return;
            }
            overlay.classList.remove('is-open');
            overlay.hidden = true;
            img.removeAttribute('src');
            if (!document.querySelector('.modal.show')) {
                document.body.classList.remove('media-overlay-open');
            }
        }

        window.smks3OpenMediaOverlay = function (src) {
            if (!src) return;
            img.src = src;
            setOverlayOpen(true);
        };

        window.smks3CloseMediaOverlay = function () {
            setOverlayOpen(false);
        };

        window.openLightbox = window.smks3OpenMediaOverlay;
        window.closeLightbox = window.smks3CloseMediaOverlay;
        window.openPeraturanModal = window.smks3OpenMediaOverlay;
        window.closePeraturanModal = window.smks3CloseMediaOverlay;

        closeBtn.addEventListener('click', window.smks3CloseMediaOverlay);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                window.smks3CloseMediaOverlay();
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
                window.smks3CloseMediaOverlay();
            }
        });

        document.addEventListener('show.bs.modal', function () {
            document.body.classList.add('media-overlay-open');
        });
        document.addEventListener('hidden.bs.modal', function () {
            if (!overlay.classList.contains('is-open')) {
                document.body.classList.remove('media-overlay-open');
            }
        });
    })();
    </script>
</body>
</html>
