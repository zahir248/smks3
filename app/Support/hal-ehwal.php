<?php

declare(strict_types=1);

function smks3_default_ubk_content(): array
{
    return [
        'lead' => 'Perkhidmatan bimbingan menyeluruh untuk pembangunan akademik, kerjaya, psikologi, kepimpinan, dan kesihatan mental pelajar.',
        'pengenalan_title' => 'Pengenalan Perkhidmatan Bimbingan & Kaunseling',
        'pengenalan_body' => 'Perkhidmatan Bimbingan dan Kaunseling di sekolah bersifat menyeluruh dan meliputi program perkembangan, pencegahan serta pemulihan. Program perkembangan dan pencegahan lebih banyak dijalankan berbanding program pemulihan. Antara perkhidmatan yang disediakan ialah bidang akademik, kerjaya, psikologi dan kesihatan mental, kepimpinan, dan kaunseling individu / kelompok.',
        'visi' => 'Mewujudkan iklim pendidikan yang kondusif, teraputik dan efektif dari aspek fizikal, perhubungan dan pengurusan berteraskan konsep perkhidmatan bimbingan dan kaunseling berkualiti secara menyeluruh dan berkesan demi kecemerlangan pelajar dan sekolah.',
        'misi' => 'Dengan penuh rasa tanggungjawab dan bersungguh-sungguh menyediakan perkhidmatan menolong bantu pelajar dari aspek pengayaan, perkembangan, pencegahan dan pemulihan untuk mempertingkatkan pengetahuan, ketrampilan dan konsep kendiri positif yang perlu dalam menghasilkan masyarakat ‘MADANI’ melalui perkhidmatan bimbingan dan kaunseling yang berkesan beramanah berteraskan falsafah pendidikan kebangsaan.',
        'falsafah' => 'Bahawasanya setiap pelajar mempunyai potensi yang boleh digembleng secara optimum menerusi pengurusan menyeluruh perkhidmatan bimbingan dan kaunseling yang cekap, berkesan dan beramanah berteraskan sumber dalaman dan luaran bagi melahirkan pelajar yang seimbang dari aspek intelektual, jasmani, emosi dan rohani serta beriman dan beramal soleh.',
        'objektif' => [
            'Merujuk pada pamplet rasmi Bimbingan & Kaunseling (boleh ditambah secara dinamik nanti)',
        ],
        'fungsi' => [
            'Menyedia rancangan tahunan program dan aktiviti perkhidmatan Bimbingan & Kaunseling.',
            'Mengenalpasti keperluan perkhidmatan Bimbingan & Kaunseling sekolah melalui kajian keperluan, soal selidik, temu bual dan perbincangan dengan pelajar, guru, pentadbiran, kakitangan sekolah, ibu bapa dan bekas pelajar.',
            'Merancang, mengawal selia dan mengemaskini rekod dan inventori.',
            'Mengelola dan melaksanakan aktiviti Unit Bimbingan Kaunseling Kelompok dan tunjuk ajar yang meransang perkembangan pelajar secara optimum.',
            'Mengumpul, menyediakan, dan menyebar maklumat UBK kepada semua pelajar.',
            'Merancang, melaksana dan mengawal selia perkhidmatan kaunseling individu secara profesional dan beretika.',
            'Merancang, melaksana dan mengawal selia aktiviti kemahiran belajar untuk semua pelajar.',
            'Merancang, melaksana dan menilai program pencegahan dadah, inhalan, rokok dan alkohol.',
            'Merancang, melaksana program peluang melanjutkan pelajaran di IPT dalam & luar negara.',
            'Memberi khidmat kaunseling krisis kepada pelajar, guru, kakitangan dan ibu bapa.',
            'Menjadi personel perhubungan dengan agensi luar berkaitan.',
            'Menjadi ahli jawatankuasa dalam Majlis Perancang, Disiplin, Lembaga/Badan Pengawas sekolah.',
            'Menjadi penyelaras dalam program mentor mentee.',
        ],
        'carta_image' => 'images/carta organisasi ubk 2026.jpg',
        'pamplet1_image' => 'images/pamplet1.jpg',
        'pamplet2_image' => 'images/pamplet2.jpg',
        'aktiviti_note' => '[Rujuk Google Drive / dokumen aktiviti]',
    ];
}

function smks3_parse_lines_list(string $text): array
{
    $out = [];
    foreach (preg_split('/\r\n|\n|\r/', $text) ?: [] as $line) {
        $line = trim($line);
        if ($line !== '') {
            $out[] = $line;
        }
    }
    return $out;
}

function smks3_format_lines_list(array $items): string
{
    $lines = [];
    foreach ($items as $item) {
        $item = trim((string) $item);
        if ($item !== '') {
            $lines[] = $item;
        }
    }
    return implode("\n", $lines);
}

