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
    <link rel="icon" type="image/png" href="images/favicon.ico">
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
            --nav-active-bg: #3a8cc4;
            --nav-active-bg-hover: #2f7aad;
            --school-bg-subtle: #f8fafc;
            --school-border: #e2e8f0;
            --bs-primary: #0B3C5D;
            --bs-primary-rgb: 11, 60, 93;
            --bs-body-font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body { font-family: var(--bs-body-font-family); color: #1e293b; line-height: 1.6; }
        body.site-nav-fixed {
            padding-top: var(--site-navbar-height, 4.75rem);
        }
        #site-navbar {
            transition: transform 0.32s ease;
        }
        #site-navbar.navbar-slide-hidden {
            transform: translateY(-100%);
        }
        /* Ensure all text uses theme colors */
        .text-primary, .card .bi, a.text-primary { color: var(--school-primary) !important; }
        a:not(.nav-link):not(.navbar-brand):not(footer a) { color: var(--school-primary); }
        a:not(.nav-link):not(.navbar-brand):not(footer a):hover { color: var(--school-primary-dark); }
        .btn-link { color: var(--school-primary) !important; font-weight: 600; text-decoration: none; }
        .btn-link:hover { color: var(--school-primary-dark) !important; text-decoration: underline; }
        .navbar {
            background: #fff !important;
            box-shadow: 0 1px 3px rgba(11, 60, 93, 0.08);
            padding: 0.75rem 0;
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
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }
        .navbar-brand:hover { color: var(--school-primary-dark) !important; }
        .navbar-brand img {
            height: 44px;
            width: auto;
            max-width: min(240px, 52vw);
            object-fit: contain;
            display: block;
        }
        .navbar-brand:hover img { opacity: 0.92; }
        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }
        .nav-link:hover { color: var(--school-primary) !important; background: rgba(11, 60, 93, 0.06); }
        .nav-link.active { color: var(--school-primary) !important; background: rgba(11, 60, 93, 0.08); font-weight: 600; }
        .navbar .nav-link.active,
        .navbar .nav-link.dropdown-toggle.active {
            color: #fff !important;
            background: var(--nav-active-bg) !important;
            font-weight: 600;
        }
        .navbar .nav-link.active:hover,
        .navbar .nav-link.dropdown-toggle.active:hover,
        .navbar .nav-link.active:focus,
        .navbar .nav-link.dropdown-toggle.active:focus {
            color: #fff !important;
            background: var(--nav-active-bg-hover) !important;
        }
        .navbar .dropdown-menu {
            border: 1px solid var(--school-border);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(11, 60, 93, 0.12);
            padding: 0.35rem 0;
            min-width: 12rem;
        }
        .navbar .dropdown-menu > li {
            margin: 0;
            padding: 0;
        }
        .navbar .dropdown-item {
            display: block;
            margin: 0;
            border-radius: 0;
            padding: 0.55rem 1rem;
            width: 100%;
            box-sizing: border-box;
            transition: color 0.15s, background 0.15s;
        }
        .navbar .dropdown-item:hover,
        .navbar .dropdown-item:focus {
            color: var(--school-primary) !important;
            background: rgba(11, 60, 93, 0.08) !important;
        }
        .navbar .dropdown-item.active {
            color: #fff !important;
            background: var(--nav-active-bg) !important;
            font-weight: 600;
        }
        .navbar .dropdown-item.active:hover,
        .navbar .dropdown-item.active:focus {
            color: #fff !important;
            background: var(--nav-active-bg-hover) !important;
        }
        .navbar-toggler { border-color: var(--school-border); }
        .navbar-toggler:focus { box-shadow: 0 0 0 2px rgba(11, 60, 93, 0.2); }

        .hero {
            background: linear-gradient(145deg, var(--school-primary-dark) 0%, var(--school-primary) 50%, var(--school-primary-light) 100%);
            padding: 4rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero-home-image {
            /* Dark neutral overlay (stronger on the left for text); no blue tint */
            background:
                linear-gradient(90deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.68) 38%, rgba(0, 0, 0, 0.58) 58%, rgba(0, 0, 0, 0.5) 100%),
                url("images/smk seremban 3 hero section.jpg") center / cover no-repeat;
            min-height: 22rem;
        }
        @media (min-width: 992px) {
            .hero-home-image {
                min-height: 26rem;
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
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.35);
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
            line-height: 1.2;
            text-shadow: 0 2px 24px rgba(0, 0, 0, 0.55), 0 1px 4px rgba(0, 0, 0, 0.7);
            font-size: clamp(1.2rem, 3.2vw + 0.85rem, 2.5rem);
        }
        .hero.hero-home-image .hero-school-name .hero-school-line {
            display: block;
            white-space: nowrap;
        }
        @media (max-width: 767.98px) {
            .hero.hero-home-image .hero-school-name .hero-school-line--name {
                white-space: normal;
            }
        }
        .hero .lead { opacity: 0.95; font-weight: 500; color: rgba(255,255,255,0.95) !important; }
        .hero .bi { color: rgba(255,255,255,0.85) !important; }
        .hero .btn-light { background: #ffffff; color: var(--school-primary) !important; font-weight: 600; border: none; padding: 0.65rem 1.5rem; border-radius: 8px; box-shadow: 0 4px 14px rgba(0,0,0,0.2); }
        .hero .btn-light:hover { background: #f1f5f9; color: var(--school-primary-dark) !important; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,0.25); }
        .hero .btn-outline-light { color: #ffffff !important; font-weight: 600; border: 2px solid rgba(255,255,255,0.9); padding: 0.65rem 1.5rem; border-radius: 8px; background: transparent; }
        .hero .btn-outline-light:hover { color: #ffffff !important; background: rgba(255,255,255,0.2); border-color: #ffffff; }

        .card, .card-hover {
            border: 1px solid var(--school-border);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.2s;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(11, 60, 93, 0.12);
            border-color: rgba(11, 60, 93, 0.2);
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
            transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .btn-primary:hover {
            background: var(--school-primary-light);
            border-color: var(--school-primary-light);
            color: #ffffff !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(11, 60, 93, 0.35);
        }
        .btn-outline-primary {
            border-color: var(--school-primary);
            color: var(--school-primary);
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-outline-primary:hover {
            background: rgba(11, 60, 93, 0.08);
            border-color: var(--school-primary);
            color: var(--school-primary-dark);
        }

        section { padding-top: 3.5rem; padding-bottom: 3.5rem; }
        section h1 { font-size: 1.9rem; margin-bottom: 0.5rem; }
        section h2 { font-size: 1.65rem; margin-bottom: 2rem; }
        section h1, section h2 { font-weight: 700; color: var(--school-primary-dark); letter-spacing: -0.02em; }
        section .lead { color: #475569 !important; }
        .text-muted { color: #52606d !important; }
        section .text-muted, .card-text.text-muted { color: #475569 !important; }
        section h5, section h6 { color: var(--school-primary-dark) !important; font-weight: 600; }
        .card .card-body h6.text-muted { color: #475569 !important; font-weight: 600; letter-spacing: 0.05em; }
        .card-title { color: var(--school-primary-dark) !important; font-weight: 600; }
        .card-body p, .card .card-text { color: #334155 !important; }
        .smks3-news-card-excerpt { text-align: justify; text-justify: inter-word; }
        .news-article-content p { margin-bottom: 1rem; text-align: justify; text-justify: inter-word; }
        .news-article-content p:last-child { margin-bottom: 0; }
        section p, section li { color: #334155; }
        small.text-muted { color: #64748b !important; }
        section h2.text-center::after { content: ''; display: block; width: 48px; height: 4px; background: var(--school-accent); margin: 0.5rem auto 0; border-radius: 2px; }

        /* Hero: ensure all text stays white (override section rules) */
        .hero h1, .hero h2, .hero .lead, .hero p, .hero .display-4 { color: #ffffff !important; }
        .hero .text-muted { color: rgba(255,255,255,0.9) !important; }

        footer {
            background: linear-gradient(180deg, var(--school-primary-dark) 0%, var(--school-primary) 100%) !important;
            padding: 3rem 0 2rem;
            margin-top: 0;
            border-top: 4px solid var(--school-accent);
        }
        footer h5, footer h6 { font-weight: 600; color: #fff; }
        footer a { color: rgba(255,255,255,0.85); text-decoration: none; transition: color 0.2s; }
        footer a:hover { color: #fff; }
        footer .text-white-50 { color: rgba(255,255,255,0.7) !important; }
        footer hr { border-color: rgba(255,255,255,0.2); }

        .footer-statistik__title {
            font-size: 1rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.75rem;
            letter-spacing: 0.02em;
        }
        .footer-statistik__total {
            text-align: center;
            font-size: clamp(1.75rem, 4vw, 2.35rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
            margin-bottom: 0.5rem;
            letter-spacing: 0.02em;
        }
        .footer-statistik__list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-statistik__row {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.38);
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.92);
        }
        .footer-statistik__row:last-child {
            border-bottom: none;
        }
        .footer-statistik__icon {
            flex-shrink: 0;
            font-size: 1.15rem;
            line-height: 1;
        }
        .footer-statistik__icon--today { color: #3b82f6; }
        .footer-statistik__icon--yesterday { color: #22d3ee; }
        .footer-statistik__icon--week { color: #c084fc; }
        .footer-statistik__icon--month { color: #f87171; }
        .footer-statistik__label { flex: 1 1 auto; min-width: 0; }
        .footer-statistik__value {
            font-weight: 600;
            color: #fff;
            margin-left: auto;
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
        /**/
        @media (min-width: 992px) {
            .navbar .dropdown:hover .dropdown-menu {
                display: block;
                margin-top: 0;
            }
        }
        @media (min-width: 992px) {
            .navbar .container {
                flex-wrap: nowrap;
            }
            .navbar-nav {
                flex-wrap: nowrap;
                margin-left: auto;
                align-items: center;
                gap: 0.15rem;
            }
            .navbar-nav .nav-item {
                flex: 0 0 auto;
            }
            .navbar-nav .nav-link {
                white-space: nowrap;
            }
        }
        .navbar-nav .nav-link {
            padding: 0.6rem 0.5rem !important;
        }

        /* Breadcrumbs: same background as page, light divider only (not a separate “strip”) */
        .page-breadcrumb-wrap {
            background: #383838;
            border-bottom: 1px solid #2c2c2c;
        }
        .page-breadcrumb-wrap .breadcrumb {
            font-size: 0.875rem;
            flex-wrap: wrap;
            --bs-breadcrumb-divider: ">";
            --bs-breadcrumb-divider-color: #9ca3af;
        }
        .page-breadcrumb-wrap .breadcrumb-item a,
        .page-breadcrumb-wrap .breadcrumb-item a:link,
        .page-breadcrumb-wrap .breadcrumb-item a:visited {
            color: #e8eaed !important;
            text-decoration: none;
            font-weight: 500;
        }
        .page-breadcrumb-wrap .breadcrumb-item a:hover {
            color: #fff !important;
            text-decoration: underline;
        }
        .page-breadcrumb-wrap .breadcrumb-item a:focus-visible {
            outline: 2px solid #fff;
            outline-offset: 2px;
            border-radius: 2px;
        }
        .page-breadcrumb-wrap .breadcrumb-item.active {
            color: #fff !important;
            font-weight: 600;
        }
        .page-breadcrumb-wrap .breadcrumb-item + .breadcrumb-item::before {
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <nav id="site-navbar" class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logosmks3.jpg" alt="SMK Seremban 3" width="200" height="44" decoding="async">
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
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['pentaksiran-peperiksaan','pusat-sumber','pra-sekolah','kecemerlangan-program-akademik','pilihan-mata-pelajaran'], true) ? 'active' : '' ?>" 
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
                                <a class="dropdown-item <?= $current_page === 'kecemerlangan-program-akademik' ? 'active' : '' ?>" href="kecemerlangan-program-akademik.php">Kecemerlangan Program Akademik</a>
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
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['unit-badan-beruniform','kelab-persatuan','sukan-permainan'], true) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Kokurikulum
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item <?= $current_page === 'unit-badan-beruniform' ? 'active' : '' ?>" href="unit-badan-beruniform.php">Unit Badan Beruniform</a>
                            </li>
                            <li>
                                <a class="dropdown-item <?= $current_page === 'kelab-persatuan' ? 'active' : '' ?>" href="kelab-persatuan.php">Kelab Dan Persatuan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" target="_blank" href="https://laporan-sukan-permainan-s3.my.canva.site/">Sukan Dan Permainan</a>
                            </li>
                        </ul>
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
                                <a class="dropdown-item" href="https://www.facebook.com/share/17rxCJHqUJ/" target="_blank" rel="noopener noreferrer">
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

