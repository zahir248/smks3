<?php

declare(strict_types=1);

/**
 * Role-based access: units → admins → page/footer edit permissions.
 */

function smks3_is_superadmin(): bool
{
    return smks3_editor_role() === 'superadmin';
}

function smks3_ensure_rbac_schema(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS units (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                slug VARCHAR(160) NOT NULL UNIQUE,
                description VARCHAR(255) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS unit_permissions (
                unit_id INT NOT NULL,
                permission_key VARCHAR(120) NOT NULL,
                PRIMARY KEY (unit_id, permission_key),
                CONSTRAINT fk_unit_permissions_unit
                    FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
        $col = $pdo->query("SHOW COLUMNS FROM users LIKE 'unit_id'")->fetch(PDO::FETCH_ASSOC);
        if (!$col) {
            $pdo->exec('ALTER TABLE users ADD COLUMN unit_id INT NULL DEFAULT NULL');
            try {
                $pdo->exec(
                    'ALTER TABLE users
                     ADD CONSTRAINT fk_users_unit
                     FOREIGN KEY (unit_id) REFERENCES units(id) ON DELETE SET NULL'
                );
            } catch (Throwable $e) {
                // ignore if FK already exists / engine limits
            }
        }
        smks3_ensure_users_edit_preview_column($pdo);
        smks3_ensure_users_is_active_column($pdo);
    } catch (Throwable $e) {
        // schema best-effort
    }
}

/**
 * Editable areas superadmin can assign to a unit.
 *
 * @return array<string, array{label: string, group: string}>
 */
function smks3_rbac_permission_catalog(): array
{
    return [
        'home' => ['label' => 'Laman Utama', 'group' => 'Umum'],
        'footer' => ['label' => 'Footer laman', 'group' => 'Umum'],
        'news' => ['label' => 'Berita', 'group' => 'Umum'],
        'contact' => ['label' => 'Hubungi', 'group' => 'Umum'],

        'profil-sekolah' => ['label' => 'Profil Sekolah', 'group' => 'Pengurusan Dan Pentadbiran'],
        'misi-visi-sekolah' => ['label' => 'FPK, Visi, Misi, Motto', 'group' => 'Pengurusan Dan Pentadbiran'],
        'sejarah-sekolah' => ['label' => 'Sejarah Sekolah', 'group' => 'Pengurusan Dan Pentadbiran'],
        'senarai-pengetua' => ['label' => 'Senarai Pengetua', 'group' => 'Pengurusan Dan Pentadbiran'],
        'pelan-sekolah' => ['label' => 'Pelan Sekolah', 'group' => 'Pengurusan Dan Pentadbiran'],
        'lencana-lagu-sekolah' => ['label' => 'Lencana & Lagu Sekolah', 'group' => 'Pengurusan Dan Pentadbiran'],
        'pengurusan-tertinggi' => ['label' => 'Pengurusan Tertinggi', 'group' => 'Pengurusan Dan Pentadbiran'],
        'guru-apk' => ['label' => 'Barisan Guru Dan AKP', 'group' => 'Pengurusan Dan Pentadbiran'],
        'kalendar-akademik' => ['label' => 'Kalendar Akademik', 'group' => 'Pengurusan Dan Pentadbiran'],
        'cuti-perayaan' => ['label' => 'Cuti Perayaan', 'group' => 'Pengurusan Dan Pentadbiran'],

        'pentaksiran-peperiksaan' => ['label' => 'Pentaksiran Dan Peperiksaan', 'group' => 'Kurikulum'],
        'pusat-sumber' => ['label' => 'Pusat Sumber', 'group' => 'Kurikulum'],
        'pra-sekolah' => ['label' => 'Pra Sekolah', 'group' => 'Kurikulum'],
        'kecemerlangan-program-akademik' => ['label' => 'Program Kecemerlangan Akademik', 'group' => 'Kurikulum'],
        'pilihan-mata-pelajaran' => ['label' => 'Pilihan Mata Pelajaran', 'group' => 'Kurikulum'],

        'enrolmen-murid' => ['label' => 'Enrolmen Murid', 'group' => 'Hal Ehwal Murid'],
        'bil-kelas-gambar' => ['label' => 'Bilangan Kelas', 'group' => 'Hal Ehwal Murid'],
        'unit-bimbingan-kaunseling' => ['label' => 'Unit Bimbingan Dan Kaunseling', 'group' => 'Hal Ehwal Murid'],
        'peraturan-sekolah' => ['label' => 'Peraturan Sekolah', 'group' => 'Hal Ehwal Murid'],
        'pemimpin-murid' => ['label' => 'Pemimpin Murid', 'group' => 'Hal Ehwal Murid'],

        'jawatankuasa-pibg' => ['label' => 'Jawatankuasa PIBG', 'group' => 'PIBG'],
    ];
}

/**
 * Sub-pages under Pentaksiran share that permission (navbar has 5 Kurikulum items only).
 *
 * @return array<string, string>
 */
function smks3_rbac_permission_aliases(): array
{
    return [
        'analisis-pat-t4-uasa-t1,2,3' => 'pentaksiran-peperiksaan',
        'analisis-ppt' => 'pentaksiran-peperiksaan',
        'bank-soalan-uasa-ppt-pat-selaras' => 'pentaksiran-peperiksaan',
        'keputusan' => 'pentaksiran-peperiksaan',
        'penggubal-soalan-upsa-uasa' => 'pentaksiran-peperiksaan',
    ];
}

function smks3_rbac_canonical_permission(string $permissionKey): string
{
    $permissionKey = trim($permissionKey);
    $aliases = smks3_rbac_permission_aliases();
    return $aliases[$permissionKey] ?? $permissionKey;
}

/** Map CMS block name → permission key. */
function smks3_rbac_permission_for_block(string $block): ?string
{
    $block = trim($block);
    if ($block === '') {
        return null;
    }

    if (str_starts_with($block, 'footer_')) {
        return 'footer';
    }

    $homeBlocks = array_merge(
        array_keys(function_exists('smks3_default_home_content') ? smks3_default_home_content() : []),
        ['school_info', 'sidebar_title', 'quick_link', 'quick_link_add', 'quick_link_delete', 'slideshow_slide', 'slideshow_add', 'slideshow_delete', 'news_hub']
    );
    if (in_array($block, $homeBlocks, true)) {
        return 'home';
    }

    if (str_starts_with($block, 'news_')) {
        return 'news';
    }

    /** @var array<string, string> $map */
    static $map = [
        'profil_item' => 'profil-sekolah',
        'profil_item_add' => 'profil-sekolah',
        'profil_item_delete' => 'profil-sekolah',
        'fpk_item' => 'misi-visi-sekolah',
        'fpk_falsafah' => 'misi-visi-sekolah',
        'fpk_add' => 'misi-visi-sekolah',
        'fpk_delete' => 'misi-visi-sekolah',
        'sejarah_item' => 'sejarah-sekolah',
        'sejarah_add' => 'sejarah-sekolah',
        'sejarah_delete' => 'sejarah-sekolah',
        'pengetua_item' => 'senarai-pengetua',
        'pengetua_add' => 'senarai-pengetua',
        'pengetua_delete' => 'senarai-pengetua',
        'pelan_image' => 'pelan-sekolah',
        'lencana_main' => 'lencana-lagu-sekolah',
        'lencana_moto' => 'lencana-lagu-sekolah',
        'lencana_lagu' => 'lencana-lagu-sekolah',
        'lencana_item' => 'lencana-lagu-sekolah',
        'lencana_item_add' => 'lencana-lagu-sekolah',
        'lencana_item_delete' => 'lencana-lagu-sekolah',
        'pengurusan_item' => 'pengurusan-tertinggi',
        'pengurusan_add' => 'pengurusan-tertinggi',
        'pengurusan_delete' => 'pengurusan-tertinggi',
        'guru_item' => 'guru-apk',
        'guru_add' => 'guru-apk',
        'guru_delete' => 'guru-apk',
        'akp_item' => 'guru-apk',
        'akp_add' => 'guru-apk',
        'akp_delete' => 'guru-apk',
        'kalendar_title' => 'kalendar-akademik',
        'kalendar_table' => 'kalendar-akademik',
        'kalendar_page' => 'kalendar-akademik',
        'kalendar_cell' => 'kalendar-akademik',
        'kalendar_pdf_add' => 'kalendar-akademik',
        'editable_table' => 'kalendar-akademik',
        'table_cell' => 'kalendar-akademik',
        'cuti_kumpulan' => 'cuti-perayaan',
        'cuti_pdf_add' => 'cuti-perayaan',
        'list_item' => 'cuti-perayaan',
        'kurikulum_meta' => 'pentaksiran-peperiksaan',
        'kurikulum_section' => 'pentaksiran-peperiksaan',
        'kurikulum_card' => 'pentaksiran-peperiksaan',
        'kurikulum_card_add' => 'pentaksiran-peperiksaan',
        'kurikulum_card_delete' => 'pentaksiran-peperiksaan',
        'pra_sekolah' => 'pra-sekolah',
        'pra_sekolah_carta' => 'pra-sekolah',
        'pra_sekolah_galeri' => 'pra-sekolah',
        'pilihan_pdf_add' => 'pilihan-mata-pelajaran',
        'enrolmen_add' => 'enrolmen-murid',
        'enrolmen_item' => 'enrolmen-murid',
        'enrolmen_delete' => 'enrolmen-murid',
        'enrolmen_feb' => 'enrolmen-murid',
        'enrolmen_summary' => 'enrolmen-murid',
        'enrolmen_blok' => 'enrolmen-murid',
        'enrolmen_floor' => 'enrolmen-murid',
        'bil_kelas_add' => 'bil-kelas-gambar',
        'bil_kelas_item' => 'bil-kelas-gambar',
        'bil_kelas_delete' => 'bil-kelas-gambar',
        'ubk_pengenalan' => 'unit-bimbingan-kaunseling',
        'ubk_visi' => 'unit-bimbingan-kaunseling',
        'ubk_misi' => 'unit-bimbingan-kaunseling',
        'ubk_falsafah' => 'unit-bimbingan-kaunseling',
        'ubk_objektif' => 'unit-bimbingan-kaunseling',
        'ubk_fungsi' => 'unit-bimbingan-kaunseling',
        'ubk_aktiviti' => 'unit-bimbingan-kaunseling',
        'ubk_carta_image' => 'unit-bimbingan-kaunseling',
        'ubk_pamplet1' => 'unit-bimbingan-kaunseling',
        'ubk_pamplet2' => 'unit-bimbingan-kaunseling',
        'peraturan_add' => 'peraturan-sekolah',
        'peraturan_delete' => 'peraturan-sekolah',
        'pemimpin_add' => 'pemimpin-murid',
        'pemimpin_delete' => 'pemimpin-murid',
        'pibg_meta' => 'jawatankuasa-pibg',
        'pibg_pdf' => 'jawatankuasa-pibg',
        'html_text' => 'home',
        'editable_html' => 'home',
    ];

    if (isset($map[$block])) {
        return $map[$block];
    }

    // Kurikulum cards send page_key — handled by caller with data.
    if (str_starts_with($block, 'kurikulum_')) {
        return null; // resolve via page_key in request
    }

    $catalog = smks3_rbac_permission_catalog();
    if (isset($catalog[$block])) {
        return $block;
    }

    return null;
}

function smks3_current_user_unit_id(): ?int
{
    smks3_ensure_session();
    try {
        $pdo = getConnection();
        smks3_ensure_rbac_schema($pdo);
        $username = (string) ($_SESSION['username'] ?? '');
        if ($username === '') {
            $_SESSION['unit_id'] = null;
            $_SESSION['unit_name'] = null;
            return null;
        }
        $stmt = $pdo->prepare(
            'SELECT us.unit_id, u.name AS unit_name
             FROM users us
             LEFT JOIN units u ON u.id = us.unit_id
             WHERE us.username = ? LIMIT 1'
        );
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || $row['unit_id'] === null || $row['unit_id'] === '') {
            $_SESSION['unit_id'] = null;
            $_SESSION['unit_name'] = null;
            return null;
        }
        $_SESSION['unit_id'] = (int) $row['unit_id'];
        $_SESSION['unit_name'] = trim((string) ($row['unit_name'] ?? '')) ?: null;
        return $_SESSION['unit_id'];
    } catch (Throwable $e) {
        $_SESSION['unit_id'] = null;
        $_SESSION['unit_name'] = null;
        return null;
    }
}

