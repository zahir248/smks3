<?php
if (!isset($page_title)) $page_title = 'Laman Utama';
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> | SMK S3</title>
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
            --school-bg-subtle: #f8fafc;
            --school-border: #e2e8f0;
            --bs-primary: #0B3C5D;
            --bs-primary-rgb: 11, 60, 93;
            --bs-body-font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body { font-family: var(--bs-body-font-family); color: #1e293b; line-height: 1.6; }
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
        }
        .navbar-brand:hover { color: var(--school-primary-dark) !important; }
        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }
        .nav-link:hover { color: var(--school-primary) !important; background: rgba(11, 60, 93, 0.06); }
        .nav-link.active { color: var(--school-primary) !important; background: rgba(11, 60, 93, 0.08); font-weight: 600; }
        .navbar-toggler { border-color: var(--school-border); }
        .navbar-toggler:focus { box-shadow: 0 0 0 2px rgba(11, 60, 93, 0.2); }

        .hero {
            background: linear-gradient(145deg, var(--school-primary-dark) 0%, var(--school-primary) 50%, var(--school-primary-light) 100%);
            padding: 4rem 0 4.5rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
        }
        .hero .container { position: relative; z-index: 1; }
        /* Force hero text visible on dark background */
        .hero, .hero h1, .hero .lead, .hero p, .hero .display-4 { color: #ffffff !important; }
        .hero h1 { font-weight: 700; letter-spacing: -0.03em; text-shadow: 0 2px 12px rgba(0,0,0,0.25); }
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
        section p, section li { color: #334155; }
        small.text-muted { color: #64748b !important; }
        section h2.text-center::after { content: ''; display: block; width: 48px; height: 4px; background: var(--school-accent); margin: 0.5rem auto 0; border-radius: 2px; }

        /* Hero: ensure all text stays white (override section rules) */
        .hero h1, .hero h2, .hero .lead, .hero p, .hero .display-4 { color: #ffffff !important; }
        .hero .text-muted { color: rgba(255,255,255,0.9) !important; }

        footer {
            background: linear-gradient(180deg, var(--school-primary-dark) 0%, var(--school-primary) 100%) !important;
            padding: 3rem 0 2rem;
            margin-top: 4rem;
            border-top: 4px solid var(--school-accent);
        }
        footer h5, footer h6 { font-weight: 600; color: #fff; }
        footer a { color: rgba(255,255,255,0.85); text-decoration: none; transition: color 0.2s; }
        footer a:hover { color: #fff; }
        footer .text-white-50 { color: rgba(255,255,255,0.7) !important; }
        footer hr { border-color: rgba(255,255,255,0.2); }

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
            .navbar-nav {
                max-width: 750px;
                margin-left: auto;
            }

            .navbar-nav .nav-item {
                flex: 0 0 20%;
                text-align: center;
            }

        }.navbar-nav .nav-link {
            padding: 0.6rem 0.5rem !important;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-mortarboard-fill me-2"></i>SMK SEREMBAN 3
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto flex-wrap">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="index.php">Laman Utama</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['profil-sekolah','misi-visi-sekolah','sejarah-sekolah']) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Pengurusan Dan Pentadbiran
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="profil-sekolah.php">Profil Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="misi-visi-sekolah.php">FPK, Visi, Misi, Motto Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="sejarah-sekolah.php">Sejarah Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="senarai-pengetua.php">Senarai Pengetua</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pelan-sekolah.php">Pelan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="lencana-lagu-sekolah.php">Lencana & Lagu Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pengurusan-tertinggi.php">Pengurusan Tertinggi Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="guru-apk.php">Barisan Guru Dan AKP</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="kalendar-akademik.php">Kalendar Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="cuti-perayaan.php">Cuti Perayaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="carta-organisasi.php">Carta Organisasi Induk</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['pentaksiran-peperiksaan','pusat-sumber','pra-sekolah','kecemerlangan-program-akademik','pilihan-mata-pelajaran']) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Kurikulum
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="pentaksiran-peperiksaan.php">Pentaksiran Dan Peperiksaan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pusat-sumber.php">Pusat Sumber Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pra-sekolah.php">Pra Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="kecemerlangan-program-akademik.php">Kecemerlangan Program Akademik</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pilihan-mata-pelajaran.php">Pilihan Mata Pelajaran</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['enrolmen-murid','bil-kelas-gambar','unit-bimbingan-kaunseling','peraturan-sekolah','pemimpin-murid']) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Hal Ehwal Murid 
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="enrolmen-murid.php">Enrolmen Murid</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="bil-kelas-gambar.php">Bilangan Kelas-Gambar</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="unit-bimbingan-kaunseling.php">Unit Bimbingan Dan Kaunseling</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="peraturan-sekolah.php">Peraturan Sekolah</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="pemimpin-murid.php">Pemimpin Murid</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['unit-badan-beruniform','kelab-persatuan','sukan-permainan']) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            Kokurikulum
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="unit-badan-beruniform.php">Unit Badan Beruniform</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="kelab-persatuan.php">Kelab Dan Persatuan</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="sukan-permainan.php">Sukan Dan Permainan</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['jawatankuasa-pibg','no-akaun-pibg']) ? 'active' : '' ?>" 
                        href="#" 
                        role="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                            PIBG
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="kawatankuasa-pibg.php">Jawatankuasa PIBG</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="no-akaun-pibg.php">Nombor Akaun PIBG</a>
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
