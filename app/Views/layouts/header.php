<?php
if (!isset($page_title)) $page_title = 'Laman Utama';

if (!function_exists('smks3_current_route')) {
    function smks3_current_route(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir !== '/' && $scriptDir !== '' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir)) ?: '/';
        }
        $uri = trim(rawurldecode($uri), '/');
        if ($uri === '' || $uri === 'index.php' || $uri === 'index') {
            return 'index';
        }
        $uri = preg_replace('/\.php$/i', '', $uri) ?: 'index';
        return basename($uri);
    }
}

$current_page = $current_page ?? smks3_current_route();

// Bootstrap already loads helpers; keep safe requires for direct includes.
if (defined('APP_PATH')) {
    require_once APP_PATH . '/Support/security.php';
    require_once APP_PATH . '/Support/helpers.php';
    require_once APP_PATH . '/Support/breadcrumbs.php';
    require_once APP_PATH . '/Support/visit_stats.php';
} else {
    require_once __DIR__ . '/../../Support/security.php';
    require_once __DIR__ . '/../../Support/helpers.php';
    require_once __DIR__ . '/../../Support/breadcrumbs.php';
    require_once __DIR__ . '/../../Support/visit_stats.php';
}

smks3_ensure_session();
$smks3_is_editor = smks3_is_editor();
$smks3_edit_preview = false;
if ($smks3_is_editor) {
    $smks3_edit_preview = smks3_get_edit_preview();
    $body_class = trim((string) ($body_class ?? '') . ' smks3-is-editor' . ($smks3_edit_preview ? ' smks3-edit-preview' : ''));
}

$breadcrumb_items = null;
if ($current_page !== 'index' && empty($suppress_site_breadcrumb)) {
    if (!empty($custom_breadcrumbs) && is_array($custom_breadcrumbs)) {
        $breadcrumb_items = $custom_breadcrumbs;
    } else {
        $breadcrumb_items = smks3_default_breadcrumb_items($current_page, $page_title);
    }
}

smks3_record_visit();

$settings = is_array($settings ?? null) ? $settings : getSettings();
$layout = is_array($layout ?? null) ? $layout : smks3_get_layout_content();
$navbar_logo = smks3_site_logo_src();
$site_favicon = smks3_site_favicon();