function smks3_get_ubk_content(): array
{
    $defaults = smks3_default_ubk_content();
    $stored = smks3_get_json_content('unit_bimbingan_kaunseling', []);
    if (!is_array($stored) || $stored === []) {
        return $defaults;
    }

    foreach ($defaults as $key => $defaultVal) {
        if (!array_key_exists($key, $stored)) {
            continue;
        }
        if (is_array($defaultVal)) {
            $list = $stored[$key];
            if (is_string($list)) {
                $list = smks3_parse_lines_list($list);
            }
            if (is_array($list) && $list !== []) {
                $defaults[$key] = array_values(array_filter(array_map('strval', $list), static fn($v) => trim($v) !== ''));
            }
            continue;
        }
        $val = trim((string) $stored[$key]);
        if ($val !== '') {
            $defaults[$key] = $val;
        }
    }
    return $defaults;
}

function smks3_save_ubk_content(array $content): bool
{
    $defaults = smks3_default_ubk_content();
    $payload = [];
    foreach ($defaults as $key => $defaultVal) {
        if (is_array($defaultVal)) {
            $list = $content[$key] ?? [];
            if (is_string($list)) {
                $list = smks3_parse_lines_list($list);
            }
            if (!is_array($list)) {
                $list = [];
            }
            $payload[$key] = array_values(array_filter(array_map(
                static fn($v) => trim((string) $v),
                $list
            ), static fn($v) => $v !== ''));
            continue;
        }
        $payload[$key] = trim((string) ($content[$key] ?? $defaultVal));
    }
    return smks3_save_site_content(
        'unit_bimbingan_kaunseling',
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}'
    );
}

function smks3_ubk_img_src(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'uploads/') || str_starts_with($path, 'images/')) {
        return $path;
    }
    return 'uploads/ubk/' . ltrim($path, '/');
}

function smks3_default_hal_ehwal_meta(): array
{
    return [
        'enrolmen-murid' => [
            'intro' => 'Susun atur kelas mengikut blok dan aras di SMK Seremban 3.',
            'sections' => [
                'main' => [
                    'title' => 'Enrolmen Murid',
                    'subtitle' => '',
                ],
            ],
        ],
        'bil-kelas-gambar' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Bilangan Kelas (Gambar)',
                    'subtitle' => 'Susunan kelas mengikut tingkatan',
                ],
            ],
        ],
        'peraturan-sekolah' => [
            'intro' => '',
            'sections' => [
                'main' => [
                    'title' => 'Peraturan Sekolah',
                    'subtitle' => 'Berikut merupakan garis panduan dan peraturan yang perlu dipatuhi oleh semua pelajar bagi memastikan disiplin dan suasana pembelajaran yang kondusif di sekolah.',
                ],
            ],
        ],
        'pemimpin-murid' => [
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
    ];
}

/** Ensure hal-ehwal page meta defaults exist in kurikulum defaults lookup. */
function smks3_ensure_hal_ehwal_meta_defaults(): void
{
    // no-op placeholder — meta is read via smks3_get_page_meta()
}

function smks3_get_page_meta(string $pageKey): array
{
    $hal = smks3_default_hal_ehwal_meta();
    if (isset($hal[$pageKey])) {
        $defaults = $hal[$pageKey];
        $stored = smks3_get_json_content('kurikulum_meta_' . $pageKey, []);
        $intro = (string) ($defaults['intro'] ?? '');
        $sections = is_array($defaults['sections'] ?? null) ? $defaults['sections'] : [];
        if (isset($stored['intro']) && is_string($stored['intro']) && trim($stored['intro']) !== '') {
            $intro = trim($stored['intro']);
        }
        if (isset($stored['sections']) && is_array($stored['sections'])) {
            foreach ($stored['sections'] as $key => $sec) {
                if (!is_array($sec)) {
                    continue;
                }
                $key = (string) $key;
                if (!isset($sections[$key]) || !is_array($sections[$key])) {
                    $sections[$key] = ['title' => '', 'subtitle' => ''];
                }
                if (isset($sec['title']) && trim((string) $sec['title']) !== '') {
                    $sections[$key]['title'] = trim((string) $sec['title']);
                }
                if (array_key_exists('subtitle', $sec)) {
                    $sections[$key]['subtitle'] = trim((string) $sec['subtitle']);
                }
            }
        }
        return ['intro' => $intro, 'sections' => $sections];
    }
    return smks3_get_kurikulum_meta($pageKey);
}

