<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use PDO;

/**
 * Portal page controllers (generated during MVC migration).
 */
final class PageController extends Controller
{

    public function page_about(): void
    {
        $page_title = 'Perihal Kami';
        $settings = getSettings();
        $this->render('pages/about', get_defined_vars());
    }

    public function page_analisis_pat_t4_uasa_t1_2_3(): void
    {
        $page_title = 'Analisis PAT T4 & UASA T1,2,3';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('analisis-pat-t4-uasa-t1,2,3'), EXTR_OVERWRITE);
        $this->render('pages/analisis-pat-t4-uasa-t1,2,3', get_defined_vars());
    }

    public function page_analisis_ppt(): void
    {
        $page_title = 'Analisis PPT';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('analisis-ppt'), EXTR_OVERWRITE);
        $this->render('pages/analisis-ppt', get_defined_vars());
    }

    public function page_bank_soalan_uasa_ppt_pat_selaras(): void
    {
        $page_title = 'Bank Soalan UASA PPT, PAT';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('bank-soalan-uasa-ppt-pat-selaras'), EXTR_OVERWRITE);
        $this->render('pages/bank-soalan-uasa-ppt-pat-selaras', get_defined_vars());
    }

    public function page_bank_soalan_upsa_bck(): void
    {
        $this->renderKurikulumPage('bank-soalan-upsa-bck', 'UPSA - BCK');
    }

    public function page_bank_soalan_upsa_bm(): void
    {
        $this->renderKurikulumPage('bank-soalan-upsa-bm', 'UPSA - BM');
    }

    public function page_bil_kelas_gambar(): void
    {
        $page_title = 'Bilangan Kelas Gambar';
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        $page_meta = smks3_get_page_meta('bil-kelas-gambar');
        $this->render('pages/bil-kelas-gambar', get_defined_vars());
    }

    public function page_buletin_sekolah(): void
    {
        $page_title = 'Buletin Sekolah';
        $settings = getSettings();
        
        $buletin_list = [
            [
                'title' => 'Buletin SMK Seremban 3 2024',
                'year' => '2024',
                'cover' => 'uploads/buletin/buletin2024.jpg',
                'file' => 'uploads/buletin/buletin2024.pdf'
            ],
            [
                'title' => 'Buletin SMK Seremban 3 2023',
                'year' => '2023',
                'cover' => 'uploads/buletin/buletin2023.jpg',
                'file' => 'uploads/buletin/buletin2023.pdf'
            ],
            [
                'title' => 'Buletin SMK Seremban 3 2022',
                'year' => '2022',
                'cover' => 'uploads/buletin/buletin2022.jpg',
                'file' => 'uploads/buletin/buletin2022.pdf'
            ],
        ];
        $this->render('pages/buletin-sekolah', get_defined_vars());
    }

    public function page_contact(): void
    {
        $page_title = 'Hubungi Kami';
        $page_lead = 'Hantar mesej atau hubungi sekolah secara langsung.';
        $settings = getSettings();
        $message = '';
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $subject = trim($_POST['subject'] ?? '');
            $msg = trim($_POST['message'] ?? '');
            if (!$name || !$email || !$msg) {
                $error = 'Nama, e-mel dan mesej wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Alamat e-mel tidak sah.';
            } else {
                $message = 'Mesej anda telah dihantar. Terima kasih!';
                $_POST = [];
            }
        }
        $this->render('pages/contact', get_defined_vars());
    }

    public function page_courses(): void
    {
        $page_title = 'Jurusan';
        $settings = getSettings();
        $courses = [
            ['id' => 1, 'name' => 'Teknik Komputer dan Rangkaian', 'slug' => 'tkj', 'description' => 'Jurusan yang mempelajari rangkaian komputer, pelayan, dan sistem maklumat.', 'duration' => '4 Tahun', 'icon' => 'bi-laptop'],
            ['id' => 2, 'name' => 'Kejuruteraan Perisian', 'slug' => 'rpl', 'description' => 'Jurusan pengaturcaraan dan pembangunan aplikasi serta perisian.', 'duration' => '4 Tahun', 'icon' => 'bi-code-slash'],
            ['id' => 3, 'name' => 'Multimedia', 'slug' => 'multimedia', 'description' => 'Jurusan reka bentuk grafik, video, animasi, dan kandungan digital.', 'duration' => '4 Tahun', 'icon' => 'bi-camera-video'],
        ];
        $this->render('pages/courses', get_defined_vars());
    }

    public function page_cuti_perayaan(): void
    {
        $page_title = 'Cuti Perayaan 2026';
        
        $settings = getSettings();
        $pdo = getConnection();
        
        smks3_ensure_gallery_sort_order('cuti_perayaan', $pdo);
        $stmt = $pdo->query('
            SELECT *
            FROM cuti_perayaan
            ORDER BY sort_order ASC, id ASC
        ');
        $cuti_pdfs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $is_editor = smks3_can_edit_page();
        
        $cutiTableHtml = smks3_get_html_content('cuti_perayaan_table', smks3_default_cuti_table_html());
        $cutiNotesHtml = smks3_get_html_content('cuti_perayaan_notes', smks3_default_cuti_notes_html());
        $cutiKumpulan = smks3_get_cuti_kumpulan();
        $cutiIntroHtml = smks3_format_cuti_kumpulan_html($cutiKumpulan);
        $this->render('pages/cuti-perayaan', get_defined_vars());
    }

    public function page_enrolmen_murid(): void
    {
        $page_title = 'Pelan Kedudukan Kelas';
        $pdo = getConnection();
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();
        $page_meta = smks3_get_page_meta('enrolmen-murid');
        $enrolmen = smks3_get_enrolmen_content();
        $this->render('pages/enrolmen-murid', get_defined_vars());
    }

    public function page_guru_apk(): void
    {
        $page_title = 'Barisan Guru Dan AKP';
        $pdo = getConnection();
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();
        $placeholderImage = '/smks3/images/placeholder.png';
        $this->render('pages/guru-apk', get_defined_vars());
    }

    public function page_jawatankuasa_pibg(): void
    {
        $page_title = 'Jawatankuasa PIBG';
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();
        $pibg = smks3_get_pibg_content();
        $this->render('pages/jawatankuasa-pibg', get_defined_vars());
    }

    public function page_kalendar_akademik(): void
    {
        $page_title = 'Kalendar Akademik 2026';
        
        $settings = getSettings();
        $pdo = getConnection();
        
        $stmt = $pdo->prepare('SELECT * FROM pages WHERE page_key = ?');
        $stmt->execute(['kalendar_akademik']);
        $page = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        
        $stmt = $pdo->query('
            SELECT *
            FROM academic_calendar
            ORDER BY sort_order ASC, start_date ASC
        ');
        $calendar_pdfs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $is_editor = smks3_can_edit_page();
        $pageTitle = trim((string) ($page['title'] ?? ''));
        if ($pageTitle === '') {
            $pageTitle = 'Kalendar Akademik 2026';
        }
        $pageContentHtml = (string) ($page['content'] ?? '');
        $this->render('pages/kalendar-akademik', get_defined_vars());
    }

    public function page_kecemerlangan_program_akademik(): void
    {
        $page_title = 'Program Kecemerlangan Akademik';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('kecemerlangan-program-akademik'), EXTR_OVERWRITE);
        $this->render('pages/kecemerlangan-program-akademik', get_defined_vars());
    }

    public function page_kelab_persatuan(): void
    {
        $page_title = 'Kelab Dan Persatuan';
        $settings = getSettings();
        $this->render('pages/kelab-persatuan', get_defined_vars());
    }

    public function page_keputusan(): void
    {
        $page_title = 'Keputusan 2018-2024';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('keputusan'), EXTR_OVERWRITE);
        $this->render('pages/keputusan', get_defined_vars());
    }

    public function page_lencana_lagu_sekolah(): void
    {
        $page_title = 'Lencana & Lagu Sekolah';

        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();

        $defaults = [
            'moto' => 'Berilmu, Berdisiplin, Berbakti',
            'lirik' => "SMK Seremban 3\nPuncak ilmu abadi\nTersergam pesona\nMenuju cita\n\nSMK Seremban 3\nGedung wawasan kita\nBerjanji Bersatu\nDemi negara\n\nKami kan berusaha\nHingga berjaya\nTanpa rasa jemu\nKami berusaha\n\nSMK Seremban 3\nPuncak ilmu abadi\nTersergam pesona\nMenuju cita",
            'lirik_penggubah' => 'Samsudin Ahmad',
            'lirik_penulis' => 'Jamaluddin Ahmad',
            'image' => 'hero-logo.png',
        ];

        $stmt = $pdo->query('SELECT * FROM lencana_lagu_sekolah WHERE id = 1');
        $data = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        foreach ($defaults as $key => $fallback) {
            $val = trim((string) ($data[$key] ?? ''), " \t\n\r\0\x0B\"'");
            $data[$key] = $val !== '' ? $val : $fallback;
        }

        // Shared site logo (navbar / home / favicon / lencana).
        $lencana_image_src = smks3_site_logo_src();
        $data['image'] = basename($lencana_image_src);

        $lencana_items = $pdo->query('SELECT * FROM lencana_item ORDER BY sort_order ASC, id ASC')->fetchAll(PDO::FETCH_ASSOC);
        $this->render('pages/lencana-lagu-sekolah', get_defined_vars());
    }

    public function page_maklumat_pbd_panduan(): void
    {
        $page_title = 'Maklumat PBD Dan Panduan';
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        smks3_ensure_pbd_panduan_table($pdo);
        smks3_ensure_gallery_sort_order('pbd_panduan', $pdo);
        $db_files = $pdo->query(
            'SELECT id, file, sort_order FROM pbd_panduan ORDER BY sort_order ASC, id ASC'
        )->fetchAll(PDO::FETCH_ASSOC) ?: [];
        $this->render('pages/maklumat-pbd-panduan', get_defined_vars());
    }

    public function page_misi_visi_sekolah(): void
    {
        $page_title = 'FPK, Visi, Misi, Motto Sekolah';
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        smks3_ensure_fpk_misi_visi_schema($pdo);
        $fpk_falsafah = smks3_get_fpk_falsafah();
        $stmt = $pdo->query("SELECT * FROM fpk_misi_visi ORDER BY id ASC");
        $fpk_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->render('pages/misi-visi-sekolah', get_defined_vars());
    }

    public function page_news_details(): void
    {
        $page_title = 'Butiran Berita';
        $suppress_page_header = true;
        $pdo = getConnection();
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();

        $slugParam = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
        $legacyId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $news_item = null;
        if ($slugParam !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slugParam)) {
            $stmt = $pdo->prepare('SELECT * FROM news WHERE slug = ? LIMIT 1');
            $stmt->execute([$slugParam]);
            $news_item = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } elseif ($legacyId > 0) {
            $stmt = $pdo->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
            $stmt->execute([$legacyId]);
            $news_item = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            if ($news_item && !empty($news_item['slug'])) {
                header('Location: ' . smks3_news_article_url($news_item), true, 301);
                exit;
            }
        }

        if (!$news_item) {
            http_response_code(404);
            $page_title = 'Berita tidak dijumpai';
            $meta_robots = 'noindex, follow';
        $pdfPath = null;
        $pdfPaths = [];
        $this->render('pages/news-details', get_defined_vars());
            return;
        }

        $page_title = (string) ($news_item['title'] ?? 'Butiran Berita');
        $meta_title = $page_title . ' | SMK Seremban 3 (SMKS3)';
        $excerpt = trim((string) ($news_item['excerpt'] ?? ''));
        if ($excerpt === '') {
            $excerpt = (string) ($news_item['content'] ?? '');
        }
        if ($excerpt !== '') {
            $meta_description = smks3_seo_plain_text($excerpt, 160);
        }
        $og_type = 'article';
        $rawImage = smks3_news_primary_image($news_item['image'] ?? $news_item['image_url'] ?? null);
        if ($rawImage !== '') {
            $og_image = smks3_news_image_src($rawImage);
        }
        $pdfPaths = smks3_news_pdf_srcs($news_item['pdf_file'] ?? null);
        $pdfPath = $pdfPaths[0] ?? null;

        $this->render('pages/news-details', get_defined_vars());
    }

    public function page_news(): void
    {
        $page_title = 'Berita';
        /* =========================
           1. GET INPUT (WAJIB AWAL)
        ========================= */
        $slugParam   = isset($_GET['slug']) ? trim($_GET['slug']) : '';
        $legacyId    = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $yearFilter  = isset($_GET['year']) ? $_GET['year'] : '';
        $listPage    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        
        $news_per_page = 3;
        $news_item = null;
        
        /* =========================
           2. FETCH SINGLE NEWS → redirect to news-details (slug URL)
        ========================= */
        if ($slugParam !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slugParam)) {
            $news_item = smks3_fetch_news_by_slug($slugParam);
            if ($news_item) {
                header('Location: ' . smks3_news_article_url($news_item), true, 301);
                exit;
            }
        } elseif ($legacyId > 0) {
            $news_item = smks3_fetch_news_by_id($legacyId);
            if ($news_item) {
                header('Location: ' . smks3_news_article_url($news_item), true, 301);
                exit;
            }
        }
        $news_item = null;
        
        /* =========================
           3. LIST VIEW
        ========================= */
        $news_page_items = [];
        $pagination = [
            'page' => 1,
            'per_page' => $news_per_page,
            'total' => 0,
            'total_pages' => 1
        ];
        
        if (!$news_item) {
        
            $paginated = smks3_fetch_published_news_paginated($listPage, $news_per_page, $yearFilter);
        
            if ($paginated && $paginated['total'] > 0) {
        
                $news_page_items = $paginated['items'];
                $pagination = $paginated;
        
            } else {
        
                $news_static = [
                    [
                        'id' => 1,
                        'title' => 'Kemasukan Pelajar Baru 2025',
                        'slug' => 'ppdb-2025',
                        'excerpt' => 'Pendaftaran kemasukan tahun 2025/2026 dibuka.',
                        'content' => '<p>Maklumat pendaftaran pelajar baru...</p>',
                        'published_at' => '2025-02-10 09:00:00'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Aktiviti Latihan Industri',
                        'slug' => 'pkl-2025',
                        'excerpt' => 'Pelajar menjalani latihan industri.',
                        'content' => '<p>Program PKL dijalankan...</p>',
                        'published_at' => '2025-03-05 10:30:00'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Program Kokurikulum',
                        'slug' => 'kokurikulum-2025',
                        'excerpt' => 'Aktiviti kokurikulum sekolah.',
                        'content' => '<p>Pelbagai aktiviti dijalankan...</p>',
                        'published_at' => '2025-04-18 14:00:00'
                    ],
                ];
        
                $news_all = smks3_sort_news_by_published_desc($news_static);
        
                if ($yearFilter !== '') {
                    $news_all = array_filter($news_all, function ($n) use ($yearFilter) {
                        return substr($n['published_at'], 0, 4) == $yearFilter;
                    });
                }
        
                $total = count($news_all);
                $totalPages = max(1, ceil($total / $news_per_page));
                $listPage = min($listPage, $totalPages);
        
                $offset = ($listPage - 1) * $news_per_page;
                $news_page_items = array_slice($news_all, $offset, $news_per_page);
        
                $pagination = [
                    'page' => $listPage,
                    'per_page' => $news_per_page,
                    'total' => $total,
                    'total_pages' => $totalPages
                ];
            }
        }
        
        if ($news_item) {
            $suppress_page_header = true;
            $page_title = $news_item['title'];
        } else {
            $page_lead = 'Senarai berita dan pengumuman terbaru dari sekolah.';
        }
        
        $is_editor = smks3_can_edit_page();
        $this->render('pages/news', get_defined_vars());
    }

    public function page_pelan_sekolah(): void
    {
        $page_title = 'Pelan Sekolah';
        
        $settings = getSettings();
        
        $pdo = getConnection();
        
        /**
         * FETCH PELAN SEKOLAH
         */
        $stmt = $pdo->query("
            SELECT * 
            FROM pelan_sekolah
            WHERE id = 1
            LIMIT 1
        ");
        
        $pelan = $stmt->fetch(PDO::FETCH_ASSOC);

        /**
         * IMAGE PATHS (legacy single filename or JSON list)
         */
        $images = [];
        foreach (smks3_news_parse_images($pelan['image'] ?? null) as $name) {
            $path = 'images/pelan-sekolah/' . $name;
            if (is_file(BASE_PATH . '/' . $path)) {
                $images[] = $path;
            }
        }
        $hasPelanImages = $images !== [];
        if (!$hasPelanImages) {
            $images = ['images/no-image.png'];
        }
        $image = $images[0];
        // Only real uploads go into the edit panel (not the placeholder).
        $editImages = $hasPelanImages ? $images : [];

        $is_editor = smks3_can_edit_page();
        $this->render('pages/pelan-sekolah', get_defined_vars());
    }

    public function page_pemimpin_murid(): void
    {
        $page_title = 'Pemimpin Murid';
        
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        $page_meta = smks3_get_page_meta('pemimpin-murid');
        extract(smks3_kurikulum_page_vars('pemimpin-murid'), EXTR_OVERWRITE);
        
        smks3_ensure_gallery_sort_order('pemimpin_murid', $pdo);
        $stmt = $pdo->query('SELECT * FROM pemimpin_murid ORDER BY sort_order ASC, id ASC');
        $db_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $static_image = is_file(BASE_PATH . '/images/barisanmpp.JPG') ? 'images/barisanmpp.JPG' : '';
        $this->render('pages/pemimpin-murid', get_defined_vars());
    }

    public function page_penggubal_soalan_upsa_uasa(): void
    {
        $page_title = 'Penggubal Soalan UPSA & UASA';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('penggubal-soalan-upsa-uasa'), EXTR_OVERWRITE);
        $this->render('pages/penggubal-soalan-upsa-uasa', get_defined_vars());
    }

    public function page_pengurusan_tertinggi(): void
    {
        $page_title = 'Pengurusan Tertinggi Sekolah';
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();
        $pdo = getConnection();

        $stmt = $pdo->query('SELECT * FROM pengurusan ORDER BY susunan ASC');
        $pengurusan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groups = [
            'pengetua' => [],
            'pk' => [],
            'gkmp' => [],
            'kaunselor' => [],
        ];
        foreach ($pengurusan as $p) {
            if (isset($groups[$p['kategori']])) {
                $groups[$p['kategori']][] = $p;
            }
        }

        $placeholderImage = '/smks3/images/placeholder.png';
        $this->render('pages/pengurusan-tertinggi', get_defined_vars());
    }

    public function page_pentaksiran_peperiksaan(): void
    {
        $page_title = 'Pentaksiran & Peperiksaan';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('pentaksiran-peperiksaan'), EXTR_OVERWRITE);
        $this->render('pages/pentaksiran-peperiksaan', get_defined_vars());
    }

    public function page_peraturan_sekolah(): void
    {
        $page_title = 'Peraturan Sekolah';
        
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        $page_meta = smks3_get_page_meta('peraturan-sekolah');
        
        smks3_ensure_gallery_sort_order('peraturan_sekolah', $pdo);
        $stmt = $pdo->query('SELECT * FROM peraturan_sekolah ORDER BY sort_order ASC, id ASC');
        $db_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $static_images = [];
        foreach (['images/peraturansekolah1.jpeg', 'images/peraturansekolah2.jpeg'] as $path) {
            if (is_file(BASE_PATH . '/' . $path)) {
                $static_images[] = $path;
            }
        }
        $this->render('pages/peraturan-sekolah', get_defined_vars());
    }

    public function page_pilihan_mata_pelajaran(): void
    {
        $page_title = 'Pilihan Mata Pelajaran';

        $pdo = getConnection();

        smks3_ensure_gallery_sort_order('pilihan_mata_pelajaran', $pdo);
        $stmt = $pdo->query('
            SELECT *
            FROM pilihan_mata_pelajaran
            ORDER BY sort_order ASC, id ASC
        ');

        $pilihan_pdfs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $is_editor = smks3_can_edit_page();
        $this->render('pages/pilihan-mata-pelajaran', get_defined_vars());
    }

    public function page_pra_sekolah(): void
    {
        $page_title = 'Pra Sekolah';
        
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        $kurikulum_page_key = 'pra-sekolah';
        $kurikulum_meta = smks3_get_kurikulum_meta('pra-sekolah');
        
        /* GET DATA — legacy single filename or JSON list per column */
        $stmt = $pdo->query("SELECT * FROM pra_sekolah LIMIT 1");
        $data = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $cartaImages = [];
        foreach (smks3_news_parse_images($data['gambar_carta'] ?? null) as $name) {
            $path = 'uploads/pra_sekolah/' . $name;
            if (is_file(BASE_PATH . '/' . $path)) {
                $cartaImages[] = $path;
            }
        }
        $galeriImages = [];
        foreach (smks3_news_parse_images($data['gambar_galeri'] ?? null) as $name) {
            $path = 'uploads/pra_sekolah/' . $name;
            if (is_file(BASE_PATH . '/' . $path)) {
                $galeriImages[] = $path;
            }
        }
        // Keep legacy single vars for any older references.
        $carta = $cartaImages[0] ?? '';
        $galeri = $galeriImages[0] ?? '';
        $this->render('pages/pra-sekolah', get_defined_vars());
    }

    public function page_profil_sekolah(): void
    {
        $page_title = 'Profil Sekolah';
        $page_lead = 'Maklumat am dan ringkas tentang sekolah kami.';
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        $profil_data = smks3_get_profil_items($pdo);
        $this->render('pages/profil-sekolah', get_defined_vars());
    }

    public function page_pusat_sumber(): void
    {
        $page_title = 'Pusat Sumber';
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars('pusat-sumber'), EXTR_OVERWRITE);
        $this->render('pages/pusat-sumber', get_defined_vars());
    }

    public function page_sejarah_sekolah(): void
    {
        $page_title = 'Sejarah Sekolah';
        $settings = getSettings();
        $pdo = getConnection();
        $is_editor = smks3_can_edit_page();
        smks3_ensure_table_auto_id('sejarah_sekolah', $pdo);
        $stmt = $pdo->query("SELECT * FROM sejarah_sekolah ORDER BY id DESC");
        $sejarahList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->render('pages/sejarah-sekolah', get_defined_vars());
    }

    public function page_senarai_pengetua(): void
    {
        $page_title = 'Senarai Pengetua';
        // guna connection sedia ada
        
        $settings = getSettings();
        $pdo = getConnection(); // <-- ini missing
        // Ambil semua pengetua dari database, ASC supaya dari lama ke baru
        $stmt = $pdo->query("SELECT * FROM pengetua ORDER BY start_year ASC");
        $pengetua_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Reverse array supaya yang baru berada di bawah
        $pengetua_list = array_reverse($pengetua_list);
        $is_editor = smks3_can_edit_page();
        $this->render('pages/senarai-pengetua', get_defined_vars());
    }

    public function page_staff(): void
    {
        $page_title = 'Guru & Kakitangan';
        $staff_list = [
            ['name' => 'Dr. Ahmad Wijaya, S.Pd., M.M.', 'role' => 'Pengetua', 'department' => 'Pengurusan', 'bio' => 'Memimpin SMK S3 dengan visi pendidikan vokasional yang unggul.', 'photo_url' => null],
            ['name' => 'Siti Nurhaliza, S.Kom.', 'role' => 'Guru Produktif TKJ', 'department' => 'Teknik Komputer dan Rangkaian', 'bio' => 'Pengajar rangkaian dan sistem komputer.', 'photo_url' => null],
            ['name' => 'Budi Santoso, S.T.', 'role' => 'Guru Produktif RPL', 'department' => 'Kejuruteraan Perisian', 'bio' => 'Pengajar pengaturcaraan dan pembangunan aplikasi.', 'photo_url' => null],
        ];
        $this->render('pages/staff', get_defined_vars());
    }

    public function page_unit_badan_beruniform(): void
    {
        $page_title = 'Unit Badan Beruniform';
        $settings = getSettings();
        $this->render('pages/unit-badan-beruniform', get_defined_vars());
    }

    public function page_unit_bimbingan_kaunseling(): void
    {
        $page_title = 'Unit Bimbingan & Kaunseling';
        $settings = getSettings();
        $is_editor = smks3_can_edit_page();
        $ubk = smks3_get_ubk_content();
        $this->render('pages/unit-bimbingan-kaunseling', get_defined_vars());
    }

    public function page_unit_pbd(): void
    {
        $this->renderKurikulumPage('unit-pbd', 'Unit PBD');
    }

    public function page_pbd_ppt(): void
    {
        $this->renderKurikulumPage('pbd-ppt', 'PBD PPT');
    }

    public function page_pbd_uasa(): void
    {
        $this->renderKurikulumPage('pbd-uasa', 'PBD UASA');
    }

    public function page_pbd_penjaminan_kualiti(): void
    {
        $this->renderKurikulumPage('pbd-penjaminan-kualiti', 'Penjaminan Kualiti PBD');
    }

    public function page_pbd_pk_pemantauan(): void
    {
        $this->renderKurikulumPage('pbd-pk-pemantauan', 'Pemantauan');
    }

    public function page_pbd_pk_pementoran(): void
    {
        $this->renderKurikulumPage('pbd-pk-pementoran', 'Pementoran');
    }

    public function page_pbd_pk_pengesanan(): void
    {
        $this->renderKurikulumPage('pbd-pk-pengesanan', 'Pengesanan');
    }

    public function page_pbd_pk_penyelarasan(): void
    {
        $this->renderKurikulumPage('pbd-pk-penyelarasan', 'Penyelarasan');
    }

    public function page_pbd_uasa_individu(): void
    {
        $this->renderKurikulumPage('pbd-uasa-individu', 'PBD INDIVIDU');
    }

    public function page_pbd_ppt_tingkatan_1(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-1', 'PBD Tingkatan 1');
    }

    public function page_pbd_ppt_tingkatan_1_individu(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-1-individu', 'PBD INDIVIDU');
    }

    public function page_pbd_ppt_tingkatan_2(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-2', 'PBD Tingkatan 2');
    }

    public function page_pbd_ppt_tingkatan_2_individu(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-2-individu', 'PBD INDIVIDU');
    }

    public function page_pbd_ppt_tingkatan_3(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-3', 'PBD Tingkatan 3');
    }

    public function page_pbd_ppt_tingkatan_3_individu(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-3-individu', 'PBD INDIVIDU');
    }

    public function page_pbd_ppt_tingkatan_4(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-4', 'PBD Tingkatan 4');
    }

    public function page_pbd_ppt_tingkatan_4_individu(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-4-individu', 'PBD INDIVIDU');
    }

    public function page_pbd_ppt_tingkatan_5(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-5', 'PBD Tingkatan 5');
    }

    public function page_pbd_ppt_tingkatan_5_individu(): void
    {
        $this->renderKurikulumPage('pbd-ppt-tingkatan-5-individu', 'PBD INDIVIDU');
    }

    private function renderKurikulumPage(string $pageKey, string $pageTitle): void
    {
        $page_title = $pageTitle;
        $settings = getSettings();
        extract(smks3_kurikulum_page_vars($pageKey), EXTR_OVERWRITE);
        $this->render('pages/' . $pageKey, get_defined_vars());
    }

    public function home(): void
    {
        $page_title = 'Laman Utama';
        $meta_title = 'SMK Seremban 3 (SMKS3) | Portal Rasmi Sekolah Menengah Kebangsaan Seremban 3';
        
        $pdo = getConnection();
        
        $settings = getSettings();
        $meta_description = trim((string) ($settings['about_summary'] ?? ''));
        if ($meta_description === '') {
            $meta_description = 'Portal rasmi SMK Seremban 3 (SMKS3), Seremban, Negeri Sembilan. Berita sekolah, akademik, kokurikulum dan maklumat rasmi SMKS3.';
        }
        $home_content = smks3_get_home_content();
        $is_editor = smks3_can_edit_page();
        smks3_ensure_home_media_seed(BASE_PATH);
        
        $news_list = getLatestNewsByYear($pdo, 3);
        
        if (!is_array($news_list)) {
            $news_list = [];
        }
        
        $news_latest = $news_list;
        
        $home_quick_links = smks3_get_quick_links();
        $home_slideshow = smks3_get_slideshow(BASE_PATH);
        
        $body_class = 'page-home';
        $this->render('home/index', get_defined_vars());
    }

    public function sitemap(): void
    {
        smks3_seo_render_sitemap_xml();
    }

    public function robots(): void
    {
        smks3_seo_render_robots_txt();
    }

}
