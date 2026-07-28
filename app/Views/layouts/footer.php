<?php
require_once (defined('APP_PATH') ? APP_PATH . '/Support/visit_stats.php' : __DIR__ . '/../../Support/visit_stats.php');
$smks3_visit_stats = smks3_get_visit_stats();
if (!isset($current_page)) {
    $current_page = function_exists('smks3_current_route') ? smks3_current_route() : 'index';
}
$settings = is_array($settings ?? null) ? $settings : getSettings();
$layout = is_array($layout ?? null) ? $layout : smks3_get_layout_content();
$logged_in_editor = !empty($smks3_is_editor);
$can_edit_footer = $logged_in_editor && smks3_can_edit_footer();
$footerSocial = is_array($layout['social'] ?? null) ? $layout['social'] : [];
?>
<?php if ($current_page !== 'index') : ?>
</main>
<?php endif; ?>
    <footer class="text-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4"
                     <?php if ($can_edit_footer): ?>
                     data-edit-block="footer_about"
                     data-edit-label="Sunting pengenalan footer"
                     data-brand="<?= htmlspecialchars((string) ($layout['footer_brand'] ?? 'SMK Seremban 3'), ENT_QUOTES, 'UTF-8') ?>"
                     data-blurb="<?= htmlspecialchars((string) ($layout['footer_blurb'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                    <h5 class="mb-2" data-bind="footer_brand"><?= htmlspecialchars((string) ($layout['footer_brand'] ?? 'SMK Seremban 3')) ?></h5>
                    <p class="text-white-50 small mb-3" data-bind="footer_blurb"><?= htmlspecialchars((string) ($layout['footer_blurb'] ?? '')) ?></p>
                    <p class="text-white-50 small mb-3">
                        Laman rasmi
                        <a href="./" class="text-white">SMKS3</a> /
                        <a href="profil-sekolah" class="text-white">SMK Seremban 3</a> /
                        <a href="contact" class="text-white">SMK Seremban</a>.
                    </p>
                    <?php if (!$logged_in_editor) : ?>
                    <button type="button"
                            class="footer-staff-access"
                            data-bs-toggle="modal"
                            data-bs-target="#staffLoginModal">
                        <span class="footer-staff-access__icon" aria-hidden="true">
                            <i class="bi bi-person-workspace"></i>
                        </span>
                        <span class="footer-staff-access__copy">
                            <span class="footer-staff-access__title">Portal Kakitangan</span>
                            <span class="footer-staff-access__desc">Kemas kini berita, jadual &amp; maklumat sekolah</span>
                        </span>
                        <i class="bi bi-arrow-right-short footer-staff-access__arrow" aria-hidden="true"></i>
                    </button>
                    <?php else : ?>
                    <div class="footer-staff-access footer-staff-access--active" role="status">
                        <span class="footer-staff-access__icon" aria-hidden="true">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span class="footer-staff-access__copy">
                            <span class="footer-staff-access__title">Mod suntingan aktif</span>
                            <span class="footer-staff-access__desc">Guna butang Log keluar di bar atas untuk tamatkan sesi</span>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4"
                     <?php if ($can_edit_footer): ?>
                     data-edit-block="footer_contact"
                     data-edit-label="Sunting maklumat hubungi"
                     data-title="<?= htmlspecialchars((string) ($layout['footer_contact_title'] ?? 'Hubungi'), ENT_QUOTES, 'UTF-8') ?>"
                     data-address="<?= htmlspecialchars((string) ($settings['address'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     data-phone="<?= htmlspecialchars((string) ($settings['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     data-email="<?= htmlspecialchars((string) ($settings['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                     <?php endif; ?>>
                    <h6 class="mb-2" data-bind="footer_contact_title"><?= htmlspecialchars((string) ($layout['footer_contact_title'] ?? 'Hubungi')) ?></h6>
                    <ul class="list-unstyled small text-white-50 mb-0">
                        <li class="mb-1"><i class="bi bi-geo-alt me-2"></i><span data-bind="address"><?= nl2br(htmlspecialchars((string) ($settings['address'] ?? ''))) ?></span></li>
                        <li class="mb-1"><i class="bi bi-telephone me-2"></i><a class="text-white-50" data-bind="phone" href="tel:<?= htmlspecialchars(preg_replace('/\s+/', '', (string) ($settings['phone'] ?? '')), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) ($settings['phone'] ?? '')) ?></a></li>
                        <li><i class="bi bi-envelope me-2"></i><a class="text-white-50" data-bind="email" href="mailto:<?= htmlspecialchars((string) ($settings['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars((string) ($settings['email'] ?? '')) ?></a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <div class="row g-4 g-lg-3">
                        <div class="col-md-6"
                             <?php if ($can_edit_footer): ?>
                             data-edit-block="footer_social"
                             data-edit-label="Sunting media sosial"
                             data-title="<?= htmlspecialchars((string) ($layout['footer_social_title'] ?? 'Ikuti Kami'), ENT_QUOTES, 'UTF-8') ?>"
                             data-social-json="<?= htmlspecialchars(json_encode($footerSocial, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8') ?>"
                             <?php endif; ?>>
                            <h6 class="mb-2" data-bind="footer_social_title"><?= htmlspecialchars((string) ($layout['footer_social_title'] ?? 'Ikuti Kami')) ?></h6>
                            <div data-bind="footer_social_links">
                            <?php foreach ($footerSocial as $social):
                                if (!is_array($social)) {
                                    continue;
                                }
                                $s = smks3_normalize_social_link($social);
                                ?>
                            <a href="<?= htmlspecialchars($s['href'], ENT_QUOTES, 'UTF-8') ?>"
                               class="text-white me-3"
                               target="_blank"
                               rel="noopener noreferrer"
                               aria-label="<?= htmlspecialchars($s['label'], ENT_QUOTES, 'UTF-8') ?>"><i class="bi <?= htmlspecialchars($s['icon'], ENT_QUOTES, 'UTF-8') ?> fs-5"></i></a>
                            <?php endforeach; ?>
                            </div>
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
            <p class="text-center text-white-50 small mb-0"
               <?php if ($can_edit_footer): ?>
               data-edit-block="footer_copyright"
               data-edit-label="Sunting teks hak cipta"
               data-value="<?= htmlspecialchars((string) ($layout['footer_copyright'] ?? 'SMK Seremban 3. Hak Cipta Terpelihara.'), ENT_QUOTES, 'UTF-8') ?>"
               <?php endif; ?>>&copy; <?= date('Y') ?> <span data-bind="footer_copyright"><?= htmlspecialchars((string) ($layout['footer_copyright'] ?? 'SMK Seremban 3. Hak Cipta Terpelihara.')) ?></span></p>
        </div>
    </footer>

    <?php if (!$logged_in_editor) : ?>
    <div class="modal fade" id="staffLoginModal" tabindex="-1" aria-labelledby="staffLoginModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" data-bs-focus="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content staff-login-modal">
                <div class="staff-login-modal__header">
                    <div class="staff-login-modal__brand">
                        <img src="<?= htmlspecialchars(smks3_site_logo_src(), ENT_QUOTES, 'UTF-8') ?>" alt="" width="48" height="43" class="staff-login-modal__logo" decoding="async">
                        <div>
                            <p class="staff-login-modal__eyebrow">SMK Seremban 3</p>
                            <h2 class="staff-login-modal__title" id="staffLoginModalLabel">Portal Kakitangan</h2>
                        </div>
                    </div>
                    <button type="button" class="staff-login-modal__close" data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="staff-login-modal__body">
                    <p class="staff-login-modal__lead">
                        Log masuk untuk mengemas kini berita, jadual, dan maklumat sekolah terus di laman portal.
                    </p>
                    <form id="staffLoginForm" novalidate>
                        <div class="mb-3">
                            <label class="form-label" for="staffLoginUsername">Nama pengguna</label>
                            <div class="input-group staff-login-modal__field">
                                <span class="input-group-text"><i class="bi bi-person" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" id="staffLoginUsername" name="username" autocomplete="username" placeholder="Masukkan nama pengguna" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="staffLoginPassword">Kata laluan</label>
                            <div class="input-group staff-login-modal__field">
                                <span class="input-group-text"><i class="bi bi-lock" aria-hidden="true"></i></span>
                                <input type="password" class="form-control" id="staffLoginPassword" name="password" autocomplete="current-password" placeholder="Masukkan kata laluan" required>
                                <button type="button" class="btn btn-outline-secondary" id="staffLoginTogglePw" title="Tunjuk/sembunyi kata laluan" aria-label="Tunjuk kata laluan">
                                    <i class="bi bi-eye" id="staffLoginEye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="staff-login-modal__status" id="staffLoginStatus" aria-live="polite"></div>
                    </form>
                </div>

                <div class="staff-login-modal__footer">
                    <p class="staff-login-modal__hint mb-0">
                        <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
                        Akses untuk kakitangan sahaja
                    </p>
                    <div class="staff-login-modal__actions">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" form="staffLoginForm" class="btn btn-primary" id="staffLoginSubmit">
                            <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i> Log Masuk
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

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
            var editBar = document.getElementById('smks3EditBar');
            if (editBar) {
                document.documentElement.style.setProperty(
                    '--smks3-edit-bar-height',
                    Math.ceil(editBar.getBoundingClientRect().height) + 'px'
                );
            }
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
            // Avoid opening media while an editor is trying to click Edit on the image.
            if (document.body.classList.contains('smks3-is-editor')
                && !document.body.classList.contains('smks3-edit-preview')) {
                return;
            }
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
    <?php if (empty($smks3_is_editor)) : ?>
    <script>
    (function () {
        var form = document.getElementById('staffLoginForm');
        if (!form) return;

        var statusEl = document.getElementById('staffLoginStatus');
        var submitBtn = document.getElementById('staffLoginSubmit');
        var toggleBtn = document.getElementById('staffLoginTogglePw');
        var pwInput = document.getElementById('staffLoginPassword');
        var eye = document.getElementById('staffLoginEye');
        var modalEl = document.getElementById('staffLoginModal');

        function setStatus(msg, ok) {
            statusEl.textContent = msg || '';
            statusEl.className = 'staff-login-modal__status' + (msg ? (ok ? ' is-ok' : ' is-error') : '');
        }

        function resetLoginModal() {
            form.reset();
            setStatus('');
            if (submitBtn) submitBtn.disabled = false;
            if (pwInput) pwInput.type = 'password';
            if (eye) {
                eye.classList.add('bi-eye');
                eye.classList.remove('bi-eye-slash');
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-label', 'Tunjuk kata laluan');
            }
            form.classList.remove('was-validated');
        }

        if (toggleBtn && pwInput && eye) {
            toggleBtn.addEventListener('click', function () {
                var show = pwInput.type === 'password';
                pwInput.type = show ? 'text' : 'password';
                eye.classList.toggle('bi-eye', !show);
                eye.classList.toggle('bi-eye-slash', show);
                toggleBtn.setAttribute('aria-label', show ? 'Sembunyi kata laluan' : 'Tunjuk kata laluan');
            });
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!form.reportValidity()) return;

            setStatus('Sedang log masuk…', true);
            submitBtn.disabled = true;

            fetch('api/login.php', {
                method: 'POST',
                headers: window.smks3WithCsrf({ 'Content-Type': 'application/json', 'Accept': 'application/json' }),
                credentials: 'same-origin',
                body: JSON.stringify({
                    username: document.getElementById('staffLoginUsername').value.trim(),
                    password: pwInput.value,
                    csrf_token: window.smks3Csrf || ''
                })
            })
            .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
            .then(function (res) {
                if (!res.ok || !res.j.ok) {
                    setStatus((res.j && res.j.error) || 'Log masuk gagal.', false);
                    submitBtn.disabled = false;
                    return;
                }
                if (res.j.csrf_token) {
                    window.smks3Csrf = res.j.csrf_token;
                    var meta = document.querySelector('meta[name="csrf-token"]');
                    if (meta) meta.setAttribute('content', res.j.csrf_token);
                }
                setStatus('Berjaya! Membuka mod suntingan…', true);
                window.location.reload();
            })
            .catch(function () {
                setStatus('Ralat rangkaian. Sila cuba lagi.', false);
                submitBtn.disabled = false;
            });
        });

        // Open modal from ?login=1
        var params = new URLSearchParams(window.location.search);
        if (params.get('login') === '1' && modalEl && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
            if (params.get('idle') === '1') {
                setStatus('Sesi tamat kerana tiada aktiviti. Sila log masuk semula.', false);
            }
            params.delete('login');
            params.delete('idle');
            var next = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            if (window.history && window.history.replaceState) {
                window.history.replaceState({}, '', next);
            }
        }

        document.addEventListener('click', function (e) {
            var locked = e.target.closest('a[data-smks3-login-required]');
            if (!locked) return;
            e.preventDefault();
            if (modalEl && window.bootstrap) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
                setStatus('Log masuk kakitangan diperlukan untuk membuka fail ini.', false);
            }
        });

        if (modalEl) {
            modalEl.addEventListener('show.bs.modal', function () {
                document.documentElement.classList.add('staff-login-open');
                document.body.classList.add('staff-login-open');
            });
            modalEl.addEventListener('hide.bs.modal', function () {
                if (document.activeElement && modalEl.contains(document.activeElement)) {
                    document.activeElement.blur();
                }
            });
            modalEl.addEventListener('hidden.bs.modal', function () {
                document.documentElement.classList.remove('staff-login-open');
                document.body.classList.remove('staff-login-open');
                resetLoginModal();
            });
        }
    })();
    </script>
    <?php endif; ?>
<?php
if (!empty($smks3_is_editor)) {
    require __DIR__ . '/edit-mode.php';
}
?>
</body>
</html>