/** Unit name for the logged-in admin, or null. */
function smks3_current_user_unit_name(): ?string
{
    smks3_ensure_session();
    if (array_key_exists('unit_name', $_SESSION) && array_key_exists('unit_id', $_SESSION)) {
        $name = $_SESSION['unit_name'];
        return is_string($name) && $name !== '' ? $name : null;
    }
    smks3_current_user_unit_id();
    $name = $_SESSION['unit_name'] ?? null;
    return is_string($name) && $name !== '' ? $name : null;
}

/** @return list<string> */
function smks3_current_user_permissions(): array
{
    if (smks3_is_superadmin()) {
        return array_keys(smks3_rbac_permission_catalog());
    }
    $unitId = smks3_current_user_unit_id();
    if (!$unitId) {
        return [];
    }
    try {
        $pdo = getConnection();
        smks3_ensure_rbac_schema($pdo);
        $stmt = $pdo->prepare('SELECT permission_key FROM unit_permissions WHERE unit_id = ?');
        $stmt->execute([$unitId]);
        return array_values(array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []));
    } catch (Throwable $e) {
        return [];
    }
}

function smks3_rbac_refresh_session_permissions(): void
{
    smks3_ensure_session();
    unset($_SESSION['rbac_permissions'], $_SESSION['unit_id'], $_SESSION['unit_name']);
    smks3_current_user_unit_id();
    smks3_current_user_permissions();
}