function smks3_default_enrolmen_content(): array
{
    return [
        'feb' => [
            'title' => 'ENROLMENT FEBRUARY',
            'image' => 'images/ENROLMENT FEB.jpg',
        ],
        'summary_title' => 'Bilangan Kelas ( IKRAM/ IHSAN/ IKHLAS/ ITQAN )',
        'summary' => [
            'Tingkatan 1 – 4 Kelas',
            'Tingkatan 2 – 4 Kelas',
            'Tingkatan 3 – 4 Kelas',
            'Tingkatan 4 – 4 Kelas',
            'Tingkatan 5 – 4 Kelas',
            'Pra – 1 Kelas (Spectra)',
        ],
        'blok_a' => [
            'title' => 'Blok Akademik A',
            'floors' => [
                [
                    'name' => 'Aras 3',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => 'B. BAHASA TAMIL', 'class' => 'special'],
                        ['label' => 'B. BAHASA CINA', 'class' => 'special'],
                        ['label' => '2 IKHLAS', 'class' => 't2'],
                    ],
                ],
                [
                    'name' => 'Aras 2',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => '1 IKRAM', 'class' => 't1'],
                        ['label' => '1 IHSAN', 'class' => 't1'],
                        ['label' => '1 IKHLAS', 'class' => 't1'],
                        ['label' => '1 ITQAN', 'class' => 't1'],
                        ['label' => '2 IKRAM', 'class' => 't2'],
                        ['label' => '2 IHSAN', 'class' => 't2'],
                        ['label' => '2 IKHLAS', 'class' => 't2'],
                    ],
                ],
                [
                    'name' => 'Aras 1',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => '4 IHSAN', 'class' => 't4'],
                        ['label' => '4 IKRAM', 'class' => 't4'],
                        ['label' => '3 ITQAN', 'class' => 't3'],
                        ['label' => '3 IKHLAS', 'class' => 't3'],
                        ['label' => '3 IHSAN', 'class' => 't3'],
                        ['label' => '3 IKRAM', 'class' => 't3'],
                        ['label' => '2 ITQAN', 'class' => 't2'],
                    ],
                ],
                [
                    'name' => 'Aras Bawah',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => 'SURAU', 'class' => 'special surau'],
                        ['label' => 'BILIK PAK21', 'class' => 'special'],
                        ['label' => 'BILIK MATEMATIK', 'class' => 'special'],
                    ],
                ],
            ],
        ],
        'blok_b' => [
            'title' => 'Blok Akademik B',
            'floors' => [
                [
                    'name' => 'Aras 3',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                        ['label' => '-', 'class' => 'special'],
                    ],
                ],
                [
                    'name' => 'Aras 2',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => 'BILIK MULTIMEDIA', 'class' => 'special'],
                        ['label' => 'BILIK TAYANGAN', 'class' => 'special'],
                        ['label' => '5 IKRAM', 'class' => 't5'],
                        ['label' => '5 IHSAN', 'class' => 't5'],
                        ['label' => '5 IKHLAS', 'class' => 't5'],
                        ['label' => '4 ITQAN', 'class' => 't4'],
                        ['label' => '4 IKHLAS', 'class' => 't4'],
                    ],
                ],
                [
                    'name' => 'Aras 1',
                    'grid' => 'grid-1',
                    'rooms' => [
                        ['label' => 'PERPUSTAKAAN AL-GHAZALI', 'class' => 'library'],
                    ],
                ],
                [
                    'name' => 'Aras Bawah',
                    'grid' => 'grid-7',
                    'rooms' => [
                        ['label' => 'BILIK DISIPLIN', 'class' => 'special'],
                        ['label' => '5 ITQAN', 'class' => 't5'],
                        ['label' => 'LORONG MAKMAL', 'class' => 'special'],
                        ['label' => 'BILIK PEND. ISLAM', 'class' => 'special'],
                        ['label' => 'GALERI SEJARAH', 'class' => 'special'],
                        ['label' => 'BILIK BAHASA', 'class' => 'special'],
                        ['label' => 'BILIK PERSALINAN', 'class' => 'special'],
                    ],
                ],
            ],
        ],
    ];
}

function smks3_normalize_enrolmen_room(array $room): array
{
    $label = trim((string) ($room['label'] ?? ''));
    $class = trim((string) ($room['class'] ?? 'special')) ?: 'special';
    $parts = preg_split('/\s+/', $class) ?: ['special'];
    $base = (string) ($parts[0] ?? 'special');
    $allowedBase = ['t1', 't2', 't3', 't4', 't5', 'special', 'library'];
    if (!in_array($base, $allowedBase, true)) {
        $base = 'special';
    }
    $extras = [];
    foreach ($parts as $part) {
        if ($part === 'surau') {
            $extras[] = 'surau';
        }
    }
    $final = $base;
    if ($extras !== [] && $base === 'special') {
        $final .= ' ' . implode(' ', array_unique($extras));
    }
    return ['label' => $label !== '' ? $label : '-', 'class' => $final];
}

