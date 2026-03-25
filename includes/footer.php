<?php
require_once __DIR__ . '/visit_stats.php';
$smks3_visit_stats = smks3_get_visit_stats();
?>
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3"><i class="bi bi-mortarboard-fill me-2"></i>SMK SEREMBAN 3</h5>
                    <p class="text-white-50 small">Sekolah Menengah Kebangsaan dengan pendidikan vokasional berkualiti untuk masa depan pelajar.</p>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-uppercase mb-3">Hubungi</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li><i class="bi bi-geo-alt me-2"></i>Jalan Seremban Tiga 3 25, Seremban 3, 70300 Seremban, Negeri Sembilan</li>
                        <li><i class="bi bi-telephone me-2"></i>011-65732533</li>
                        <li><i class="bi bi-envelope me-2"></i>nea4117@moe.edu.my</li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="row g-4 g-lg-3">
                        <div class="col-md-6">
                            <h6 class="text-uppercase mb-3">Ikuti Kami</h6>
                            <a href="https://www.facebook.com/share/17rxCJHqUJ/" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-facebook fs-5"></i></a>
                            <a href="https://www.tiktok.com/@smkseremban3?lang=en" class="text-white me-3" target="_blank" rel="noopener noreferrer"><i class="bi bi-tiktok fs-5"></i></a>
                            <a href="https://www.youtube.com/@TVPSSSMKSEREMBAN3" class="text-white" target="_blank" rel="noopener noreferrer"><i class="bi bi-youtube fs-5"></i></a>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-statistik">
                                <h6 class="footer-statistik__title">Statistik Kunjungan</h6>
                                <div class="footer-statistik__total"><?= number_format($smks3_visit_stats['total']) ?></div>
                                <ul class="footer-statistik__list">
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__icon footer-statistik__icon--today" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
                                        <span class="footer-statistik__label">Hari Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['today']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__icon footer-statistik__icon--yesterday" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
                                        <span class="footer-statistik__label">Semalam</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['yesterday']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__icon footer-statistik__icon--week" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
                                        <span class="footer-statistik__label">Minggu Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['week']) ?></span>
                                    </li>
                                    <li class="footer-statistik__row">
                                        <span class="footer-statistik__icon footer-statistik__icon--month" aria-hidden="true"><i class="bi bi-person-fill"></i></span>
                                        <span class="footer-statistik__label">Bulan Ini</span>
                                        <span class="footer-statistik__value"><?= number_format($smks3_visit_stats['month']) ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <p class="text-center text-white-50 small mb-0">&copy; <?= date('Y') ?> SMK S3. Hak Cipta Terpelihara.</p>
        </div>
    </footer>
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
            var delta = y - lastY;
            if (mobileMenuOpen()) {
                nav.classList.remove('navbar-slide-hidden');
                lastY = y;
                return;
            }
            if (y < 32) {
                nav.classList.remove('navbar-slide-hidden');
            } else if (delta > 8 && y > 72) {
                nav.classList.add('navbar-slide-hidden');
            } else if (delta < -8) {
                nav.classList.remove('navbar-slide-hidden');
            }
            lastY = y;
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
    </script>
</body>
</html>