if (!function_exists('smks3_resolve_seo')) {
    require_once (defined('APP_PATH') ? APP_PATH : (__DIR__ . '/../../')) . '/Support/seo.php';
}
$seo = smks3_resolve_seo(get_defined_vars());
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= htmlspecialchars(smks3_csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <title><?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="keywords" content="<?= htmlspecialchars($seo['keywords'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="author" content="<?= htmlspecialchars($seo['school_name'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="robots" content="<?= htmlspecialchars($seo['robots'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="googlebot" content="<?= htmlspecialchars($seo['robots'], ENT_QUOTES, 'UTF-8') ?>">
    <link rel="canonical" href="<?= htmlspecialchars($seo['canonical'], ENT_QUOTES, 'UTF-8') ?>">

    <meta property="og:locale" content="ms_MY">
    <meta property="og:type" content="<?= htmlspecialchars($seo['og_type'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:site_name" content="<?= htmlspecialchars(smks3_brand_aliases(), ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:title" content="<?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:url" content="<?= htmlspecialchars($seo['canonical'], ENT_QUOTES, 'UTF-8') ?>">
    <meta property="og:image" content="<?= htmlspecialchars($seo['og_image'], ENT_QUOTES, 'UTF-8') ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($seo['og_image'], ENT_QUOTES, 'UTF-8') ?>">

    <meta name="geo.region" content="MY-05">
    <meta name="geo.placename" content="Seremban">
<?php
$googleVerification = trim((string) (smks3_env('GOOGLE_SITE_VERIFICATION') ?? ''));
if ($googleVerification !== '') :
?>
    <meta name="google-site-verification" content="<?= htmlspecialchars($googleVerification, ENT_QUOTES, 'UTF-8') ?>">
<?php endif; ?>
    <link rel="icon" href="<?= htmlspecialchars($site_favicon['href'], ENT_QUOTES, 'UTF-8') ?>" type="<?= htmlspecialchars($site_favicon['type'], ENT_QUOTES, 'UTF-8') ?>">
<?php foreach ($seo['json_ld'] as $seoBlock) : ?>
    <script type="application/ld+json"><?= json_encode($seoBlock, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<?php endforeach; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
    window.smks3Csrf = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : '';
    window.smks3WithCsrf = function (headers) {
        headers = headers || {};
        if (window.smks3Csrf) headers['X-CSRF-Token'] = window.smks3Csrf;
        return headers;
    };
    window.smks3AppendCsrf = function (fd) {
        if (fd && window.smks3Csrf && typeof fd.set === 'function') {
            fd.set('csrf_token', window.smks3Csrf);
        }
        return fd;
    };
    </script>
    <style>
        :root {
            --school-primary: #0B3C5D;
            --school-primary-light: #0d4a73;
            --school-primary-dark: #082a42;
            --school-accent: #1a6fa8;
            --nav-active-bg: #2d8fd4;
            --nav-active-bg-hover: #3a9ee6;
            --school-bg-subtle: #f4f8fc;
            --school-pastel-base: #f4f8fc;
            --school-pastel-sky: #e8f2f9;
            --school-pastel-mist: #eef5fa;
            --school-pastel-section: rgba(232, 242, 249, 0.78);
            --school-pastel-header: rgba(244, 248, 252, 0.92);
            --school-border: #dce8f2;
            --school-radius: 10px;
            --school-radius-lg: 12px;
            --section-padding-y: 2.75rem;
            --bs-primary: #0B3C5D;
            --bs-primary-rgb: 11, 60, 93;
            --bs-body-font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --motion-ease: cubic-bezier(0.22, 1, 0.36, 1);
            --motion-duration: 0.28s;
            --motion-lift: -4px;
            --school-pattern-edu: url("data:image/svg+xml,%3Csvg%20width%3D%22160%22%20height%3D%22160%22%20viewBox%3D%220%200%20160%20160%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cg%20fill%3D%22none%22%20stroke%3D%22%230B3C5D%22%20stroke-opacity%3D%220.058%22%20stroke-width%3D%221.15%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpath%20d%3D%22M20%2030%20V50%22%2F%3E%3Cpath%20d%3D%22M20%2030%20C20%2026%2028%2024%2036%2028%20C44%2032%2048%2034%2048%2034%20V54%20C48%2054%2044%2050%2036%2048%20C28%2046%2020%2048%2020%2050%22%2F%3E%3Cpath%20d%3D%22M48%2034%20C48%2034%2052%2032%2060%2028%20C68%2024%2076%2026%2076%2030%20V50%20C76%2048%2068%2046%2060%2048%20C52%2050%2048%2054%2048%2054%22%2F%3E%3Cpath%20d%3D%22M98%2022%20L116%2040%22%2F%3E%3Cpath%20d%3D%22M98%2022%20L94%2026%20L112%2044%20L116%2040%22%2F%3E%3Cpath%20d%3D%22M122%2072%20L148%2078%20L135%2066%20Z%22%2F%3E%3Cpath%20d%3D%22M135%2078%20V88%22%2F%3E%3Cpath%20d%3D%22M16%2098%20H56%22%2F%3E%3Cpath%20d%3D%22M16%2098%20V106%20H56%20V106%22%2F%3E%3Cpath%20d%3D%22M24%2098%20V102%22%2F%3E%3Cpath%20d%3D%22M32%2098%20V104%22%2F%3E%3Cpath%20d%3D%22M40%2098%20V102%22%2F%3E%3Cpath%20d%3D%22M48%2098%20V104%22%2F%3E%3Cpath%20d%3D%22M88%20100%20V118%22%2F%3E%3Cpath%20d%3D%22M88%20100%20C96%2094%20104%2094%20112%20100%22%2F%3E%3Cpath%20d%3D%22M112%20100%20V118%20C104%20112%2096%20112%2088%20118%22%2F%3E%3Cpath%20d%3D%22M130%20118%20H142%22%2F%3E%3Cpath%20d%3D%22M136%20112%20V124%22%2F%3E%3Ccircle%20cx%3D%2236%22%20cy%3D%22132%22%20r%3D%224%22%2F%3E%3Cellipse%20cx%3D%2236%22%20cy%3D%22132%22%20rx%3D%2212%22%20ry%3D%224%22%20transform%3D%22rotate(45%2036%20132)%22%2F%3E%3Cellipse%20cx%3D%2236%22%20cy%3D%22132%22%20rx%3D%2212%22%20ry%3D%224%22%20transform%3D%22rotate(-45%2036%20132)%22%2F%3E%3C%2Fg%3E%3C%2Fsvg%3E");
            --school-pattern-lines: url("data:image/svg+xml,%3Csvg%20width%3D%2272%22%20height%3D%2272%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M0%2036%20H72%22%20stroke%3D%22%230B3C5D%22%20stroke-opacity%3D%220.03%22%20stroke-width%3D%221%22%2F%3E%3Cpath%20d%3D%22M0%2018%20H72%22%20stroke%3D%22%230B3C5D%22%20stroke-opacity%3D%220.022%22%20stroke-width%3D%221%22%2F%3E%3Cpath%20d%3D%22M0%2054%20H72%22%20stroke%3D%22%230B3C5D%22%20stroke-opacity%3D%220.022%22%20stroke-width%3D%221%22%2F%3E%3C%2Fsvg%3E");
        }
        html {
            overflow-x: clip;
            max-width: 100%;
        }
        body {
            font-family: var(--bs-body-font-family);
            color: #1e293b;
            line-height: 1.6;
            width: 100%;
            max-width: 100%;
            overflow-x: clip;
            background-color: var(--school-pastel-base);
            background-image:
                var(--school-pattern-edu),
                var(--school-pattern-lines),
                radial-gradient(rgba(11, 60, 93, 0.055) 0.8px, transparent 0.8px),
                linear-gradient(180deg, #f6f9fc 0%, #eaf2f8 52%, #e3edf6 100%);
            background-size: 160px 160px, 72px 72px, 24px 24px, 100% 100%;
            background-attachment: fixed, fixed, fixed, scroll;
        }
        @media (max-width: 1199.98px) {
            body {
                background-attachment: scroll, scroll, scroll, scroll;
            }
            #site-navbar > .container,
            .hero .container,
            .site-page-content .container,
            .home-page-content .container,
            .page-header .container,
            .page-breadcrumb-wrap .container {
                width: 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 991.98px) {
            body {
                background-attachment: scroll, scroll, scroll, scroll;
            }
        }
        body.site-nav-fixed {
            padding-top: var(--site-navbar-height, 4.75rem);
        }
        body.page-home.site-nav-fixed {
            padding-top: 0 !important;
        }
        <?php if (!empty($smks3_is_editor)) : ?>
        /* Edit mode: stack Mod suntingan + menu with the same menu spacing as normal mode */
        body.smks3-is-editor {
            --smks3-edit-bar-height: 2.75rem;
        }
        body.smks3-is-editor.site-nav-fixed {
            padding-top: calc(var(--site-navbar-height, 4.75rem) + var(--smks3-edit-bar-height, 2.75rem)) !important;
        }
        body.page-home.smks3-is-editor.site-nav-fixed {
            padding-top: 0 !important;
        }
        body.smks3-is-editor #site-navbar {
            top: var(--smks3-edit-bar-height, 2.75rem) !important;
        }
        /* Critical: apply before footer edit-mode.css so refresh never flashes outlines */
        [data-edit-block] {
            position: relative;
            outline: 2px dashed transparent;
            outline-offset: 4px;
            --smks3-edit-dash: rgba(26, 111, 168, 0.5);
            /* Draw inside the box so overflow:hidden parents never clip the border */
            --smks3-edit-dash-inset: 0px;
        }
        /* Image / media edit blocks: keep Edit badge clear of the media hit area */
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block]:has(img),
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block].ubk-image-empty,
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block].pra-image-empty {
            outline-offset: 14px;
            padding: 0.85rem;
            --smks3-edit-dash-inset: 0px;
        }
        body.smks3-is-editor.smks3-edit-preview [data-edit-block],
        body.smks3-is-editor.smks3-edit-preview [data-edit-block]:hover,
        body.smks3-is-editor.smks3-edit-preview [data-edit-block].is-editing {
            outline: none !important;
            outline-color: transparent !important;
            cursor: inherit !important;
            transition: none !important;
        }
        body.smks3-is-editor.smks3-edit-preview [data-edit-block]::before,
        body.smks3-is-editor.smks3-edit-preview [data-edit-block]::after {
            content: none !important;
            display: none !important;
        }
        body.smks3-is-editor.smks3-edit-preview [data-edit-block$="_add"] {
            display: none !important;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block] {
            cursor: pointer;
            outline: none;
            /* Allow dash/badge when the edit host itself used to clip */
            overflow: visible;
        }
        /* Animated dashed border — clockwise, drawn inside so it always matches Edit badge */
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block]::before {
            content: "";
            position: absolute;
            inset: var(--smks3-edit-dash-inset, 0px);
            z-index: 6;
            border-radius: inherit;
            pointer-events: none;
            background:
                repeating-linear-gradient(90deg, var(--smks3-edit-dash) 0 8px, transparent 8px 16px) top / 16px 2px repeat-x,
                repeating-linear-gradient(90deg, var(--smks3-edit-dash) 0 8px, transparent 8px 16px) bottom / 16px 2px repeat-x,
                repeating-linear-gradient(180deg, var(--smks3-edit-dash) 0 8px, transparent 8px 16px) left / 2px 16px repeat-y,
                repeating-linear-gradient(180deg, var(--smks3-edit-dash) 0 8px, transparent 8px 16px) right / 2px 16px repeat-y;
            background-repeat: repeat-x, repeat-x, repeat-y, repeat-y;
            background-position: 0 0, 0 100%, 0 0, 100% 0;
            animation: smks3-edit-dash-cw 1s linear infinite;
        }
        @keyframes smks3-edit-dash-cw {
            to {
                background-position: 16px 0, -16px 100%, 0 -16px, 100% 16px;
            }
        }
        @media (prefers-reduced-motion: reduce) {
            body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block]::before {
                content: none !important;
                display: none !important;
                animation: none;
            }
            body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block] {
                outline: 2px dashed var(--smks3-edit-dash);
                outline-offset: -2px;
            }
        }
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block].btn,
        body.smks3-is-editor:not(.smks3-edit-preview) a.btn[data-edit-block] {
            background-color: var(--bs-btn-bg, var(--school-primary)) !important;
            color: var(--bs-btn-color, #fff) !important;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block]::after {
            content: "Edit";
            position: absolute;
            top: 0.35rem;
            right: 0.35rem;
            z-index: 7;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #fff;
            background: #0B3C5D;
            padding: 0.15rem 0.45rem;
            border-radius: 6px;
            pointer-events: none;
        }
        /* Navigasi cards are full-link hits — use Edit badge to open the panel */
        body.smks3-is-editor:not(.smks3-edit-preview) [data-edit-block="quick_link"]::after {
            pointer-events: auto;
            cursor: pointer;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) footer [data-edit-block] {
            --smks3-edit-dash: rgba(255, 255, 255, 0.65);
        }
        body.smks3-is-editor:not(.smks3-edit-preview) footer [data-edit-block]::after {
            background: #fff;
            color: #0B3C5D;
        }
        body.smks3-is-editor.smks3-edit-ready:not(.smks3-edit-preview) [data-edit-block]:hover,
        body.smks3-is-editor.smks3-edit-ready:not(.smks3-edit-preview) [data-edit-block].is-editing {
            --smks3-edit-dash: #1a6fa8;
        }
        body.smks3-is-editor.smks3-edit-ready:not(.smks3-edit-preview) footer [data-edit-block]:hover,
        body.smks3-is-editor.smks3-edit-ready:not(.smks3-edit-preview) footer [data-edit-block].is-editing {
            --smks3-edit-dash: #ffffff;
        }
        /* Parents that commonly clipped child edit borders */
        body.smks3-is-editor:not(.smks3-edit-preview) .home-cta,
        body.smks3-is-editor:not(.smks3-edit-preview) .home-slideshow-wrap,
        body.smks3-is-editor:not(.smks3-edit-preview) .home-slideshow-wrap .carousel-inner,
        body.smks3-is-editor:not(.smks3-edit-preview) .home-slideshow-wrap .carousel-item,
        body.smks3-is-editor:not(.smks3-edit-preview) .card[data-edit-block],
        body.smks3-is-editor:not(.smks3-edit-preview) .card-hover[data-edit-block],
        body.smks3-is-editor:not(.smks3-edit-preview) .table-responsive:has([data-edit-block]) {
            overflow: visible !important;
        }
        /* Table / list cells: keep a clear dash even inside scrollports */
        body.smks3-is-editor:not(.smks3-edit-preview) td[data-edit-block],
        body.smks3-is-editor:not(.smks3-edit-preview) th[data-edit-block],
        body.smks3-is-editor:not(.smks3-edit-preview) li[data-edit-block] {
            --smks3-edit-dash-inset: 1px;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) .carousel-inner {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) .carousel-item {
            display: block !important;
            float: none;
            margin-right: 0;
            opacity: 1 !important;
            transform: none !important;
            transition: none !important;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) .carousel-control-prev,
        body.smks3-is-editor:not(.smks3-edit-preview) .carousel-control-next,
        body.smks3-is-editor:not(.smks3-edit-preview) .carousel-indicators {
            display: none !important;
            pointer-events: none !important;
        }
        .smks3-edit-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1080;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem 1rem;
            min-height: 2.75rem;
            padding: 0.45rem 1.75rem;
            background: #0B3C5D;
            color: #fff;
            font-size: 0.875rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            box-sizing: border-box;
        }
        .smks3-edit-bar__left,
        .smks3-edit-bar__right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-width: 0;
        }
        .smks3-edit-bar__left {
            flex: 1 1 auto;
            overflow: visible;
            min-width: 0;
        }
        .smks3-edit-bar__right {
            flex: 0 0 auto;
            flex-wrap: nowrap;
        }
        .smks3-edit-bar__user {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            min-width: 0;
            max-width: 100%;
        }
        .smks3-edit-bar__user-text {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .smks3-edit-bar__perm-tip {
            position: relative;
            display: inline-flex;
            align-items: center;
            flex-shrink: 0;
            color: rgba(255, 255, 255, 0.85);
            cursor: help;
            outline: none;
        }
        .smks3-edit-bar__perm-tip:hover,
        .smks3-edit-bar__perm-tip:focus-visible {
            color: #fff;
        }
        .smks3-edit-bar__perm-tip > .bi {
            font-size: 0.95rem;
            line-height: 1;
        }
        .smks3-edit-bar__perm-tip-panel {
            position: absolute;
            top: calc(100% + 0.35rem);
            left: 0;
            z-index: 1090;
            display: none;
            width: min(18.5rem, calc(100vw - 1.5rem));
            padding: 0.75rem 0.85rem;
            border-radius: 10px;
            background: #fff;
            color: #1e293b;
            font-size: 0.78rem;
            font-weight: 400;
            line-height: 1.4;
            text-align: left;
            white-space: normal;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.22);
            border: 1px solid rgba(11, 60, 93, 0.12);
        }
        .smks3-edit-bar__perm-tip::before {
            content: "";
            position: absolute;
            top: 100%;
            left: 0;
            width: 2rem;
            height: 0.45rem;
        }
        .smks3-edit-bar__perm-tip-panel::before {
            content: "";
            position: absolute;
            top: -5px;
            left: 0.55rem;
            width: 10px;
            height: 10px;
            background: #fff;
            border-left: 1px solid rgba(11, 60, 93, 0.12);
            border-top: 1px solid rgba(11, 60, 93, 0.12);
            transform: rotate(45deg);
        }
        .smks3-edit-bar__perm-tip:hover .smks3-edit-bar__perm-tip-panel,
        .smks3-edit-bar__perm-tip:focus-within .smks3-edit-bar__perm-tip-panel {
            display: block;
        }
        .smks3-edit-bar__perm-tip-title {
            display: block;
            margin-bottom: 0.45rem;
            font-weight: 700;
            font-size: 0.8rem;
            color: #0B3C5D;
        }
        .smks3-edit-bar__perm-tip-group {
            margin: 0 0 0.55rem;
        }
        .smks3-edit-bar__perm-tip-group:last-child {
            margin-bottom: 0;
        }
        .smks3-edit-bar__perm-tip-group-label {
            display: block;
            margin-bottom: 0.15rem;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            color: #64748b;
        }
        .smks3-edit-bar__perm-tip-list {
            margin: 0;
            padding-left: 1.05rem;
        }
        .smks3-edit-bar__perm-tip-list li {
            margin: 0.05rem 0;
        }
        .smks3-edit-bar__perm-tip-empty {
            margin: 0;
            color: #64748b;
        }
        .smks3-edit-bar a {
            color: #fff;
            text-decoration: none;
            opacity: 0.9;
        }
        .smks3-edit-bar a:hover { opacity: 1; text-decoration: underline; }
        .smks3-edit-bar .badge-edit {
            flex-shrink: 0;
            background: rgba(255,255,255,0.18);
            border-radius: 999px;
            padding: 0.2rem 0.65rem;
            font-weight: 600;
            white-space: nowrap;
        }
        body.smks3-edit-preview .smks3-edit-bar .badge-edit {
            background: rgba(255, 255, 255, 0.1);
            opacity: 0.85;
        }
        .smks3-edit-bar__toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            margin: 0;
            cursor: pointer;
            user-select: none;
            font-weight: 600;
            font-size: 0.82rem;
            flex-shrink: 0;
        }
        .smks3-edit-bar__toggle input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .smks3-edit-bar__switch {
            position: relative;
            width: 2.4rem;
            height: 1.25rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.45);
            flex-shrink: 0;
        }
        body.smks3-edit-ready .smks3-edit-bar__switch {
            transition: background 0.2s ease, border-color 0.2s ease;
        }
        .smks3-edit-bar__switch::after {
            content: "";
            position: absolute;
            top: 1px;
            left: 2px;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #fff;
        }
        body.smks3-edit-ready .smks3-edit-bar__switch::after {
            transition: transform 0.2s ease;
        }
        /* Visual state follows body class (set before paint) — avoids toggle slide on navigation */
        body.smks3-is-editor:not(.smks3-edit-preview) .smks3-edit-bar__switch {
            background: #22c55e;
            border-color: #86efac;
        }
        body.smks3-is-editor:not(.smks3-edit-preview) .smks3-edit-bar__switch::after {
            transform: translateX(1.05rem);
        }
        .smks3-edit-bar__toggle-label {
            white-space: nowrap;
        }
        body.smks3-edit-preview [data-edit-mode-on],
        body:not(.smks3-edit-preview) [data-edit-mode-off] {
            display: none !important;
        }
        .smks3-edit-bar__logout {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.85rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.55);
            background: rgba(255, 255, 255, 0.12);
            color: #fff !important;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none !important;
            opacity: 1 !important;
            transition: background 0.2s ease, border-color 0.2s ease;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .smks3-edit-bar__logout:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: #fff;
        }
        @media (max-width: 991.98px) {
            .smks3-edit-bar {
                gap: 0.5rem 0.75rem;
                padding: 0.4rem 0.75rem;
                font-size: 0.8rem;
            }
            .smks3-edit-bar__left,
            .smks3-edit-bar__right {
                gap: 0.5rem;
            }
            .smks3-edit-bar__toggle-label {
                display: none;
            }
            .smks3-edit-bar__logout {
                padding: 0.3rem 0.55rem;
                font-size: 0.8rem;
            }
        }
        @media (max-width: 575.98px) {
            .smks3-edit-bar {
                flex-wrap: wrap;
                row-gap: 0.35rem;
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }
            .smks3-edit-bar__left {
                flex: 1 1 calc(100% - 6.5rem);
                order: 1;
            }
            .smks3-edit-bar__right {
                flex: 0 0 auto;
                order: 2;
                margin-left: auto;
            }
            .smks3-edit-bar .badge-edit {
                padding: 0.15rem 0.5rem;
            }
            .smks3-edit-bar .badge-edit .smks3-edit-bar__badge-text {
                display: none;
            }
            .smks3-edit-bar .badge-edit .bi {
                margin-right: 0 !important;
            }
            .smks3-edit-bar__logout-text {
                display: none;
            }
            .smks3-edit-bar__logout {
                padding: 0.3rem 0.45rem;
            }
        }
        <?php endif; ?>
        #site-navbar.navbar-slide-hidden {
            transform: translateY(-100%);
        }
        /* Ensure all text uses theme colors */
        .text-primary, .card .bi, a.text-primary { color: var(--school-primary) !important; }
        a:not(.nav-link):not(.navbar-brand):not(.dropdown-item):not(footer a) { color: var(--school-primary); }
        a:not(.nav-link):not(.navbar-brand):not(.dropdown-item):not(footer a):hover { color: var(--school-primary-dark); }
        .btn-link {
            color: var(--school-primary) !important;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            transition: color var(--motion-duration) ease, gap var(--motion-duration) var(--motion-ease);
        }
        .btn-link:hover {
            color: var(--school-primary-dark) !important;
            text-decoration: underline;
            gap: 0.45rem;
        }

        /* ── Navbar (default — unchanged at top) ── */
        #site-navbar {
            background: #fff !important;
            border-bottom: 1px solid var(--school-border);
            box-shadow: none;
            padding: 0.6rem 0;
            width: 100%;
            max-width: 100%;
            transition: transform 0.32s ease, background 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease, padding 0.35s ease;
        }
        .navbar {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0;
        }
        #site-navbar .navbar-pill {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            width: 100%;
            transition:
                background 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                box-shadow 0.4s cubic-bezier(0.22, 1, 0.36, 1),
                border-color 0.4s ease,
                border-radius 0.4s ease,
                backdrop-filter 0.4s ease,
                padding 0.4s ease,
                width 0.4s ease;
        }

        /* Floating glass pill — desktop only, when scrolling */
        @media (min-width: 992px) {
            #site-navbar.navbar-scrolled {
                background: transparent !important;
                border-bottom-color: transparent !important;
                padding: 0.6rem 0 !important;
            }
            #site-navbar.navbar-scrolled .navbar-pill {
                width: 100%;
                max-width: 100%;
                flex-wrap: nowrap;
                gap: 0;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(20px) saturate(1.5);
                -webkit-backdrop-filter: blur(20px) saturate(1.5);
                border: 1px solid rgba(226, 232, 240, 0.95);
                border-radius: 999px;
                box-shadow:
                    0 8px 32px rgba(11, 60, 93, 0.12),
                    0 2px 6px rgba(0, 0, 0, 0.05);
                padding: 0 0.85rem;
            }
            body.page-home #site-navbar.navbar-scrolled .nav-link {
                color: #475569 !important;
                text-shadow: none;
            }
            body.page-home #site-navbar.navbar-scrolled .nav-link:hover {
                color: var(--school-primary) !important;
                background: rgba(11, 60, 93, 0.06) !important;
            }
            body.page-home #site-navbar.navbar-scrolled .nav-link.active,
            body.page-home #site-navbar.navbar-scrolled .nav-link.dropdown-toggle.active {
                color: var(--school-primary) !important;
                background: transparent !important;
                font-weight: 700;
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-brand img {
                filter: none;
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-toggler {
                border-color: var(--school-border);
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-toggler-icon {
                filter: none;
            }
        }

        /* Homepage — invisible over hero (until scroll) */
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) {
            background: transparent !important;
            border-bottom-color: transparent !important;
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .nav-link {
            color: rgba(255, 255, 255, 0.92) !important;
            text-shadow: 0 1px 6px rgba(0, 0, 0, 0.4);
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.12) !important;
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .nav-link.active,
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .nav-link.dropdown-toggle.active {
            color: #fff !important;
            background: transparent !important;
            font-weight: 700;
            text-shadow: 0 1px 8px rgba(0, 0, 0, 0.45);
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .navbar-brand img {
            filter: drop-shadow(0 1px 6px rgba(0, 0, 0, 0.35));
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.4);
        }
        body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .navbar-toggler-icon {
            filter: brightness(0) invert(1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.35rem;
            color: var(--school-primary) !important;
            letter-spacing: -0.02em;
            white-space: nowrap;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 0.2rem 0;
            background: transparent;
            box-shadow: none;
            border-radius: 0;
            transition: transform var(--motion-duration) var(--motion-ease), opacity var(--motion-duration) ease;
        }
        .navbar-brand:hover {
            color: var(--school-primary-dark) !important;
            transform: scale(1.03);
        }
        .navbar-brand img {
            height: 42px;
            width: auto;
            max-width: min(220px, 48vw);
            object-fit: contain;
            display: block;
            transition: transform var(--motion-duration) var(--motion-ease);
        }
        .navbar-brand:hover img {
            transform: rotate(-2deg) scale(1.04);
        }
        .nav-link {
            font-weight: 500;
            font-size: 0.875rem;
            color: #475569 !important;
            padding: 0.45rem 0.65rem !important;
            border-radius: 6px;
            transition:
                color var(--motion-duration) ease,
                background var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease);
        }
        .nav-link:hover {
            color: var(--school-primary) !important;
            background: rgba(11, 60, 93, 0.06) !important;
            transform: translateY(-1px);
        }
        .navbar .nav-link.active,
        .navbar .nav-link.dropdown-toggle.active {
            color: var(--school-primary) !important;
            background: transparent !important;
            font-weight: 700;
            box-shadow: none;
        }
        .navbar .nav-link.active:hover,
        .navbar .nav-link.dropdown-toggle.active:hover,
        .navbar .nav-link.active:focus,
        .navbar .nav-link.dropdown-toggle.active:focus {
            color: var(--school-primary-dark) !important;
            background: transparent !important;
        }
        .navbar .dropdown-toggle::after {
            vertical-align: 0.15em;
        }
        .navbar .dropdown-menu {
            border: 1px solid var(--school-border);
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            padding: 0.35rem 0;
            min-width: 13rem;
            margin-top: 0.25rem !important;
        }
        .navbar .dropdown-menu > li {
            margin: 0;
            padding: 0;
        }
        .navbar .dropdown-item {
            display: block;
            margin: 0;
            border-radius: 0;
            padding: 0.5rem 1rem;
            width: 100%;
            box-sizing: border-box;
            font-size: 0.875rem;
            color: #334155;
            transition:
                color var(--motion-duration) ease,
                background var(--motion-duration) ease,
                padding-left var(--motion-duration) var(--motion-ease);
        }
        .navbar .dropdown-item:hover,
        .navbar .dropdown-item:focus {
            color: var(--school-primary-dark) !important;
            background: #f8fafc !important;
            padding-left: 1.2rem;
        }
        .navbar .dropdown-item.active {
            color: var(--school-primary) !important;
            background: rgba(11, 60, 93, 0.08) !important;
            font-weight: 600;
        }
        .navbar .dropdown-item.active:hover,
        .navbar .dropdown-item.active:focus {
            color: var(--school-primary-dark) !important;
            background: rgba(11, 60, 93, 0.1) !important;
        }
        .navbar-toggler {
            border-color: var(--school-border);
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            transition:
                transform var(--motion-duration) var(--motion-ease),
                background var(--motion-duration) ease,
                border-color var(--motion-duration) ease;
        }
        .navbar-toggler:hover {
            background: rgba(11, 60, 93, 0.06);
            transform: scale(1.06);
        }
        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(11, 60, 93, 0.15);
        }
        .navbar-toggler-icon {
            filter: none;
            opacity: 1;
        }
        @media (max-width: 991.98px) {
            #site-navbar .navbar-pill {
                justify-content: flex-start;
            }
            #site-navbar .navbar-brand {
                margin-right: 0;
            }
            #site-navbar .navbar-toggler {
                margin-left: auto;
                order: 2;
            }
            #site-navbar .navbar-collapse {
                order: 3;
                flex-basis: 100%;
                width: 100%;
            }
            #site-navbar.navbar-scrolled {
                background: #fff !important;
                border-bottom: 1px solid var(--school-border) !important;
                padding: 0.6rem 0 !important;
            }
            #site-navbar.navbar-scrolled .navbar-pill {
                background: transparent !important;
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            body.page-home #site-navbar.navbar-scrolled {
                background: #fff !important;
                border-bottom-color: var(--school-border) !important;
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-brand img {
                filter: none;
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-toggler {
                border-color: var(--school-border);
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-toggler-icon {
                filter: none;
            }
            .navbar .navbar-collapse {
                background: #fff;
                border: 1px solid var(--school-border);
                border-radius: 8px;
                margin-top: 0.65rem;
                padding: 0.5rem;
                box-shadow: none;
            }
            body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .navbar-collapse {
                background: rgba(0, 0, 0, 0.55);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border-color: rgba(255, 255, 255, 0.15);
            }
            body.page-home #site-navbar:not(.navbar-scrolled):not(.navbar-menu-open) .navbar-collapse .nav-link {
                color: #fff !important;
                text-shadow: none;
            }
            body.page-home #site-navbar.navbar-scrolled .nav-link {
                color: #475569 !important;
                text-shadow: none;
            }
            body.page-home #site-navbar.navbar-scrolled .nav-link.active {
                color: var(--school-primary) !important;
                background: transparent !important;
                font-weight: 700;
            }
            body.page-home #site-navbar.navbar-scrolled .navbar-toggler-icon {
                filter: none;
            }
            .navbar .nav-link {
                padding: 0.55rem 0.75rem !important;
            }
            .navbar .dropdown-menu {
                border: none;
                box-shadow: none;
                margin-top: 0 !important;
                padding-left: 0.5rem;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            #site-navbar .nav-link {
                font-size: 0.8rem;
                padding: 0.38rem 0.42rem !important;
            }
            .navbar-brand img {
                height: 38px;
                max-width: min(160px, 22vw);
            }
            #site-navbar.navbar-scrolled .navbar-pill,
            #site-navbar .navbar-pill {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .page-header {
                padding: 1.35rem 0 1.2rem;
            }
            :root {
                --section-padding-y: 2.35rem;
            }
        }
        @media (max-width: 1199.98px) {
            #site-navbar .navbar-pill {
                max-width: 100%;
            }
        }
        @media (max-width: 767.98px) {
            :root {
                --section-padding-y: 2rem;
            }
            .navbar-brand img {
                height: 36px;
                max-width: min(180px, 55vw);
            }
            .page-header {
                padding: 1.25rem 0 1.1rem;
            }
            .page-breadcrumb-wrap .breadcrumb {
                font-size: 0.8rem;
            }
            .info-card__body {
                padding: 1rem;
            }
        }

        .hero {
            background: linear-gradient(145deg, var(--school-primary-dark) 0%, var(--school-primary) 50%, var(--school-primary-light) 100%);
            padding: 4rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero-home-image {
            background:
                linear-gradient(90deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.68) 38%, rgba(0, 0, 0, 0.58) 58%, rgba(0, 0, 0, 0.5) 100%),
                url("images/smk-seremban-3-hero.jpg") center / cover no-repeat;
            background-attachment: scroll, fixed;
        }
        .hero.hero-home-image {
            min-height: 100vh;
            min-height: 100svh;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 !important;
            padding-top: var(--site-navbar-height, 4.75rem) !important;
            margin: 0 !important;
            box-sizing: border-box;
            overflow: visible;
        }
        body.smks3-is-editor .hero.hero-home-image {
            padding-top: calc(var(--site-navbar-height, 4.75rem) + var(--smks3-edit-bar-height, 2.75rem)) !important;
        }
        @media (max-width: 1199.98px) {
            .hero-home-image {
                background-attachment: scroll, scroll;
            }
            .hero.hero-home-image {
                min-height: auto;
                padding-top: calc(var(--site-navbar-height, 4.75rem) + 2rem) !important;
                padding-bottom: 3rem !important;
            }
            body.smks3-is-editor .hero.hero-home-image {
                padding-top: calc(var(--site-navbar-height, 4.75rem) + var(--smks3-edit-bar-height, 2.75rem) + 2rem) !important;
            }
            .hero.hero-home-image .hero-school-name .hero-school-line {
                white-space: normal;
            }
            .hero-home-logo-img {
                display: none !important;
            }
        }
        @media (max-width: 767.98px) {
            .hero.hero-home-image {
                padding-top: calc(var(--site-navbar-height, 4.75rem) + 1.5rem) !important;
                padding-bottom: 2.5rem !important;
            }
            body.smks3-is-editor .hero.hero-home-image {
                padding-top: calc(var(--site-navbar-height, 4.75rem) + var(--smks3-edit-bar-height, 2.75rem) + 1.5rem) !important;
            }
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .hero-home-image::before {
            display: none;
        }
        .hero-home-image .lead {
            text-shadow: 0 1px 14px rgba(0, 0, 0, 0.45);
        }
        .hero-home-logo-img {
            max-height: 11rem;
            width: auto;
            max-width: min(100%, 22rem);
            object-fit: contain;
        }
        @media (min-width: 992px) {
            .hero-home-logo-img {
                max-height: 13rem;
            }
        }
        .hero .container { position: relative; z-index: 1; }
        /* Force hero text visible on dark background */
        .hero, .hero h1, .hero .lead, .hero p, .hero .display-4 { color: #ffffff !important; }
        .hero h1 { font-weight: 700; letter-spacing: -0.03em; text-shadow: 0 2px 12px rgba(0,0,0,0.25); }
        .hero.hero-home-image .hero-school-name {
            line-height: 1.22;
            text-shadow: 0 2px 24px rgba(0, 0, 0, 0.55), 0 1px 4px rgba(0, 0, 0, 0.7);
        }
        .hero.hero-home-image .hero-school-name .hero-school-line {
            display: block;
        }
        @media (min-width: 1200px) {
            .hero.hero-home-image .hero-school-name .hero-school-line {
                white-space: nowrap;
            }
        }
        .hero .lead { opacity: 0.95; font-weight: 500; color: rgba(255,255,255,0.95) !important; }
        .hero .bi { color: rgba(255,255,255,0.85) !important; }
        .hero .btn-light { background: #ffffff; color: var(--school-primary) !important; font-weight: 600; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.2); }
        .hero .btn-outline-light { color: #ffffff !important; font-weight: 600; border: 2px solid rgba(255,255,255,0.9); padding: 0.65rem 1.5rem; border-radius: 8px; background: transparent; }

        .card, .card-hover {
            border: 1px solid var(--school-border);
            border-radius: var(--school-radius-lg);
            overflow: hidden;
            background: #fff;
            transition:
                border-color var(--motion-duration) ease,
                box-shadow var(--motion-duration) var(--motion-ease),
                transform var(--motion-duration) var(--motion-ease);
            box-shadow: none;
        }
        .card-hover:hover {
            border-color: rgba(11, 60, 93, 0.22);
            box-shadow: 0 10px 28px rgba(11, 60, 93, 0.1);
            transform: translateY(var(--motion-lift));
        }
        .card-hover:hover .icon-box {
            transform: scale(1.08) rotate(-4deg);
            background: rgba(11, 60, 93, 0.12);
        }
        .card .bi { color: var(--school-primary); }
        .bg-light { background: var(--school-bg-subtle) !important; }

        .btn-primary {
            background: var(--school-primary);
            border-color: var(--school-primary);
            color: #ffffff !important;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            transition:
                background var(--motion-duration) ease,
                border-color var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease),
                box-shadow var(--motion-duration) var(--motion-ease);
        }
        .btn-primary:hover {
            background: var(--school-primary-light);
            border-color: var(--school-primary-light);
            color: #ffffff !important;
            box-shadow: 0 6px 18px rgba(11, 60, 93, 0.22);
            transform: translateY(-2px);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(11, 60, 93, 0.18);
        }
        .btn-outline-primary {
            border-color: var(--school-primary);
            color: var(--school-primary);
            font-weight: 600;
            border-radius: 8px;
            transition:
                background var(--motion-duration) ease,
                border-color var(--motion-duration) ease,
                color var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease),
                box-shadow var(--motion-duration) var(--motion-ease);
        }
        .btn-outline-primary:hover {
            background: rgba(11, 60, 93, 0.08);
            border-color: var(--school-primary);
            color: var(--school-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(11, 60, 93, 0.1);
        }
        .btn-outline-primary:active {
            transform: translateY(0);
        }

        section:not(.hero):not(.page-section):not(.home-section) {
            padding-top: var(--section-padding-y);
            padding-bottom: var(--section-padding-y);
        }
        section h1 { font-size: 1.9rem; margin-bottom: 0.5rem; }
        section h2 { font-size: 1.35rem; margin-bottom: 1.25rem; }
        section h1, section h2 { font-weight: 700; color: var(--school-primary-dark); letter-spacing: -0.02em; }
        section .lead { color: #475569 !important; }
        .text-muted { color: #52606d !important; }
        section .text-muted, .card-text.text-muted { color: #475569 !important; }
        section h5, section h6 { color: var(--school-primary-dark) !important; font-weight: 600; }
        .card .card-body h6.text-muted { color: #475569 !important; font-weight: 600; }
        .card-title { color: var(--school-primary-dark) !important; font-weight: 600; }
        .card-body p, .card .card-text { color: #334155 !important; }
        .smks3-news-card-excerpt { text-align: justify; text-justify: inter-word; }
        .news-article-content p { margin-bottom: 1rem; text-align: justify; text-justify: inter-word; }
        .news-article-content p:last-child { margin-bottom: 0; }
        section p, section li { color: #334155; }
        small.text-muted { color: #64748b !important; }

        /* ── Page layout system ── */
        .site-page-content,
        .home-page-content {
            position: relative;
            isolation: isolate;
        }
        .page-header {
            background: var(--school-pastel-header);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-bottom: 1px solid rgba(220, 232, 242, 0.9);
            padding: 1.75rem 0 1.5rem;
        }
        .page-header__title {
            font-size: clamp(1.45rem, 2.5vw, 1.8rem);
            font-weight: 700;
            color: var(--school-primary-dark);
            margin: 0;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }
        .page-header__lead {
            color: #64748b;
            font-size: 0.95rem;
            margin: 0.45rem 0 0;
            max-width: 40rem;
            line-height: 1.65;
        }
        .page-section {
            padding: var(--section-padding-y) 0;
            background: transparent;
            border-top: none;
        }
        .site-page-content > .page-section:nth-child(even) {
            background: var(--school-pastel-section);
        }
        .page-section--muted {
            background: var(--school-pastel-section);
        }
        .page-section + .page-section {
            border-top: none;
        }
        section.bg-light {
            background: var(--school-pastel-mist) !important;
        }
        .page-section__intro {
            max-width: 40rem;
            margin-bottom: 2rem;
        }
        .page-section__title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--school-primary-dark);
            margin-bottom: 1rem;
        }

        /* Info / stat cards */
        .info-card {
            border: 1px solid var(--school-border);
            border-radius: var(--school-radius);
            background: #fff;
            height: 100%;
            transition:
                border-color var(--motion-duration) ease,
                box-shadow var(--motion-duration) var(--motion-ease),
                transform var(--motion-duration) var(--motion-ease);
        }
        .info-card:hover {
            border-color: rgba(11, 60, 93, 0.22);
            box-shadow: 0 8px 24px rgba(11, 60, 93, 0.08);
            transform: translateY(var(--motion-lift));
        }
        .info-card:hover .icon-box {
            transform: scale(1.1) rotate(-3deg);
            background: rgba(11, 60, 93, 0.12);
        }
        .info-card__body {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            padding: 1.15rem;
        }
        .icon-box {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            background: rgba(11, 60, 93, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--school-primary);
            font-size: 1.15rem;
            transition:
                transform var(--motion-duration) var(--motion-ease),
                background var(--motion-duration) ease;
        }
        .info-card__label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 0.2rem;
        }
        .info-card__value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--school-primary-dark);
            margin: 0;
            line-height: 1.45;
        }

        /* Panels & content */
        .panel-card {
            border: 1px solid var(--school-border);
            border-radius: var(--school-radius-lg);
            background: #fff;
            height: 100%;
            transition:
                border-color var(--motion-duration) ease,
                box-shadow var(--motion-duration) var(--motion-ease),
                transform var(--motion-duration) var(--motion-ease);
        }
        .panel-card:hover {
            border-color: rgba(11, 60, 93, 0.18);
            box-shadow: 0 8px 24px rgba(11, 60, 93, 0.07);
            transform: translateY(-2px);
        }
        .panel-card__head {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--school-border);
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--school-primary-dark);
            margin: 0;
        }
        .panel-card__body {
            padding: 1.25rem;
        }
        .content-narrow {
            max-width: 720px;
            margin-left: auto;
            margin-right: auto;
        }
        .content-narrow h1 {
            font-size: clamp(1.4rem, 2.5vw, 1.75rem);
            margin-bottom: 0.75rem;
        }

        /* News archive */
        .news-archive-feed__post {
            background: #fff;
            border: 1px solid var(--school-border);
            border-radius: var(--school-radius);
            margin-bottom: 1rem;
            transition:
                border-color var(--motion-duration) ease,
                box-shadow var(--motion-duration) var(--motion-ease),
                transform var(--motion-duration) var(--motion-ease);
        }
        .news-archive-feed__post:last-child {
            margin-bottom: 0;
        }
        .news-archive-feed__post:hover {
            border-color: rgba(11, 60, 93, 0.22);
            box-shadow: 0 8px 22px rgba(11, 60, 93, 0.08);
            transform: translateY(-3px);
        }
        .news-archive-feed__post .post-title a {
            color: var(--school-primary-dark);
            text-decoration: none;
            font-weight: 700;
        }
        .news-archive-feed__post .post-title a:hover {
            color: var(--school-accent);
        }
        .pdf-thumb {
            width: 100%;
            max-height: 220px;
            border-radius: 8px;
            background: #f1f5f9;
        }

        /* Hero: ensure all text stays white (override section rules) */
        .hero h1, .hero h2, .hero .lead, .hero p, .hero .display-4 { color: #ffffff !important; }
        .hero .text-muted { color: rgba(255,255,255,0.9) !important; }

        footer {
            background: var(--school-primary-dark) !important;
            padding: 2.5rem 0 1.5rem;
            margin-top: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        footer h5, footer h6 {
            font-weight: 600;
            color: #fff;
            font-size: 0.95rem;
            text-transform: none;
            letter-spacing: 0;
        }
        footer a { color: rgba(255,255,255,0.8); text-decoration: none; transition: color var(--motion-duration) ease, transform var(--motion-duration) var(--motion-ease); }
        footer a:hover { color: #fff; }
        footer a[aria-label] {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            transition:
                color var(--motion-duration) ease,
                background var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease);
        }
        footer a[aria-label]:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-3px) scale(1.08);
        }
        footer .text-white-50 { color: rgba(255,255,255,0.65) !important; }
        footer hr { border-color: rgba(255,255,255,0.12); margin: 1.75rem 0 1.25rem; }

        .footer-statistik__title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .footer-statistik__total {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 0.75rem;
        }
        .footer-statistik__list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-statistik__row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            padding: 0.35rem 0;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.75);
        }
        .footer-statistik__label { flex: 1 1 auto; min-width: 0; }
        .footer-statistik__value {
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .footer-staff-access {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            max-width: 22rem;
            text-align: left;
            padding: 0.85rem 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
            transition:
                background var(--motion-duration) ease,
                border-color var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease);
        }
        button.footer-staff-access {
            cursor: pointer;
        }
        button.footer-staff-access:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
            color: #fff;
        }
        .footer-staff-access--active {
            cursor: default;
            opacity: 0.92;
        }
        .footer-staff-access__icon {
            flex-shrink: 0;
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            font-size: 1.15rem;
        }
        .footer-staff-access__copy {
            flex: 1 1 auto;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }
        .footer-staff-access__title {
            font-size: 0.92rem;
            font-weight: 700;
            line-height: 1.25;
        }
        .footer-staff-access__desc {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.72);
            line-height: 1.35;
        }
        .footer-staff-access__arrow {
            flex-shrink: 0;
            font-size: 1.5rem;
            opacity: 0.85;
        }

        .staff-login-modal {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 22px 56px rgba(11, 60, 93, 0.28);
        }
        .staff-login-modal__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.35rem;
            background:
                linear-gradient(135deg, #0B3C5D 0%, #0d4a73 55%, #1a6fa8 100%);
            color: #fff;
        }
        .staff-login-modal__brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 0;
        }
        .staff-login-modal__logo {
            width: 48px;
            height: auto;
            flex-shrink: 0;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.2));
            animation: staffLoginLogoIn 0.55s var(--motion-ease) both;
        }
        .staff-login-modal__eyebrow {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.78);
            margin: 0 0 0.2rem;
        }
        .staff-login-modal__title {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.25;
            color: #fff;
        }
        .staff-login-modal__close {
            flex-shrink: 0;
            width: 2.1rem;
            height: 2.1rem;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, transform 0.2s ease;
        }
        .staff-login-modal__close:hover {
            background: rgba(255, 255, 255, 0.28);
            transform: scale(1.05);
        }
        .staff-login-modal__body {
            padding: 1.35rem 1.35rem 0.75rem;
            background: #fff;
        }
        .staff-login-modal__lead {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 1.15rem;
        }
        .staff-login-modal__field .input-group-text {
            background: #f8fafc;
            border-color: var(--school-border);
            color: var(--school-primary);
        }
        .staff-login-modal__field .form-control:focus {
            z-index: 3;
        }
        .staff-login-modal__status {
            min-height: 1.25rem;
            font-size: 0.85rem;
            margin: 0.65rem 0 0;
        }
        .staff-login-modal__status.is-error { color: #b91c1c; }
        .staff-login-modal__status.is-ok { color: #15803d; }
        html.staff-login-open,
        body.staff-login-open {
            overflow: hidden !important;
            overscroll-behavior: none;
        }
        #staffLoginModal.modal {
            overscroll-behavior: contain;
        }
        .staff-login-modal__footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            padding: 1rem 1.35rem 1.25rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .staff-login-modal__hint {
            font-size: 0.78rem;
            color: #64748b;
            display: inline-flex;
            align-items: center;
        }
        .staff-login-modal__actions {
            display: flex;
            gap: 0.5rem;
            margin-left: auto;
        }
        .staff-login-modal__actions .btn {
            min-width: 6.5rem;
        }
        #staffLoginModal.show .staff-login-modal__header {
            animation: staffLoginHeaderIn 0.45s var(--motion-ease) both;
        }
        #staffLoginModal.show .staff-login-modal__body {
            animation: staffLoginBodyIn 0.5s var(--motion-ease) 0.05s both;
        }
        #staffLoginModal.show .staff-login-modal__footer {
            animation: staffLoginBodyIn 0.5s var(--motion-ease) 0.1s both;
        }
        @keyframes staffLoginLogoIn {
            from { opacity: 0; transform: scale(0.85); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes staffLoginHeaderIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes staffLoginBodyIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 575.98px) {
            .staff-login-modal__footer {
                flex-direction: column;
                align-items: stretch;
            }
            .staff-login-modal__actions {
                margin-left: 0;
                width: 100%;
            }
            .staff-login-modal__actions .btn {
                flex: 1 1 auto;
            }
        }

        .form-control, .form-select {
            border-radius: 8px;
            border-color: var(--school-border);
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus {
            border-color: var(--school-primary);
            box-shadow: 0 0 0 3px rgba(11, 60, 93, 0.15);
        }
        .form-label { font-weight: 500; color: #334155; }
        .alert { border-radius: 10px; border: none; }
        @media (min-width: 992px) {
            #site-navbar .navbar-pill {
                flex-wrap: nowrap;
            }
            #site-navbar .navbar-pill .navbar-collapse {
                flex: 1 1 auto;
                justify-content: flex-end;
            }
            #site-navbar .navbar-nav {
                flex-wrap: nowrap;
                margin-left: auto;
                align-items: center;
                gap: 0;
            }
            .navbar-nav .nav-item {
                flex: 0 0 auto;
            }
            .navbar-nav .nav-link {
                white-space: nowrap;
            }
        }

        /* Breadcrumbs */
        .page-breadcrumb-wrap {
            background: rgba(238, 245, 250, 0.82);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-bottom: 1px solid rgba(220, 232, 242, 0.85);
        }
        .page-breadcrumb-wrap .breadcrumb {
            font-size: 0.875rem;
            flex-wrap: wrap;
            --bs-breadcrumb-divider: "/";
            --bs-breadcrumb-divider-color: #94a3b8;
        }
        .page-breadcrumb-wrap .breadcrumb-item a,
        .page-breadcrumb-wrap .breadcrumb-item a:link,
        .page-breadcrumb-wrap .breadcrumb-item a:visited {
            color: var(--school-primary) !important;
            text-decoration: none;
            font-weight: 500;
        }
        .page-breadcrumb-wrap .breadcrumb-item a:hover {
            color: var(--school-primary-dark) !important;
            text-decoration: underline;
        }
        .page-breadcrumb-wrap .breadcrumb-item a:focus-visible {
            outline: 2px solid var(--school-primary);
            outline-offset: 2px;
            border-radius: 2px;
        }
        .page-breadcrumb-wrap .breadcrumb-item.active {
            color: #64748b !important;
            font-weight: 500;
        }
        .page-breadcrumb-wrap .breadcrumb-item + .breadcrumb-item::before {
            color: #94a3b8;
        }

        /* ── Motion & interactivity (site-wide) ── */
        .site-reveal {
            opacity: 0;
            transform: translateY(1.15rem);
            transition:
                opacity 0.65s var(--motion-ease) var(--reveal-delay, 0ms),
                transform 0.65s var(--motion-ease) var(--reveal-delay, 0ms);
        }
        .site-reveal--from-left {
            transform: translateX(-1.15rem);
        }
        .site-reveal.is-visible {
            opacity: 1;
            transform: translate(0, 0);
        }
        .page-breadcrumb-wrap .breadcrumb-item a {
            position: relative;
            text-decoration: none !important;
        }
        .page-breadcrumb-wrap .breadcrumb-item a::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -1px;
            width: 100%;
            height: 2px;
            background: var(--school-accent);
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform var(--motion-duration) var(--motion-ease);
        }
        .page-breadcrumb-wrap .breadcrumb-item a:hover::after {
            transform: scaleX(1);
        }
        @media (min-width: 992px) {
            .navbar .dropdown-menu {
                display: block;
                margin-top: 0;
                opacity: 0;
                visibility: hidden;
                transform: translateY(8px);
                transition:
                    opacity 0.22s var(--motion-ease),
                    transform 0.22s var(--motion-ease),
                    visibility 0.22s;
                pointer-events: none;
            }
            .navbar .dropdown:hover .dropdown-menu,
            .navbar .dropdown-menu.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
                pointer-events: auto;
            }
        }
        .hero .btn-light,
        .hero .btn-outline-light {
            transition:
                background var(--motion-duration) ease,
                color var(--motion-duration) ease,
                border-color var(--motion-duration) ease,
                transform var(--motion-duration) var(--motion-ease),
                box-shadow var(--motion-duration) var(--motion-ease);
        }
        .hero .btn-light:hover {
            background: #f1f5f9;
            color: var(--school-primary-dark) !important;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.28);
        }
        .hero .btn-outline-light:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.2);
            border-color: #ffffff;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }
        @media (prefers-reduced-motion: reduce) {
            .site-reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
            .navbar-brand:hover,
            .navbar-brand:hover img,
            .nav-link:hover,
            .card-hover:hover,
            .info-card:hover,
            .panel-card:hover,
            .news-archive-feed__post:hover,
            .btn-primary:hover,
            .btn-outline-primary:hover,
            footer a[aria-label]:hover {
                transform: none !important;
            }
        }

        /* Image lightbox — above fixed navbar */
        body.media-overlay-open {
            overflow: hidden;
        }
        body.media-overlay-open #site-navbar {
            visibility: hidden;
            pointer-events: none;
        }
        #site-media-overlay {
            position: fixed;
            inset: 0;
            z-index: 11000;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.92);
            padding: 1.5rem;
        }
        #site-media-overlay.is-open {
            display: flex;
        }
        #site-media-overlay__img {
            max-width: min(96vw, 1400px);
            max-height: 92vh;
            width: auto;
            height: auto;
            object-fit: contain;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.45);
        }
        #site-media-overlay__close {
            position: absolute;
            top: 0.85rem;
            right: 1rem;
            width: 2.75rem;
            height: 2.75rem;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            font-size: 1.75rem;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background var(--motion-duration) ease;
        }
        #site-media-overlay__close:hover {
            background: rgba(255, 255, 255, 0.24);
        }
    </style>
