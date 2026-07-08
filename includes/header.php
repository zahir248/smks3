<?php
if (!isset($page_title)) $page_title = 'Laman Utama';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
require_once __DIR__ . '/breadcrumbs.php';

$breadcrumb_items = null;
if ($current_page !== 'index' && empty($suppress_site_breadcrumb)) {
    if (!empty($custom_breadcrumbs) && is_array($custom_breadcrumbs)) {
        $breadcrumb_items = $custom_breadcrumbs;
    } else {
        $breadcrumb_items = smks3_default_breadcrumb_items($current_page, $page_title);
    }
}

require_once __DIR__ . '/visit_stats.php';
smks3_record_visit();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | SMK S3</title>
    <link rel="icon" href="images/favicon-smks3.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
                url("images/smk seremban 3 hero section.jpg") center / cover no-repeat;
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
        @media (max-width: 1199.98px) {
            .hero-home-image {
                background-attachment: scroll, scroll;
            }
            .hero.hero-home-image {
                min-height: auto;
                padding-top: calc(var(--site-navbar-height, 4.75rem) + 2rem) !important;
                padding-bottom: 3rem !important;
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
    <nav id="site-navbar" class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <div class="navbar-pill">
            <a class="navbar-brand" href="index.php">
                <img src="images/hero-logo.png" alt="SMK Seremban 3" width="47" height="42" decoding="async">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto flex-lg-nowrap">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="index.php">Laman Utama</a>
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
                                <a class="dropdown-item <?= $current_page === 'profil-sekolah' ? 'active' : '' ?>" href="profil-sekolah.php">Profil Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'misi-visi-sekolah' ? 'active' : '' ?>" href="misi-visi-sekolah.php">FPK, Visi, Misi, Motto Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'sejarah-sekolah' ? 'active' : '' ?>" href="sejarah-sekolah.php">Sejarah Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'senarai-pengetua' ? 'active' : '' ?>" href="senarai-pengetua.php">Senarai Pengetua</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pelan-sekolah' ? 'active' : '' ?>" href="pelan-sekolah.php">Pelan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'lencana-lagu-sekolah' ? 'active' : '' ?>" href="lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pengurusan-tertinggi' ? 'active' : '' ?>" href="pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'guru-apk' ? 'active' : '' ?>" href="guru-apk.php">Barisan Guru Dan AKP</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'kalendar-akademik' ? 'active' : '' ?>" href="kalendar-akademik.php">Kalendar Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'cuti-perayaan' ? 'active' : '' ?>" href="cuti-perayaan.php">Cuti Perayaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="_blank" href="images/CARTA ORGANISASI INDUK.pdf">Carta Organisasi Induk</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['pentaksiran-peperiksaan','analisis-pat-t4-uasa-t1,2,3','analisis-ppt','bank-soalan-uasa-ppt-pat-selaras','keputusan','penggubal-soalan-upsa-uasa','pusat-sumber','pra-sekolah','kecemerlangan-program-akademik','pilihan-mata-pelajaran'], true) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Kurikulum
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pentaksiran-peperiksaan' ? 'active' : '' ?>" href="pentaksiran-peperiksaan.php">Pentaksiran Dan Peperiksaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pusat-sumber' ? 'active' : '' ?>" href="pusat-sumber.php">Pusat Sumber Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pra-sekolah' ? 'active' : '' ?>" href="pra-sekolah.php">Pra Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'kecemerlangan-program-akademik' ? 'active' : '' ?>" href="kecemerlangan-program-akademik.php">Program Kecemerlangan Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pilihan-mata-pelajaran' ? 'active' : '' ?>" href="pilihan-mata-pelajaran.php">Pilihan Mata Pelajaran</a>
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
                                <a class="dropdown-item <?= $current_page === 'enrolmen-murid' ? 'active' : '' ?>" href="enrolmen-murid.php">Enrolmen Murid</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'bil-kelas-gambar' ? 'active' : '' ?>" href="bil-kelas-gambar.php">Bilangan Kelas-Gambar</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'unit-bimbingan-kaunseling' ? 'active' : '' ?>" href="unit-bimbingan-kaunseling.php">Unit Bimbingan Dan Kaunseling</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'peraturan-sekolah' ? 'active' : '' ?>" href="peraturan-sekolah.php">Peraturan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'pemimpin-murid' ? 'active' : '' ?>" href="pemimpin-murid.php">Pemimpin Murid</a>
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
                                <a class="dropdown-item <?= $current_page === 'jawatankuasa-pibg' ? 'active' : '' ?>" href="jawatankuasa-pibg.php">Jawatankuasa PIBG</a>
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

