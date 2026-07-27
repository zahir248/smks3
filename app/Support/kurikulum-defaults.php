<?php

declare(strict_types=1);

$defaults = [
    'kecemerlangan-program-akademik' => [
        'meta' => [
            'intro' => 'Pelbagai program akademik dijalankan bagi meningkatkan prestasi dan kecemerlangan pelajar SMK Seremban 3.',
            'sections' => [
                'main' => [
                    'title' => 'Program Kecemerlangan Akademik',
                    'subtitle' => 'Antara program yang dilaksanakan untuk meningkatkan kecemerlangan akademik pelajar.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'ROAD TO A',
                'description' => 'Program bimbingan akademik bagi membantu pelajar mencapai keputusan cemerlang dalam peperiksaan.',
                'icon' => 'bi-graph-up',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'SMART SKOR',
                'description' => 'Program teknik menjawab dan strategi pembelajaran bagi meningkatkan pencapaian pelajar.',
                'icon' => 'bi-lightbulb',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'BOOSTER SPM 2026',
                'description' => 'Program intensif khas untuk calon SPM bagi meningkatkan prestasi menjelang peperiksaan sebenar.',
                'icon' => 'bi-rocket',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'KEM ILMU DINAMIK',
                'description' => 'Program kem akademik yang memberi pendedahan teknik pembelajaran dan motivasi kepada pelajar.',
                'icon' => 'bi-people',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'LMS',
                'description' => 'Platform pembelajaran digital yang digunakan untuk perkongsian bahan pembelajaran dan latihan.',
                'icon' => 'bi-laptop',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'JADUAL ANJAL',
                'description' => 'Pengurusan jadual pembelajaran yang fleksibel bagi memberi fokus kepada mata pelajaran penting.',
                'icon' => 'bi-calendar-week',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
        ],
    ],

    'pusat-sumber' => [
        'meta' => [
            'intro' => 'Pusat sumber menyediakan bahan bacaan, maklumat digital dan aktiviti literasi untuk meningkatkan budaya membaca dalam kalangan pelajar.',
            'sections' => [
                'nilam' => [
                    'title' => 'Program NILAM',
                    'subtitle' => 'Nadi Ilmu Amalan Membaca (NILAM) merupakan program galakan membaca yang dilaksanakan bagi memupuk budaya membaca dalam kalangan pelajar.',
                ],
                'buletin' => [
                    'title' => 'Buletin Sekolah',
                    'subtitle' => 'Buletin sekolah memaparkan aktiviti, pencapaian dan perkembangan terkini warga SMK Seremban 3.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'nilam',
                'title' => 'Advanced Integrated NILAM System',
                'description' => 'Sistem bersepadu untuk merekod bahan NILAM murid',
                'icon' => 'bi-book',
                'href' => 'https://ains.moe.gov.my/login?returnUrl=/',
                'is_external' => 1,
                'btn_label' => 'Lihat Maklumat',
                'links' => [],
            ],
            [
                'section_key' => 'buletin',
                'title' => 'Buletin Sekolah',
                'description' => 'Koleksi buletin sekolah yang memaparkan aktiviti, program dan kejayaan warga sekolah.',
                'icon' => 'bi-newspaper',
                'href' => 'news',
                'is_external' => 0,
                'btn_label' => 'Lihat Buletin',
                'links' => [],
            ],
        ],
    ],

    'pentaksiran-peperiksaan' => [
        'meta' => [
            'intro' => 'Maklumat berkaitan pentaksiran pelajar termasuk peperiksaan dalaman, peperiksaan awam dan Pentaksiran Bilik Darjah (PBD).',
            'sections' => [
                'dalaman' => [
                    'title' => 'Unit Peperiksaan Dalaman',
                    'subtitle' => 'Maklumat analisis peperiksaan dan bahan rujukan bagi kegunaan guru dan pelajar.',
                ],
                'spm' => [
                    'title' => 'Peperiksaan Umum / SPM',
                    'subtitle' => 'Peperiksaan awam yang dikendalikan oleh Kementerian Pendidikan Malaysia bagi pelajar tingkatan lima.',
                ],
                'pbd' => [
                    'title' => 'Pentaksiran Bilik Darjah (PBD)',
                    'subtitle' => 'Pentaksiran berterusan yang dijalankan oleh guru semasa proses pengajaran dan pembelajaran.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'dalaman',
                'title' => 'Unit Peperiksaan Dalaman',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'ANALISIS PAT T4 & UASA T1,2,3', 'href' => 'analisis-pat-t4-uasa-t1,2,3'],
                    ['title' => 'ANALISIS PPT', 'href' => 'analisis-ppt'],
                    ['title' => 'BANK SOALAN UASA, PPT, PAT SELARAS', 'href' => 'bank-soalan-uasa-ppt-pat-selaras'],
                    ['title' => 'KEPUTUSAN 2018 - 2024', 'href' => 'keputusan'],
                    ['title' => 'PENGGUBAL SOALAN UPSA & UASA', 'href' => 'penggubal-soalan-upsa-uasa'],
                ],
            ],
            [
                'section_key' => 'spm',
                'title' => 'Sijil Pelajaran Malaysia (SPM)',
                'description' => "SPM merupakan peperiksaan awam utama bagi pelajar Tingkatan 5 di Malaysia. Keputusan peperiksaan ini menjadi salah satu penentu kelayakan pelajar untuk melanjutkan pelajaran ke peringkat yang lebih tinggi seperti matrikulasi, diploma atau kolej vokasional.\nDikendalikan oleh Lembaga Peperiksaan Malaysia\nMelibatkan pelbagai mata pelajaran teras dan elektif\nMenjadi syarat kemasukan ke institusi pengajian tinggi",
                'icon' => '',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [],
            ],
            [
                'section_key' => 'pbd',
                'title' => 'UNIT PBD',
                'description' => 'Maklumat berkaitan pengurusan Pentaksiran Bilik Darjah termasuk garis panduan dan dokumen berkaitan.',
                'icon' => 'bi-folder-check',
                'href' => 'unit-pbd',
                'is_external' => 0,
                'btn_label' => 'Lihat Maklumat',
                'links' => [],
            ],
            [
                'section_key' => 'pbd',
                'title' => 'SEMAKAN PBD SMK SEREMBAN 3',
                'description' => 'Semakan tahap penguasaan Pentaksiran Bilik Darjah bagi pelajar SMK Seremban 3.',
                'icon' => 'bi-search',
                'href' => 'https://lookerstudio.google.com/u/0/reporting/a93b2cf1-f955-443e-829e-cf4a9a3d37c1/page/OXERC',
                'is_external' => 1,
                'btn_label' => 'Semak Sekarang',
                'links' => [],
            ],
            [
                'section_key' => 'pbd',
                'title' => 'MAKLUMAT PBD DAN PANDUAN',
                'description' => 'Rujukan gambar maklumat PBD dan panduan pelaksanaan.',
                'icon' => 'bi-images',
                'href' => 'maklumat-pbd-panduan',
                'is_external' => 0,
                'btn_label' => 'Lihat Maklumat',
                'links' => [],
            ],
        ],
    ],

    'unit-pbd' => [
        'meta' => [
            'intro' => 'Maklumat dan dokumen berkaitan Pentaksiran Bilik Darjah (PBD) termasuk laporan PPT, UASA, penjaminan kualiti dan surat pekeliling.',
            'sections' => [
                'main' => [
                    'title' => 'Unit PBD',
                    'subtitle' => 'Sila pilih folder di bawah untuk melihat dokumen dan laporan PBD.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'PBD PPT',
                'description' => 'Laporan dan dokumen PBD bagi Peperiksaan Pertengahan Tahun mengikut tingkatan.',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD UASA',
                'description' => 'Laporan dan dokumen PBD bagi UASA.',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-uasa',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PENJAMINAN KUALITI PBD',
                'description' => 'Dokumen penjaminan kualiti Pentaksiran Bilik Darjah.',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-penjaminan-kualiti',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'SURAT PEKELILING',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'https://drive.google.com/drive/folders/1PYFHUPxvAt6-LeZacXZC9Cv1nkAxnCq1',
                'is_external' => 1,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
        ],
    ],

    'pbd-penjaminan-kualiti' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Penjaminan Kualiti PBD',
                    'subtitle' => 'Sila pilih folder di bawah.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'CARTA ORGANISASI',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'https://drive.google.com/drive/folders/1Z837DkxGziZFsADfgxL3fUQI1OYdirfn',
                'is_external' => 1,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'CONTOH LAPORAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'https://drive.google.com/drive/folders/1WZOT182f4RFi5Ajcow5wX9cmoaOhYX3N',
                'is_external' => 1,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PEMANTAUAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-pk-pemantauan',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PEMENTORAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-pk-pementoran',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PENGESANAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-pk-pengesanan',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PENYELARASAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-pk-penyelarasan',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
        ],
    ],

    'pbd-uasa' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'PBD UASA',
                    'subtitle' => 'Sila pilih folder tahun di bawah.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => '2025',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PBD INDIVIDU', 'href' => 'pbd-uasa-individu'],
                    ['title' => 'PBD MATA PELAJARAN', 'href' => smks3_drive_folder_url('1Y5b99nPnDoPBoNg71uXcS1vJcFVl5EKB')],
                    ['title' => 'PBD MATA PELAJARAN MENGIKUT TINGKATAN', 'href' => smks3_drive_folder_url('1v-aDhZu-m70QHlo95TAMRNh5e4XEDY3s')],
                    ['title' => 'PBD MENGIKUT KELAS', 'href' => smks3_drive_folder_url('1_ekv_WXKQbQESFtaUU2PydkoP26i8HQS')],
                ],
            ],
        ],
    ],

    'pbd-uasa-individu' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'PBD INDIVIDU',
                    'subtitle' => 'Senarai kelas PBD UASA 2025.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'SENARAI KELAS',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => (static function (): array {
                    $map = [
                        '1 IHSAN' => '1C3b-k_hSnEK1EblZOZ-4kgzl8yqfk-Fm',
                        '1 IKHLAS' => '1WYtV4YQGQcSwj3S3QvYfBndq8psVWVMY',
                        '1 IKRAM' => '1-ng7d4PC_5xMNS4XqBtQnQ9J7URmstT7',
                        '1 ILTIZAM' => '1uhcX28Ja1CNALhbo4gAtwUi7uin2nbnE',
                        '1 ITQAN' => '1htWPE82M-PhsB7aPt4tAQTVvn0db1xbP',
                        '2 IHSAN' => '1T_s90I-TkIWCK2I4ivwVpJc5Jt1tuLzB',
                        '2 IKHLAS' => '1gu6WFQQMKCmkq1s6NSXWik4fjdEKgLfT',
                        '2 IKRAM' => '14sEx_ZIUJbQWU6hk3FWE9MO71yn-0syu',
                        '2 ILTIZAM' => '',
                        '2 ITQAN' => '1A2HoYpB24ZwZl9C1qmBfK0nGwnJe7xlZ',
                        '3 IHSAN' => '1jd4sc6YCvVjw_QMdt8GzNTHGxnURcAT6',
                        '3 IKHLAS' => '1nV3VdN_sVXxccea1ZNzGWFyWk4_A_vWI',
                        '3 IKRAM' => '1J6t_-DdXd995dnRo0JdXGc3WSRgB9swr',
                        '3 ILTIZAM' => '',
                        '3 ITQAN' => '',
                        '4 IHSAN' => '16kKTrPMno5j9C70o_oVVD_wx1z7SRk9w',
                        '4 IKHLAS' => '1JOKP_D_Ri7V_Udmom61YWrUcOcN9sRiO',
                        '4 IKRAM' => '1Byexsqgr4E4gOxIQ0z0SYNpbiNkNtYfj',
                        '4 ILTIZAM' => '',
                        '4 ITQAN' => '13_IROWMw3z0yVZG7ej1_7_afDwcxO2T-',
                        '5 IHSAN' => '1MSnlZxnCu96RzQF9Vef9xxKJIjeYN7oe',
                        '5 IKHLAS' => '1VI2VzJT2yDxBNrTd4ICosBqb1W1v7Dl3',
                        '5 IKRAM' => '1x_G7c_WYLT7Mi1TYm9PTErC46rE7i0YW',
                        '5 ILTIZAM' => '',
                        '5 ITQAN' => '1_1IjG56cMNEsw5oUqxXisTPRSG-5il4T',
                    ];
                    $links = [];
                    foreach ($map as $title => $id) {
                        if ($id === '') {
                            continue;
                        }
                        $links[] = [
                            'title' => $title,
                            'href' => smks3_drive_folder_url($id),
                        ];
                    }
                    return $links;
                })(),
            ],
        ],
    ],

    'pbd-ppt' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'PBD PPT',
                    'subtitle' => 'Sila pilih tingkatan atau analisis di bawah.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'ANALISIS PBD UPSA',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'https://drive.google.com/drive/folders/1633mAw0AVJAMK2TIRe0OT8JhE75n4CjH',
                'is_external' => 1,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD TINGKATAN 1',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt-tingkatan-1',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD TINGKATAN 2',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt-tingkatan-2',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD TINGKATAN 3',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt-tingkatan-3',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD TINGKATAN 4',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt-tingkatan-4',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
            [
                'section_key' => 'main',
                'title' => 'PBD TINGKATAN 5',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => 'pbd-ppt-tingkatan-5',
                'is_external' => 0,
                'btn_label' => 'Lihat Bahagian',
                'links' => [],
            ],
        ],
    ],

    'analisis-ppt' => [
        'meta' => [
            'intro' => 'Paparan analisis pencapaian pelajar bagi Peperiksaan Pertengahan Tahun (PPT) mengikut tingkatan dan kategori penilaian.',
            'sections' => [
                'main' => [
                    'title' => 'Analisis PPT',
                    'subtitle' => 'Sila pilih kategori di bawah untuk melihat analisis keputusan secara terperinci berdasarkan tingkatan dan jenis laporan.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'ANALISIS SUBJEK',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'ANALISIS TINGKATAN 1', 'href' => 'https://drive.google.com/drive/folders/1k39ONdnhY3obIPg0N6RJyA5ai2ZwWx1_'],
                    ['title' => 'ANALISIS TINGKATAN 2', 'href' => 'https://drive.google.com/drive/folders/1R2W1wGO_ePd-uNhWzeDWiP3i33htP3J9'],
                    ['title' => 'ANALISIS TINGKATAN 3', 'href' => 'https://drive.google.com/drive/folders/1qb707O-WExRt5OjZ4t5jcs3gJpYU9ljE'],
                    ['title' => 'ANALISIS TINGKATAN 4', 'href' => 'https://drive.google.com/drive/folders/1rBNXK_N3T5E1pZCD9fmKbt2rzrwWFhgs'],
                    ['title' => 'ANALISIS TINGKATAN 5', 'href' => 'https://drive.google.com/drive/folders/1KrKdmy_RzyivezVLljUCZIK4ULs3EI8H'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'GPS (ANALISIS PENCAPAIAN KESELURUHAN PELAJAR)',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'TINGKATAN 1', 'href' => 'https://drive.google.com/drive/folders/13lFXcNtif8P9K9dkG5KFtk-fxJ9aBMZz'],
                    ['title' => 'TINGKATAN 2', 'href' => 'https://drive.google.com/drive/folders/1MY-IWun-kjQychhVpLIFSL_fUCB4FEDY'],
                    ['title' => 'TINGKATAN 3', 'href' => 'https://drive.google.com/drive/folders/1N-SHCq6ReLZ-Qwg5tLJBmtIO1lxacx8I'],
                    ['title' => 'TINGKATAN 4', 'href' => 'https://drive.google.com/drive/folders/1udxBb-p1r0cS5IFG-i1bXLPP0TGco-32'],
                    ['title' => 'TINGKATAN 5', 'href' => 'https://drive.google.com/drive/folders/1fl7z5Kodfyw3Kh2pSP5cSln3hvXhztWO'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'LEMBARAN MARKAH',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'TINGKATAN 1', 'href' => 'https://docs.google.com/spreadsheets/d/1cSzy0egD7D8zddVZnNQ-I2DSQIrW_7HG/edit?gid=1674267632#gid=1674267632'],
                    ['title' => 'TINGKATAN 2', 'href' => 'https://docs.google.com/spreadsheets/d/1bxllXQifY8pfpO5oNuCznnC7hqSsp_Ru/edit?gid=653020296#gid=653020296'],
                    ['title' => 'TINGKATAN 3', 'href' => 'https://docs.google.com/spreadsheets/d/1eSB60lHtVT72IFT1KtIPn4jPSyKfXapO/edit?gid=480898156#gid=480898156'],
                    ['title' => 'TINGKATAN 4', 'href' => 'https://drive.google.com/drive/folders/1lyQxHZBFe_dacVMcXux9Q_n4NXaRAK-h'],
                    ['title' => 'TINGKATAN 5', 'href' => 'https://drive.google.com/drive/folders/1_Cm1pDdHw9stW-NbKG--A7hwH5aD9Fl2'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'PERATUS LAYAK SIJIL TING. 4 & 5',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'ANALISA LMS T4 2025', 'href' => 'https://docs.google.com/spreadsheets/d/1ZKRve4wTW8Wrt5mErhaq8A6gArpHRapw/edit?gid=882625758#gid=882625758'],
                    ['title' => 'ANALISA LMS T5 2025', 'href' => 'https://docs.google.com/spreadsheets/d/1OIYROEwwh_jEUoj6WURtqsCm-sdzFp-H/edit?gid=362934113#gid=362934113'],
                    ['title' => 'SENARAI NAMA PELAJAR GAGAL BM SEJ', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'RUMUSAN RANKING',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'TINGKATAN 1', 'href' => '#'],
                    ['title' => 'TINGKATAN 2', 'href' => '#'],
                    ['title' => 'TINGKATAN 3', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'SISTEM PEPERIKSAAN',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => '2025', 'href' => '#'],
                ],
            ],
        ],
    ],

    'analisis-pat-t4-uasa-t1,2,3' => [
        'meta' => [
            'intro' => 'Paparan analisis pencapaian pelajar bagi Peperiksaan Akhir Tahun (PAT) Tingkatan 4 serta Ujian Akhir Sesi Akademik (UASA) bagi Tingkatan 1, 2 dan 3.',
            'sections' => [
                'main' => [
                    'title' => 'Analisis PAT T4 & UASA T1,2,3',
                    'subtitle' => 'Sila pilih kategori di bawah untuk melihat analisis terperinci mengikut tingkatan dan jenis laporan.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'ANALISIS SUBJEK',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => '2025 - TINGKATAN 1 - ANALISIS MP F1 UASA 2025', 'href' => 'https://docs.google.com/spreadsheets/d/1P0eT9DIDCN51UZpX7ic1fDSl1K0R4AG7/edit?gid=269500431#gid=269500431'],
                    ['title' => '2025 - TINGKATAN 1 - ANALISIS MP UPSA TANPA PECAHAN KELAS T1 2025', 'href' => 'https://docs.google.com/spreadsheets/d/1mC8Va3NB6E-ZTd0Dz-37hD8_ge1Mm_LH/edit?gid=103880239#gid=103880239'],
                    ['title' => '2025 - TINGKATAN 2', 'href' => 'https://docs.google.com/spreadsheets/d/1Lrfb9AOSByxJx6xVNkE9L44mjFu-0dIr/edit?gid=360450347#gid=360450347'],
                    ['title' => '2025 - TINGKATAN 3', 'href' => 'https://docs.google.com/spreadsheets/d/1A95TWniq2p_XH4_wxKWj0Jlvtp0pJemd/edit?gid=656362314#gid=656362314'],
                    ['title' => '2025 - TINGKATAN 4', 'href' => 'https://docs.google.com/spreadsheets/d/13EX8bdxT3SRcQJhgHDlL3IM0COgJvUvq/edit?gid=1650092040#gid=1650092040'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'GPS',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => '2025', 'href' => 'https://docs.google.com/spreadsheets/d/12NKdE7rZdZEYz9xoUA75fja2_IeNmZEV/edit?gid=1473146862#gid=1473146862'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'LEMBARAN MARKAH',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'TINGKATAN 1', 'href' => 'https://drive.google.com/drive/folders/1u46c82LzcW2YjTqKILsL1fMld4PFv3T7'],
                    ['title' => 'TINGKATAN 2', 'href' => 'https://drive.google.com/drive/folders/1f5zGFQS1c7bLS_zw-P-JrK9s6WfzIQxd'],
                    ['title' => 'TINGKATAN 3', 'href' => 'https://drive.google.com/drive/folders/1xGpoij2d5X0NACZYL4YtogDJRpuHDkFI'],
                    ['title' => 'TINGKATAN 4', 'href' => 'https://drive.google.com/drive/folders/11-lJSrTVBqFXoYDBqAYUFzHJU7U1s5Ee'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'LMS TING. 4',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => '2025', 'href' => 'https://drive.google.com/drive/folders/1w5uMsiu9JYBlifgyLFJrUmvKNy9Z3b8M'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'PELAPORAN UASA',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PELAPORAN UASA TINGKATAN 1', 'href' => '#'],
                    ['title' => 'PELAPORAN UASA TINGKATAN 2', 'href' => '#'],
                    ['title' => 'PELAPORAN UASA TINGKATAN 3', 'href' => '#'],
                ],
            ],
        ],
    ],

    'bank-soalan-uasa-ppt-pat-selaras' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Bank Soalan UASA PPT, PAT',
                    'subtitle' => '',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'PPT TING. 5 TING. 5 SELARAS',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'UPSA T5 2024', 'href' => 'https://drive.google.com/drive/folders/1ab5SLC3HTikGtauJ5uvqgvMgaGBWNh6h'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'SOALAN PROGRAM PENINGKATAN AKADEMIK TING 4 (PAT)',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'BI FORM 4', 'href' => '#'],
                    ['title' => 'BM FORM 4', 'href' => '#'],
                    ['title' => 'MATEMATIK FORM 4', 'href' => '#'],
                    ['title' => 'PEND MORAL FORM 4', 'href' => '#'],
                    ['title' => 'PAI FORM 4', 'href' => '#'],
                    ['title' => 'SAINS FORM 4', 'href' => '#'],
                    ['title' => 'SEJARAH FORM 4', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'UASA',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'BCK', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'UPSA',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'Empty', 'href' => '#'],
                ],
            ],
        ],
    ],

    'keputusan' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Keputusan 2018-2024',
                    'subtitle' => '',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => '2018',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PEPERIKSAAN PERTENGAHAN TAHUN', 'href' => 'https://drive.google.com/drive/folders/1lQeGZzIFOwVfObRV3yytaz1RattFGvWT'],
                    ['title' => 'UJIAN 1', 'href' => 'https://drive.google.com/drive/folders/1KrVNjZ55v4pr5_aNxus5xHrhiIymd8Rn'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2019',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PEPERIKSAAN PERTENGAHAN TAHUN', 'href' => 'https://drive.google.com/drive/folders/14cTAYDHj653MhFb1QzhQgJAZeyvkrBNF'],
                    ['title' => 'PEPERIKSAAN AKHIR TAHUN', 'href' => 'https://drive.google.com/drive/folders/1ndp7GQw-Igh68JOwpjwkXkaVs8WF26w3'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2020',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PEPERIKSAAN PERTENGAHAN TAHUN', 'href' => 'https://drive.google.com/drive/folders/11Fe5x9uesnrd_pQ2iy7ocRJP-w1G42iK'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2021',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PEPERIKSAAN PERTENGAHAN TAHUN', 'href' => 'https://drive.google.com/drive/folders/1b08Rzxi_DhvRcyn7EmQbrGehdJpHnpSi'],
                    ['title' => 'PEPERIKSAAN AKHIR TAHUN', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2022',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'PEPERIKSAAN PERTENGAHAN TAHUN', 'href' => 'https://drive.google.com/drive/folders/1WSPHYYoX_7I9iX4DhuXW-RkpQC4bEtFg'],
                    ['title' => 'UASA', 'href' => '#'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2023',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'UASA', 'href' => 'https://drive.google.com/drive/folders/16dPHvgsyLCpKAtEDemWA7q_zwLbiSM8p'],
                    ['title' => 'UPSA', 'href' => 'https://drive.google.com/drive/folders/1Qz1tY8mLIC8OEX5yD7z1L4GKvoUBHhTN'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => '2024',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'KEPUTUSAN UASA', 'href' => 'https://drive.google.com/drive/folders/1LWuNvvvie8FmtD3G2MiiK0gpO0fypWt5'],
                    ['title' => 'PELAPORAN PBD AKHIR SESI AKADEMIK 2024/2025', 'href' => 'https://drive.google.com/drive/folders/1TOfuq0onlcgl5w5GaDz9jJRDvAUJdBR1'],
                    ['title' => 'UPSA', 'href' => 'https://drive.google.com/drive/folders/15MyOSyJqeIoNGriweX9T3cvxnfmBcsn7'],
                ],
            ],
        ],
    ],

    'penggubal-soalan-upsa-uasa' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Penggubal Soalan UPSA & UASA',
                    'subtitle' => '',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'PENGGUBAL SOALAN 2025',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'JADUAL PENGGUBAL SOALAN 2025 PN', 'href' => 'https://docs.google.com/document/d/1-lQWTLiupHJ_a9gzp_ggq6Uu5RolbMG2/edit'],
                    ['title' => 'JADUAL PENGGUBAL SOALAN PANITIA SAINS 2025', 'href' => 'https://docs.google.com/document/d/1kyxQcUhrnLw4PVp-4_K4PDP_4jZhwD06/edit'],
                    ['title' => 'JADUAL PENGGUBALAN SOALAN PAI', 'href' => 'https://docs.google.com/document/d/1TbA8imtGDTGs_3f0TjNsfLaVwjqjhyem/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 - BAHASA TAMIL', 'href' => 'https://docs.google.com/document/d/1zFPDsLFoSxVqjQdy9PRZxO5S2tViugk2/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 (1)', 'href' => 'https://docs.google.com/document/d/1x8tCX0g1c6wiyC-sWZYQ3lZTPdNxj1qt/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 (ASK)', 'href' => 'https://docs.google.com/document/d/1OVE60tQJqjCeKnAHVSoFNxHUGVfBkZgb/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 (BC)', 'href' => 'https://docs.google.com/document/d/1q4IX_ViC3UOByf50nfzfcXKm6TvOQypp/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 (BCK)', 'href' => 'https://docs.google.com/document/d/1WgxhWa3rM-JsT1ZqnqhC7BSMUavkx6j0/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 2', 'href' => 'https://docs.google.com/document/d/1AijTsapCkX3md-UWkuZb0eqMhUiJEdSH/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025 BM', 'href' => 'https://docs.google.com/document/d/18VwjrQBazeBenBwRtzQd1qJcTCTPczIo/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025', 'href' => 'https://docs.google.com/document/d/1qH9WYVU31k5imlMjnRrDau5c2Oun_MEJ/edit'],
                    ['title' => 'Penggubal Soalan 2025', 'href' => 'https://docs.google.com/document/d/1ByWolsR_-eR7GHMRhsmjxNh97w8uZmHO/edit'],
                    ['title' => 'PENGGUBAL SOALAN 2025.P.MORAL', 'href' => 'https://docs.google.com/document/d/1jjn-6-U8jE2myvM7SPW8U6STveTlOxbO/edit'],
                    ['title' => 'PENGGUBAL SOALAN SEJARAH PEPERIKSAAN TAHUN 2025 ', 'href' => 'https://docs.google.com/document/d/1YjjRZ1diDjcFxpc9JCmGq_BZHQcpDqqg/edit'],
                ],
            ],
            [
                'section_key' => 'main',
                'title' => 'PENGGUBAL SOALAN 2026',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => [
                    ['title' => 'Empty', 'href' => '#'],
                ],
            ],
        ],
    ],

    'pra-sekolah' => [
        'meta' => [
            'intro' => '',
            'sections' => [
                'carta' => [
                    'title' => 'Carta Organisasi Sekolah',
                    'subtitle' => 'Carta organisasi ini memaparkan struktur pengurusan sekolah, daripada pengetua hingga ke guru-guru.',
                ],
                'galeri' => [
                    'title' => 'Galeri Murid',
                    'subtitle' => 'Beberapa gambar aktiviti dan pelajar sekolah semasa sesi pembelajaran dan kokurikulum.',
                ],
            ],
        ],
        'cards' => [],
    ],

    'pemimpin-murid' => [
        'meta' => [
            'intro' => 'Barisan kepimpinan murid yang berwibawa, berdisiplin dan komited dalam membantu pengurusan serta pembangunan sahsiah pelajar di sekolah.',
            'sections' => [
                'main' => [
                    'title' => 'Barisan Pemimpin Murid',
                    'subtitle' => '',
                ],
                'info' => [
                    'title' => 'Maklumat Berkaitan',
                    'subtitle' => '',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'info',
                'title' => 'Carta Organisasi',
                'description' => 'Lihat struktur kepimpinan pengawas sekolah dalam format PDF.',
                'icon' => 'bi-file-earmark-pdf-fill',
                'href' => 'images/CARTA ORGANISASI PENGAWAS.pdf',
                'is_external' => 1,
                'btn_label' => 'Buka Dokumen',
                'links' => [],
            ],
            [
                'section_key' => 'info',
                'title' => 'NextGen Leaders 3',
                'description' => 'Portal rasmi program pembangunan kepimpinan murid.',
                'icon' => 'bi-globe',
                'href' => 'https://nextgenleaders3.my.canva.site/',
                'is_external' => 1,
                'btn_label' => 'Lawati Portal',
                'links' => [],
            ],
            [
                'section_key' => 'info',
                'title' => 'Senarai Nama MPP',
                'description' => 'Senarai penuh Majlis Perwakilan Pelajar 2026.',
                'icon' => 'bi-file-earmark-text-fill',
                'href' => 'images/SENARAI NAMA MPP 2026.pdf',
                'is_external' => 1,
                'btn_label' => 'Lihat Senarai',
                'links' => [],
            ],
        ],
    ],
];