</head>
<body<?= !empty($body_class) ? ' class="' . htmlspecialchars($body_class, ENT_QUOTES, 'UTF-8') . '"' : '' ?>>
<?php if (!empty($smks3_is_editor)) :
    $smks3_editor_name = htmlspecialchars((string) ($_SESSION['username'] ?? 'Editor'), ENT_QUOTES, 'UTF-8');
    $smks3_editor_role = smks3_editor_role();
    $smks3_logout = $smks3_editor_role === 'superadmin' ? 'superadmin/logout.php' : 'admin/logout.php';
    $smks3_edit_preview = !empty($smks3_edit_preview);
    $smks3_unit_name = $smks3_editor_role === 'superadmin' ? null : smks3_current_user_unit_name();
    $smks3_perm_groups = [];
    if ($smks3_editor_role === 'superadmin') {
        $smks3_editor_label = $smks3_editor_name . ' · superadmin';
    } elseif ($smks3_unit_name) {
        $smks3_unit_display = function_exists('mb_substr')
            ? mb_strtoupper(mb_substr($smks3_unit_name, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($smks3_unit_name, 1, null, 'UTF-8')
            : ucfirst($smks3_unit_name);
        $smks3_editor_label = $smks3_editor_name . ' · Admin Unit ' . htmlspecialchars($smks3_unit_display, ENT_QUOTES, 'UTF-8');
        $smks3_perm_groups = smks3_current_user_permission_labels();
    } else {
        $smks3_editor_label = $smks3_editor_name . ' · admin';
    }
?>
<div class="smks3-edit-bar" role="status" id="smks3EditBar">
    <div class="smks3-edit-bar__left">
        <span class="badge-edit" id="smks3EditBadge">
            <span data-edit-mode-on><i class="bi bi-pencil-square me-1"></i><span class="smks3-edit-bar__badge-text"> Mod suntingan</span></span>
            <span data-edit-mode-off><i class="bi bi-eye me-1"></i><span class="smks3-edit-bar__badge-text"> Pratonton</span></span>
        </span>
        <span class="smks3-edit-bar__user">
            <span class="smks3-edit-bar__user-text" title="<?= $smks3_editor_label ?>"><?= $smks3_editor_label ?></span>
            <?php if ($smks3_unit_name !== null) : ?>
            <span class="smks3-edit-bar__perm-tip" tabindex="0" aria-label="Halaman yang boleh diurus">
                <i class="bi bi-info-circle" aria-hidden="true"></i>
                <span class="smks3-edit-bar__perm-tip-panel" role="tooltip">
                    <span class="smks3-edit-bar__perm-tip-title">Anda boleh mengurus</span>
                    <?php if ($smks3_perm_groups === []) : ?>
                        <p class="smks3-edit-bar__perm-tip-empty">Tiada kebenaran ditetapkan. Hubungi superadmin.</p>
                    <?php else : ?>
                        <?php foreach ($smks3_perm_groups as $groupLabel => $labels) : ?>
                        <div class="smks3-edit-bar__perm-tip-group">
                            <span class="smks3-edit-bar__perm-tip-group-label"><?= htmlspecialchars($groupLabel, ENT_QUOTES, 'UTF-8') ?></span>
                            <ul class="smks3-edit-bar__perm-tip-list">
                                <?php foreach ($labels as $label) : ?>
                                <li><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </span>
            </span>
            <?php endif; ?>
        </span>
    </div>
    <div class="smks3-edit-bar__right">
        <label class="smks3-edit-bar__toggle" title="Hidupkan atau matikan mod suntingan">
            <input type="checkbox" id="smks3EditModeToggle"<?= $smks3_edit_preview ? '' : ' checked' ?>>
            <span class="smks3-edit-bar__switch" aria-hidden="true"></span>
            <span class="smks3-edit-bar__toggle-label">
                <span data-edit-mode-on>Suntingan aktif</span>
                <span data-edit-mode-off>Pratonton</span>
            </span>
        </label>
        <span class="d-none d-lg-inline opacity-75" id="smks3EditBarHint">
            <span data-edit-mode-on>Klik kandungan untuk sunting</span>
            <span data-edit-mode-off>Paparan seperti pelawat</span>
        </span>
        <a href="<?= htmlspecialchars($smks3_logout, ENT_QUOTES, 'UTF-8') ?>"
           class="smks3-edit-bar__logout"
           title="Tamatkan sesi suntingan">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
            <span class="smks3-edit-bar__logout-text">Log keluar</span>
        </a>
    </div>
</div>
<?php endif; ?>
    <nav id="site-navbar" class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <div class="navbar-pill">
            <a class="navbar-brand" href="./">
                <img src="<?= htmlspecialchars($navbar_logo, ENT_QUOTES, 'UTF-8') ?>"
                     alt="<?= htmlspecialchars((string) ($settings['school_name'] ?? 'SMK Seremban 3'), ENT_QUOTES, 'UTF-8') ?>"
                     width="47" height="42" decoding="async">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto flex-lg-nowrap">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="./">Laman Utama</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['profil-sekolah','misi-visi-sekolah','sejarah-sekolah','senarai-pengetua','pelan-sekolah','lencana-lagu-sekolah','pengurusan-tertinggi','guru-apk','kalendar-akademik','cuti-perayaan'], true) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Pengurusan Dan Pentadbiran
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'profil-sekolah' ? 'active' : '' ?>" href="profil-sekolah">Profil Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'misi-visi-sekolah' ? 'active' : '' ?>" href="misi-visi-sekolah">FPK, Visi, Misi, Motto Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'sejarah-sekolah' ? 'active' : '' ?>" href="sejarah-sekolah">Sejarah Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'senarai-pengetua' ? 'active' : '' ?>" href="senarai-pengetua">Senarai Pengetua</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pelan-sekolah' ? 'active' : '' ?>" href="pelan-sekolah">Pelan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'lencana-lagu-sekolah' ? 'active' : '' ?>" href="lencana-lagu-sekolah">Lencana & Lagu Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pengurusan-tertinggi' ? 'active' : '' ?>" href="pengurusan-tertinggi">Pengurusan Tertinggi Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'guru-apk' ? 'active' : '' ?>" href="guru-apk">Barisan Guru Dan AKP</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'kalendar-akademik' ? 'active' : '' ?>" href="kalendar-akademik">Kalendar Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'cuti-perayaan' ? 'active' : '' ?>" href="cuti-perayaan">Cuti Perayaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="_blank" href="images/CARTA ORGANISASI INDUK.pdf">Carta Organisasi Induk</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['pentaksiran-peperiksaan','analisis-pat-t4-uasa-t1,2,3','analisis-ppt','bank-soalan-uasa-ppt-pat-selaras','bank-soalan-upsa-bck','bank-soalan-upsa-bm','keputusan','penggubal-soalan-upsa-uasa','upsa-2026','unit-pbd','maklumat-pbd-panduan','pbd-ppt','pbd-uasa','pbd-uasa-individu','pbd-penjaminan-kualiti','pbd-pk-pemantauan','pbd-pk-pementoran','pbd-pk-pengesanan','pbd-pk-penyelarasan','pbd-ppt-tingkatan-1','pbd-ppt-tingkatan-1-individu','pbd-ppt-tingkatan-2','pbd-ppt-tingkatan-2-individu','pbd-ppt-tingkatan-3','pbd-ppt-tingkatan-3-individu','pbd-ppt-tingkatan-4','pbd-ppt-tingkatan-4-individu','pbd-ppt-tingkatan-5','pbd-ppt-tingkatan-5-individu','pusat-sumber','pra-sekolah','kecemerlangan-program-akademik','pilihan-mata-pelajaran'], true) ? 'active' : '' ?>"
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Kurikulum
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pentaksiran-peperiksaan' ? 'active' : '' ?>" href="pentaksiran-peperiksaan">Pentaksiran Dan Peperiksaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pusat-sumber' ? 'active' : '' ?>" href="pusat-sumber">Pusat Sumber Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pra-sekolah' ? 'active' : '' ?>" href="pra-sekolah">Pra Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'kecemerlangan-program-akademik' ? 'active' : '' ?>" href="kecemerlangan-program-akademik">Program Kecemerlangan Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pilihan-mata-pelajaran' ? 'active' : '' ?>" href="pilihan-mata-pelajaran">Pilihan Mata Pelajaran</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['enrolmen-murid','bil-kelas-gambar','unit-bimbingan-kaunseling','peraturan-sekolah','pemimpin-murid'], true) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Hal Ehwal Murid 
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'enrolmen-murid' ? 'active' : '' ?>" href="enrolmen-murid">Enrolmen Murid</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'bil-kelas-gambar' ? 'active' : '' ?>" href="bil-kelas-gambar">Bilangan Kelas-Gambar</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'unit-bimbingan-kaunseling' ? 'active' : '' ?>" href="unit-bimbingan-kaunseling">Unit Bimbingan Dan Kaunseling</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'peraturan-sekolah' ? 'active' : '' ?>" href="peraturan-sekolah">Peraturan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pemimpin-murid' ? 'active' : '' ?>" href="pemimpin-murid">Pemimpin Murid</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'kokurikulum' ? 'active' : '' ?>" 
                           href="https://sites.google.com/moe-dl.edu.my/unit-kokurikulum-smk-s3?usp=sharing" 
                           target="_blank">
                            Kokurikulum
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['jawatankuasa-pibg'], true) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            PIBG
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'jawatankuasa-pibg' ? 'active' : '' ?>" href="jawatankuasa-pibg">Jawatankuasa PIBG</a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="_blank" href="images/NO AKAUN PIBG SMK S3.png">Nombor Akaun PIBG</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Media Sekolah
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="https://www.tiktok.com/@smkseremban3?lang=en" target="_blank" rel="noopener noreferrer">
                                    TikTok
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.facebook.com/share/1ECEvkNEJQ/?mibextid=wwXIfr" target="_blank" rel="noopener noreferrer">
                                    Facebook
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="https://www.youtube.com/@TVPSSSMKSEREMBAN3" target="_blank" rel="noopener noreferrer">
                                    YouTube
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php if (!empty($smks3_is_editor) && smks3_is_superadmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'pengurusan-akses' ? 'active' : '' ?>" href="pengurusan-akses">
                            Akses
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            </div>
        </div>
    </nav>
<?php if ($breadcrumb_items !== null) : ?>
    <div class="page-breadcrumb-wrap">
        <div class="container py-2 py-md-2">
            <nav aria-label="Lokasi halaman">
                <ol class="breadcrumb mb-0">
                    <?php foreach ($breadcrumb_items as $cr) :
                        $label = $cr['label'] ?? '';
                        $isCurrent = !empty($cr['current']);
                        $href = $cr['href'] ?? null;
                        $hasLink = !$isCurrent && $href !== null && $href !== '';
                        ?>
                    <li class="breadcrumb-item<?= $isCurrent ? ' active' : '' ?>"<?= $isCurrent ? ' aria-current="page"' : '' ?>>
                        <?php if ($hasLink) : ?>
                            <a href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($label) ?></a>
                        <?php else : ?>
                            <?= htmlspecialchars($label) ?>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </div>
    </div>
<?php endif; ?>
<?php if ($current_page !== 'index' && empty($suppress_page_header)) : ?>
    <div class="page-header">
        <div class="container">
            <h1 class="page-header__title"><?= htmlspecialchars($page_title) ?></h1>
            <?php if (!empty($page_lead)) : ?>
            <p class="page-header__lead"><?= htmlspecialchars($page_lead) ?></p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php if ($current_page !== 'index') : ?>
<main class="site-page-content">
<?php endif; ?>