function smks3_normalize_enrolmen_blok(array $blok, array $default): array
{
    $out = [
        'title' => trim((string) ($blok['title'] ?? $default['title'] ?? 'Blok')) ?: (string) ($default['title'] ?? 'Blok'),
        'floors' => [],
    ];
    $floors = is_array($blok['floors'] ?? null) ? $blok['floors'] : ($default['floors'] ?? []);
    foreach ($floors as $i => $floor) {
        if (!is_array($floor)) {
            continue;
        }
        $defFloor = is_array($default['floors'][$i] ?? null) ? $default['floors'][$i] : [];
        $rooms = [];
        $rawRooms = is_array($floor['rooms'] ?? null) ? $floor['rooms'] : ($defFloor['rooms'] ?? []);
        foreach ($rawRooms as $room) {
            if (is_array($room)) {
                $rooms[] = smks3_normalize_enrolmen_room($room);
            }
        }
        $grid = trim((string) ($floor['grid'] ?? $defFloor['grid'] ?? 'grid-7')) ?: 'grid-7';
        if (!in_array($grid, ['grid-7', 'grid-4', 'grid-3', 'grid-1'], true)) {
            $grid = 'grid-7';
        }
        $out['floors'][] = [
            'name' => trim((string) ($floor['name'] ?? $defFloor['name'] ?? 'Aras')) ?: 'Aras',
            'grid' => $grid,
            'rooms' => $rooms,
        ];
    }
    return $out;
}

function smks3_get_enrolmen_content(): array
{
    $defaults = smks3_default_enrolmen_content();
    $stored = smks3_get_json_content('enrolmen_murid_content', []);
    if (!is_array($stored) || $stored === []) {
        return $defaults;
    }

    $feb = is_array($stored['feb'] ?? null) ? $stored['feb'] : [];
    $defaults['feb']['title'] = trim((string) ($feb['title'] ?? $defaults['feb']['title'])) ?: $defaults['feb']['title'];
    $img = trim((string) ($feb['image'] ?? ''));
    if ($img !== '') {
        $defaults['feb']['image'] = $img;
    }

    $summaryTitle = trim((string) ($stored['summary_title'] ?? ''));
    if ($summaryTitle !== '') {
        $defaults['summary_title'] = $summaryTitle;
    }
    if (isset($stored['summary'])) {
        if (is_string($stored['summary'])) {
            $defaults['summary'] = smks3_parse_lines_list($stored['summary']);
        } elseif (is_array($stored['summary'])) {
            $list = array_values(array_filter(array_map(
                static fn($v) => trim((string) $v),
                $stored['summary']
            ), static fn($v) => $v !== ''));
            if ($list !== []) {
                $defaults['summary'] = $list;
            }
        }
    }

    foreach (['blok_a', 'blok_b'] as $key) {
        if (isset($stored[$key]) && is_array($stored[$key])) {
            $defaults[$key] = smks3_normalize_enrolmen_blok($stored[$key], $defaults[$key]);
        }
    }

    return $defaults;
}

function smks3_save_enrolmen_content(array $content): bool
{
    $defaults = smks3_default_enrolmen_content();
    $payload = [
        'feb' => [
            'title' => trim((string) ($content['feb']['title'] ?? $defaults['feb']['title'])) ?: $defaults['feb']['title'],
            'image' => trim((string) ($content['feb']['image'] ?? $defaults['feb']['image'])) ?: $defaults['feb']['image'],
        ],
        'summary_title' => trim((string) ($content['summary_title'] ?? $defaults['summary_title'])) ?: $defaults['summary_title'],
        'summary' => [],
        'blok_a' => smks3_normalize_enrolmen_blok(
            is_array($content['blok_a'] ?? null) ? $content['blok_a'] : $defaults['blok_a'],
            $defaults['blok_a']
        ),
        'blok_b' => smks3_normalize_enrolmen_blok(
            is_array($content['blok_b'] ?? null) ? $content['blok_b'] : $defaults['blok_b'],
            $defaults['blok_b']
        ),
    ];
    $summary = $content['summary'] ?? $defaults['summary'];
    if (is_string($summary)) {
        $summary = smks3_parse_lines_list($summary);
    }
    if (!is_array($summary)) {
        $summary = $defaults['summary'];
    }
    $payload['summary'] = array_values(array_filter(array_map(
        static fn($v) => trim((string) $v),
        $summary
    ), static fn($v) => $v !== ''));
    if ($payload['summary'] === []) {
        $payload['summary'] = $defaults['summary'];
    }

    return smks3_save_site_content(
        'enrolmen_murid_content',
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}'
    );
}

function smks3_enrolmen_img_src(string $path): string
{
    $path = trim($path);
    if ($path === '') {
        return '';
    }
    if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'uploads/') || str_starts_with($path, 'images/')) {
        return $path;
    }
    return 'uploads/enrolmen/' . ltrim($path, '/');
}