for ($tingkatan = 1; $tingkatan <= 5; $tingkatan++) {
    $pageKey = 'pbd-ppt-tingkatan-' . $tingkatan;
    $individuKey = $pageKey . '-individu';

    $pptByTingkatan = [
        1 => [
            'kelas_title' => 'PBD KESELURUHAN MENGIKUT KELAS',
            'kelas' => '1tLyAF3QRBOOOu0BnEFBneZYSqFNnmKGs',
            'mata' => '1hSMCgCnW_lPRtiOeRKVWPslybmmAn_BA',
            'individu' => [
                'PBD 1 IHSAN' => '1HxvTsMngrSa6gEiUu-4W_SRgVbLG-Lh1',
                'PBD 1 IHSAN KM' => '1xMRzMCAbw4Xwew9XTqbmrcrhBK9bxuHA',
                'PBD 1 IKHLAS KM' => '1PkGBA9B0YcZaONfVwVs0yOIONEgiAhG4',
                'PBD 1 IKRAM KM' => '1iTpZrsRVC1uqZZ5FnAKHKM9ArJl9NWVQ',
                'PBD 1 ILTIZAM' => '1xLj4fEsyvWXXjHg8vvcsw5I00WXjvRQU',
                'PBD 1 ILTIZAM KM' => '1k-sI6FDDshk3tYPz1Ti9Z6cFlVkxvGO9',
                'PBD 1 ITQAN' => '1D-zGT0nkGRdHV19Ej8zBgTnvEbm0gqUr',
            ],
        ],
        2 => [
            'kelas_title' => 'PBD MENGIKUT KELAS',
            'kelas' => '1z9-jvMK6i1E-tXF--Sr-aEpPsjWwu43r',
            'mata' => '1QAA6SAPAydboY1ZoWL5_uytAuZXeOn6D',
            'individu' => [
                'PBD 2 IHSAN' => '1YeQdo3-W0Jbygf6b2VfNiZb7jPVKpj_o',
                'PBD 2 IHSAN KM' => '1dm1GTFL3JNnJZqjeDvAN-4xX3Msuk67V',
                'PBD 2 IKHLAS' => '1WLJ6To9ebSXDFgBSvSZTM3z8D8N9tCId',
                'PBD 2 IKHLAS KM' => '15ZQgvg81UhgvcYIV5mK3egIUGAQldj0k',
                'PBD 2 IKRAM' => '1YeeCELZIyOsvJkhhjlgIvdEESq0VcHG5',
                'PBD 2 IKRAM KM' => '13gUxOJcalFCM5ZxXsCRW311WybnIYM47',
                'PBD 2 ITQAN' => '1Tz1HReaYTSJhl9KxHTXsfkOL78sxZPU9',
                'PBD 2 ITQAN KM' => '1z4axsntcpFIvrj-XL5VSt65sG25BcCWr',
            ],
        ],
        3 => [
            'kelas' => '1s8NrASnpJaHISoQ6Malek85Oa_hu09rD',
            'mata' => '1iHXhTiyRaZjrOHggqmg9r1Nr6E_djdUO',
            'individu' => [
                'PBD 3 IHSAN' => '1nLAFfqUw0tDIktR_OZGAxEzCI53tTR1X',
                'PBD 3 IHSAN KM' => '1TwQRvwtb2GPL3eVDCQtmcTaAIMH0W75U',
                'PBD 3 IKHLAS' => '1XTYbejqnqJNoRZvblMvcGj9PahEhM8Iy',
                'PBD 3 IKHLAS KM' => '1HW2v5ZbXmPgS0B5M3iugWG8y6QoKiub-',
                'PBD 3 IKRAM' => '1jqbozmxXLTMc_9FuDUntSn4bjuhzKir-',
                'PBD 3 IKRAM KM' => '14l6DqBNZTal6Hn9-j_hsmTmHxhs4Wxfe',
            ],
        ],
        4 => [
            'kelas' => '149LCrESxTnSbVB3JOiyxrj1gkATCQulR',
            'mata' => '1Rn8FpYgdkHPXL1UkTvZFY7jpR-gTK4gs',
            'individu' => [
                'PBD 4 IHSAN' => '1STwkb-KEz8j1YEyhox6B7vmv_oWboGJM',
                'PBD 4 IHSAN KM' => '12NOly-iJ0qyx-ffbUGYAGx9KxNOHzpwt',
                'PBD 4 IKHLAS' => '1OZux9KSWOI31W8HCwdvkZ8WqIinsM56k',
                'PBD 4 IKHLAS KM' => '1yH1Yp-tYU3_nNOG_6NyRWMKkENjFTHVW',
                'PBD 4 IKRAM' => '1c-aFmZ6wbN1Jb9V7ls44Hhf1KI7Y0-ao',
                'PBD 4 IKRAM KM' => '179Ath8Mzcz_UdA_djY5yRZplGz2rbtSc',
                'PBD 4 ITQAN' => '1_K8q_CyOPt9R8PDCVGx4xLFEPAdT8oRl',
            ],
        ],
        5 => [
            'kelas' => '13oeAoyh4TL_iTRXxORrYHjxrS8VMHbDB',
            'mata' => '1HJW2xqs9ATrl260Sl68IEkq7RuSIKNgi',
            'individu' => [
                'PBD 5 IHSAN' => '1gX7JBHUqMKkJT4X-Z7BlTyCZCzPfkIuP',
                'PBD 5 IHSAN KM' => '1tK8opYMAnKGHKl9Eq4u2qioXah3Fc5JC',
                'PBD 5 IKHLAS' => '1m2ZQ4iMC_oxdQPrcMPPv_VdusSLPcq2t',
                'PBD 5 IKHLAS KM' => '12_-fB8Dzam60qMrES3jmnV6ejnBZ_KNN',
                'PBD 5 IKRAM' => '1ZLLRwtIXITZLRS00Sms7grVsxF3eyGhc',
                'PBD 5 IKRAM KM' => '1q3_zXgNKILqfjJ63BtcGNS3tdF2_Eo8R',
                'PBD 5 ITQAN' => '1Ks2BhSSrQIJksmLwCJbm9n_h8ufnabbn',
                'PBD 5 ITQAN KM' => '1wGOWwo_nuiwd20NPkpf_xfvNKqMdISKd',
            ],
        ],
    ];

    $cfg = $pptByTingkatan[$tingkatan];
    $individuLinks = [];
    foreach ($cfg['individu'] as $title => $folderId) {
        $individuLinks[] = [
            'title' => $title,
            'href' => smks3_drive_folder_url($folderId),
        ];
    }

    if ($tingkatan <= 2) {
        $defaults[$pageKey] = [
            'meta' => [
                'intro' => '',
                'sections' => [
                    'main' => [
                        'title' => 'PBD Tingkatan ' . $tingkatan,
                        'subtitle' => 'Sila pilih folder tahun di bawah.',
                    ],
                ],
            ],
            'cards' => [
                [
                    'section_key' => 'main',
                    'title' => '2025',
                    'description' => '',
                    'icon' => 'bi-folder2-open',
                    'href' => '',
                    'is_external' => 0,
                    'btn_label' => '',
                    'links' => [
                        ['title' => 'PBD INDIVIDU', 'href' => $individuKey],
                        ['title' => $cfg['kelas_title'], 'href' => smks3_drive_folder_url($cfg['kelas'])],
                        ['title' => 'PBD MENGIKUT MATA PELAJARAN', 'href' => smks3_drive_folder_url($cfg['mata'])],
                    ],
                ],
            ],
        ];
    } else {
        $defaults[$pageKey] = [
            'meta' => [
                'intro' => '',
                'sections' => [
                    'main' => [
                        'title' => 'PBD Tingkatan ' . $tingkatan,
                        'subtitle' => 'Sila pilih folder di bawah.',
                    ],
                ],
            ],
            'cards' => [
                [
                    'section_key' => 'main',
                    'title' => 'PBD INDIVIDU',
                    'description' => '',
                    'icon' => 'bi-folder2-open',
                    'href' => $individuKey,
                    'is_external' => 0,
                    'btn_label' => 'Lihat Bahagian',
                    'links' => [],
                ],
                [
                    'section_key' => 'main',
                    'title' => 'PBD MENGIKUT KELAS',
                    'description' => '',
                    'icon' => 'bi-folder2-open',
                    'href' => smks3_drive_folder_url($cfg['kelas']),
                    'is_external' => 1,
                    'btn_label' => 'Lihat Bahagian',
                    'links' => [],
                ],
                [
                    'section_key' => 'main',
                    'title' => 'PBD MENGIKUT MATA PELAJARAN',
                    'description' => '',
                    'icon' => 'bi-folder2-open',
                    'href' => smks3_drive_folder_url($cfg['mata']),
                    'is_external' => 1,
                    'btn_label' => 'Lihat Bahagian',
                    'links' => [],
                ],
            ],
        ];
    }

    $defaults[$individuKey] = [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'PBD INDIVIDU',
                    'subtitle' => 'Senarai kelas PBD Tingkatan ' . $tingkatan . '.',
                ],
            ],
        ],
        'cards' => [
            [
                'section_key' => 'main',
                'title' => 'SENARAI KELAS',
                'description' => '',
                'icon' => 'bi-folder2-open',
                'href' => '',
                'is_external' => 0,
                'btn_label' => '',
                'links' => $individuLinks,
            ],
        ],
    ];
}