/**
 * Labels for pages the current admin may edit, grouped by catalog group.
 *
 * @return array<string, list<string>>
 */
function smks3_current_user_permission_labels(): array
{
    $catalog = smks3_rbac_permission_catalog();
    $grouped = [];
    $seen = [];
    foreach (smks3_current_user_permissions() as $key) {
        $canonical = smks3_rbac_canonical_permission((string) $key);
        if ($canonical === '' || isset($seen[$canonical]) || !isset($catalog[$canonical])) {
            continue;
        }
        $seen[$canonical] = true;
        $meta = $catalog[$canonical];
        $group = (string) ($meta['group'] ?? 'Lain');
        $grouped[$group][] = (string) ($meta['label'] ?? $canonical);
    }
    return $grouped;
}

function smks3_user_has_permission(string $permissionKey): bool
{
    if (!smks3_is_editor()) {
        return false;
    }
    if (smks3_is_superadmin()) {
        return true;
    }
    $permissionKey = trim($permissionKey);
    if ($permissionKey === '') {
        return false;
    }
    $perms = smks3_current_user_permissions();
    if (in_array($permissionKey, $perms, true)) {
        return true;
    }
    // Sub-pages (e.g. Analisis PPT) inherit parent permission (Pentaksiran Dan Peperiksaan)
    $canonical = smks3_rbac_canonical_permission($permissionKey);
    if ($canonical !== $permissionKey && in_array($canonical, $perms, true)) {
        return true;
    }
    return false;
}

