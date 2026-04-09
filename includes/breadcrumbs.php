<?php
/**
 * Breadcrumb trails: Laman Utama > menu > submenu (labels match navbar).
 */
declare(strict_types=1);

/** First page in each main menu — used as clickable parent crumb. */
function smks3_breadcrumb_menu_landing_href(string $menuLabel): ?string
{
    static $landing = [
        'Pengurusan Dan Pentadbiran' => 'profil-sekolah.php',
        'Kurikulum' => 'pentaksiran-peperiksaan.php',
        'Hal Ehwal Murid' => 'enrolmen-murid.php',
        'Kokurikulum' => 'unit-badan-beruniform.php',
        'PIBG' => 'kawatankuasa-pibg.php',
    ];
    return $landing[$menuLabel] ?? null;
}

/**
 * @return list<array{label: string, href?: string|null, current?: bool}>
 */
function smks3_default_breadcrumb_items(string $currentPage, string $pageTitle): array
{
    /** @var array<string, array{0: ?string, 1: string}> $map */
    static $map = [
        // Pengurusan Dan Pentadbiran
        'profil-sekolah' => ['Pengurusan Dan Pentadbiran', 'Profil Sekolah'],
        'misi-visi-sekolah' => ['Pengurusan Dan Pentadbiran', 'FPK, Visi, Misi, Motto Sekolah'],
        'sejarah-sekolah' => ['Pengurusan Dan Pentadbiran', 'Sejarah Sekolah'],
        'senarai-pengetua' => ['Pengurusan Dan Pentadbiran', 'Senarai Pengetua'],
        'pelan-sekolah' => ['Pengurusan Dan Pentadbiran', 'Pelan Sekolah'],
        'lencana-lagu-sekolah' => ['Pengurusan Dan Pentadbiran', 'Lencana & Lagu Sekolah'],
        'pengurusan-tertinggi' => ['Pengurusan Dan Pentadbiran', 'Pengurusan Tertinggi Sekolah'],
        'guru-apk' => ['Pengurusan Dan Pentadbiran', 'Barisan Guru Dan AKP'],
        'kalendar-akademik' => ['Pengurusan Dan Pentadbiran', 'Kalendar Akademik 2026'],
        'cuti-perayaan' => ['Pengurusan Dan Pentadbiran', 'Cuti Perayaan 2026'],
        // Kurikulum
        'pentaksiran-peperiksaan' => ['Kurikulum', 'Pentaksiran Dan Peperiksaan'],
        'analisis-pat-t4-uasa-t1,2,3' => ['Kurikulum',['Pentaksiran Dan Peperiksaan', 'pentaksiran-peperiksaan.php'],'Analisis PAT T4 & UASA T1,2,3'],
        'analisis-ppt' => ['Kurikulum',['Pentaksiran Dan Peperiksaan', 'pentaksiran-peperiksaan.php'],'Analisis PPT'],
        'bank-soalan-uasa-ppt-pat-selaras' => ['Kurikulum',['Pentaksiran Dan Peperiksaan', 'pentaksiran-peperiksaan.php'],'Bank Soalan UASA PPT, PAT'],
        'keputusan' => ['Kurikulum',['Pentaksiran Dan Peperiksaan', 'pentaksiran-peperiksaan.php'],'Keputusan 2018-2024'],
        'penggubal-soalan-upsa-uasa' => ['Kurikulum',['Pentaksiran Dan Peperiksaan', 'pentaksiran-peperiksaan.php'],'Penggubal Soalan UPSA & UASA'],
        'pusat-sumber' => ['Kurikulum', 'Pusat Sumber Sekolah'],
        'pra-sekolah' => ['Kurikulum', 'Pra Sekolah'],
        'kecemerlangan-program-akademik' => ['Kurikulum', 'Kecemerlangan Program Akademik'],
        'pilihan-mata-pelajaran' => ['Kurikulum', 'Pilihan Mata Pelajaran'],
        // Hal Ehwal Murid
        'enrolmen-murid' => ['Hal Ehwal Murid', 'Enrolmen Murid'],
        'bil-kelas-gambar' => ['Hal Ehwal Murid', 'Bilangan Kelas-Gambar'],
        'unit-bimbingan-kaunseling' => ['Hal Ehwal Murid', 'Unit Bimbingan Dan Kaunseling'],
        'peraturan-sekolah' => ['Hal Ehwal Murid', 'Peraturan Sekolah'],
        'pemimpin-murid' => ['Hal Ehwal Murid', 'Pemimpin Murid'],
        // Kokurikulum
        'unit-badan-beruniform' => ['Kokurikulum', 'Unit Badan Beruniform'],
        'kelab-persatuan' => ['Kokurikulum', 'Kelab Dan Persatuan'],
        'sukan-permainan' => ['Kokurikulum', 'Sukan Dan Permainan'],
        // PIBG
        'kawatankuasa-pibg' => ['PIBG', 'Jawatankuasa PIBG'],
        // Laman / pautan tambahan (bukan submenu utama)
        'news' => [null, 'Berita'],
        'about' => [null, 'Perihal Kami'],
        'courses' => [null, 'Jurusan'],
        'staff' => [null, 'Guru & Kakitangan'],
        'contact' => [null, 'Hubungi'],
        'buletin-sekolah' => [null, 'Buletin Sekolah'],
    ];

    $items = [
        ['label' => 'Laman Utama', 'href' => 'index.php'],
    ];

    if (isset($map[$currentPage])) {
    $levels = $map[$currentPage];
    $menu = $levels[0] ?? null;

    if ($menu !== null && $menu !== '') {
        $landing = smks3_breadcrumb_menu_landing_href($menu);
        $items[] = ['label' => $menu, 'href' => $landing];
    }

    // Loop semua selepas menu
    for ($i = 1; $i < count($levels); $i++) {
        $level = $levels[$i];

        if (is_array($level)) {
            $label = $level[0];
            $href = $level[1];
        } else {
            $label = $level;
            $href = null;
        }

        $items[] = [
            'label' => $label,
            'href' => $href,
            'current' => $i === count($levels) - 1
        ];
    }

    return $items;
}

    $items[] = ['label' => $pageTitle, 'current' => true];
    return $items;
}