$pbdPkBidang = [
    'pemantauan' => [
        'BIDANG BAHASA' => '1as5LIQc-5ZzkItVL0pmpnR-eEFU4FeQO',
        'BIDANG KEMANUSIAAN' => '1au2g8Esejb3pKCCw0QO2m3tkfPTEaD_s',
        'BIDANG SAINS MATEMATIK' => '1RItAR6G-EpbOrMjML82mmaaOboLbGjZD',
        'BIDANG TEKNIK VOKASIONAL' => '18P8Lbpt-klLG3coYUUGR2dYCuU5p3GxK',
    ],
    'pementoran' => [
        'BIDANG BAHASA' => '1xPG7JFj4OpBqwkpe1QlPLTnjULG21N7_',
        'BIDANG KEMANUSIAAN' => '1AV6oEEiak1fETMgSGcEzieU2uIUO3E-b',
        'BIDANG SAINS MATEMATIK' => '1zhJQ1AT2JbYPPPTn0jPM9ElMoXPVqRzL',
        'BIDANG TEKNIK VOKASIONAL' => '1T7CsZt7FJb-R49ERXiWFIMzVkpcnhApG',
    ],
    'pengesanan' => [
        'BIDANG BAHASA' => '1RVS_tyRtcJ-BARkthk8thNZ2zRJHVvW1',
        'BIDANG KEMANUSIAAN' => '1COM749i4ylHIszp16tC8tCVvh0HlM5D0',
        'BIDANG SAINS MATEMATIK' => '1yis9_fCTCnNzhJFonVl2MSpjcgRz_W7h',
        'BIDANG TEKNIK VOKASIONAL' => '1O0rleeDWlWaMXj5vIV0uQ0ZMOLOVRnHh',
    ],
    'penyelarasan' => [
        'BIDANG BAHASA' => '1zOtN4BHOy8VFC9FNXjenojrtS20FUqKy',
        'BIDANG KEMANUSIAAN' => '1-ujD6Lzo_Efm2CI0BXPRcRjKvvSnsxf5',
        'BIDANG SAINS MATEMATIK' => '1bzKn2fyoB2-pvbeWYzsdeerEOhvQbHr7',
        'BIDANG TEKNIK VOKASIONAL' => '1zVzvyn_5huoTcqV793QMhSRmMImx4Kix',
    ],
];

$pbdPkFolders = [
    'pemantauan' => 'PEMANTAUAN',
    'pementoran' => 'PEMENTORAN',
    'pengesanan' => 'PENGESANAN',
    'penyelarasan' => 'PENYELARASAN',
];

foreach ($pbdPkFolders as $slug => $label) {
    $bidangCards = [];
    foreach ($pbdPkBidang[$slug] as $bidang => $folderId) {
        $bidangCards[] = [
            'section_key' => 'main',
            'title' => $bidang,
            'description' => '',
            'icon' => 'bi-folder2-open',
            'href' => smks3_drive_folder_url($folderId),
            'is_external' => 1,
            'btn_label' => 'Lihat Bahagian',
            'links' => [],
        ];
    }

    $defaults['pbd-pk-' . $slug] = [
        'meta' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => $label,
                    'subtitle' => 'Sila pilih bidang di bawah.',
                ],
            ],
        ],
        'cards' => $bidangCards,
    ];
}

return $defaults;