function smks3_can_edit_page(?string $pageKey = null): bool
{
    if (!smks3_is_editor()) {
        return false;
    }
    if (smks3_is_superadmin()) {
        return true;
    }
    $pageKey = $pageKey ?? (function_exists('smks3_current_route') ? smks3_current_route() : 'index');
    if ($pageKey === 'index' || $pageKey === '' || $pageKey === '/') {
        return smks3_user_has_permission('home');
    }
    if ($pageKey === 'news-details' || $pageKey === 'buletin-sekolah') {
        return smks3_user_has_permission('news');
    }
    return smks3_user_has_permission($pageKey);
}

function smks3_can_edit_footer(): bool
{
    return smks3_user_has_permission('footer');
}

function smks3_can_edit_block(string $block, array $data = []): bool
{
    if (!smks3_is_editor()) {
        return false;
    }
    if (smks3_is_superadmin()) {
        return true;
    }
    if (str_starts_with($block, 'kurikulum_')) {
        $pageKey = trim((string) ($data['page_key'] ?? ''));
        if ($pageKey !== '') {
            return smks3_user_has_permission($pageKey);
        }
    }
    $key = smks3_rbac_permission_for_block($block);
    if ($key === null) {
        // Unknown block: deny for non-superadmin
        return false;
    }
    // table_cell / list_item / editable_table used on multiple pages — allow if any matching page perm
    if (in_array($block, ['table_cell', 'list_item', 'editable_table', 'html_text', 'editable_html'], true)) {
        $pageKey = function_exists('smks3_current_route') ? smks3_current_route() : '';
        if ($pageKey !== '' && smks3_can_edit_page($pageKey)) {
            return true;
        }
        // fall through to mapped default
    }
    return smks3_user_has_permission($key);
}

/** @return list<array<string, mixed>> */
function smks3_rbac_list_units(PDO $pdo): array
{
    smks3_ensure_rbac_schema($pdo);
    $rows = $pdo->query(
        'SELECT u.*,
                (SELECT COUNT(*) FROM users us WHERE us.unit_id = u.id) AS admin_count,
                (SELECT COUNT(*) FROM unit_permissions up WHERE up.unit_id = u.id) AS permission_count
         FROM units u
         ORDER BY u.name ASC'
    )->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}

/**
 * @param list<array<string, mixed>> $items
 * @return array{items: list<array<string, mixed>>, page: int, per_page: int, total: int, total_pages: int}
 */
function smks3_rbac_paginate(array $items, int $page, int $perPage = 4): array
{
    $perPage = max(1, $perPage);
    $total = count($items);
    $totalPages = max(1, (int) ceil($total / $perPage));
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;

    return [
        'items' => array_slice($items, $offset, $perPage),
        'page' => $page,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
    ];
}

/** @return list<string> */
function smks3_rbac_unit_permissions(PDO $pdo, int $unitId): array
{
    smks3_ensure_rbac_schema($pdo);
    $stmt = $pdo->prepare('SELECT permission_key FROM unit_permissions WHERE unit_id = ? ORDER BY permission_key');
    $stmt->execute([$unitId]);
    $raw = array_map('strval', $stmt->fetchAll(PDO::FETCH_COLUMN) ?: []);
    $catalog = smks3_rbac_permission_catalog();
    $out = [];
    foreach ($raw as $key) {
        $canonical = smks3_rbac_canonical_permission($key);
        if (isset($catalog[$canonical])) {
            $out[$canonical] = $canonical;
        }
    }
    return array_values($out);
}

function smks3_rbac_set_unit_permissions(PDO $pdo, int $unitId, array $keys): void
{
    smks3_ensure_rbac_schema($pdo);
    $catalog = smks3_rbac_permission_catalog();
    $pdo->prepare('DELETE FROM unit_permissions WHERE unit_id = ?')->execute([$unitId]);
    $ins = $pdo->prepare('INSERT INTO unit_permissions (unit_id, permission_key) VALUES (?, ?)');
    $seen = [];
    foreach ($keys as $key) {
        $key = smks3_rbac_canonical_permission(trim((string) $key));
        if ($key === '' || !isset($catalog[$key]) || isset($seen[$key])) {
            continue;
        }
        $seen[$key] = true;
        $ins->execute([$unitId, $key]);
    }
}

/** @return list<array<string, mixed>> */
function smks3_rbac_list_admins(PDO $pdo): array
{
    smks3_ensure_rbac_schema($pdo);
    smks3_ensure_users_is_active_column($pdo);
    $rows = $pdo->query(
        "SELECT us.id, us.username, us.role, us.unit_id, us.is_active, u.name AS unit_name
         FROM users us
         LEFT JOIN units u ON u.id = us.unit_id
         WHERE us.role = 'admin' OR (us.role IS NULL OR us.role = '')
         ORDER BY us.username ASC"
    )->fetchAll(PDO::FETCH_ASSOC);
    return is_array($rows) ? $rows : [];
}

function smks3_rbac_make_unit_slug(string $name, PDO $pdo, ?int $ignoreId = null): string
{
    $base = smks3_make_slug($name);
    if ($base === '') {
        $base = 'unit';
    }
    $slug = $base;
    $n = 2;
    while (true) {
        $sql = 'SELECT id FROM units WHERE slug = ?';
        $params = [$slug];
        if ($ignoreId) {
            $sql .= ' AND id <> ?';
            $params[] = $ignoreId;
        }
        $stmt = $pdo->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        if (!$stmt->fetchColumn()) {
            return $slug;
        }
        $slug = $base . '-' . $n;
        $n++;
    }
}
