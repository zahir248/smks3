<?php

declare(strict_types=1);

/**
 * Activity / audit log for admin & superadmin actions.
 */

function smks3_ensure_activity_log_schema(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS activity_log (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                occurred_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                actor_user_id INT NULL,
                actor_username VARCHAR(100) NULL,
                actor_role VARCHAR(32) NULL,
                action VARCHAR(64) NOT NULL,
                entity_type VARCHAR(64) NULL,
                entity_id VARCHAR(64) NULL,
                summary VARCHAR(255) NULL,
                route VARCHAR(160) NULL,
                ip VARCHAR(45) NULL,
                user_agent VARCHAR(255) NULL,
                before_json LONGTEXT NULL,
                after_json LONGTEXT NULL,
                meta_json LONGTEXT NULL,
                INDEX idx_activity_occurred (occurred_at),
                INDEX idx_activity_actor (actor_user_id),
                INDEX idx_activity_action (action),
                INDEX idx_activity_entity (entity_type, entity_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    } catch (Throwable $e) {
        // best-effort
    }
}

/**
 * @param array<string,mixed>|null $before
 * @param array<string,mixed>|null $after
 * @param array<string,mixed>|null $meta
 * @param array{
 *   user_id?:int|null,
 *   username?:string|null,
 *   role?:string|null
 * }|null $actor Override session actor (e.g. just before logout clear).
 */
function smks3_activity_log(
    string $action,
    ?array $before = null,
    ?array $after = null,
    ?string $entityType = null,
    ?string $entityId = null,
    ?string $summary = null,
    ?array $meta = null,
    ?array $actor = null
): void {
    try {
        $pdo = getConnection();
        smks3_ensure_activity_log_schema($pdo);

        $userId = $actor['user_id'] ?? null;
        $username = $actor['username'] ?? null;
        $role = $actor['role'] ?? null;
        if ($userId === null && function_exists('smks3_current_user_id')) {
            $userId = smks3_current_user_id();
        }
        if (($username === null || $username === '') && !empty($_SESSION['username'])) {
            $username = (string) $_SESSION['username'];
        }
        if (($role === null || $role === '') && !empty($_SESSION['role'])) {
            $role = (string) $_SESSION['role'];
        }
        if ($userId === null && !empty($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];
        }

        $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        if (function_exists('mb_substr')) {
            $ua = mb_substr($ua, 0, 255);
        } else {
            $ua = substr($ua, 0, 255);
        }

        $route = function_exists('smks3_current_route') ? smks3_current_route() : null;
        $ip = function_exists('smks3_client_ip') ? smks3_client_ip() : (string) ($_SERVER['REMOTE_ADDR'] ?? '');

        $stmt = $pdo->prepare(
            'INSERT INTO activity_log
                (actor_user_id, actor_username, actor_role, action, entity_type, entity_id, summary, route, ip, user_agent, before_json, after_json, meta_json)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)'
        );
        $stmt->execute([
            $userId !== null && (int) $userId > 0 ? (int) $userId : null,
            $username !== null && $username !== '' ? (string) $username : null,
            $role !== null && $role !== '' ? (string) $role : null,
            $action,
            $entityType,
            $entityId !== null && $entityId !== '' ? (string) $entityId : null,
            $summary !== null && $summary !== '' ? smks3_activity_truncate((string) $summary, 255) : null,
            $route,
            $ip !== '' ? $ip : null,
            $ua !== '' ? $ua : null,
            smks3_activity_encode_json($before),
            smks3_activity_encode_json($after),
            smks3_activity_encode_json($meta),
        ]);
    } catch (Throwable $e) {
        error_log('SMKS3 activity_log: ' . $e->getMessage());
    }
}

function smks3_activity_truncate(string $value, int $max): string
{
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($value) <= $max) {
            return $value;
        }
        return mb_substr($value, 0, max(0, $max - 1)) . '…';
    }
    if (strlen($value) <= $max) {
        return $value;
    }
    return substr($value, 0, max(0, $max - 1)) . '…';
}

/** @param mixed $value */
function smks3_activity_encode_json($value): ?string
{
    if ($value === null) {
        return null;
    }
    try {
        $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return null;
        }
        // Cap payload size (~400KB) to protect DB.
        if (strlen($json) > 400000) {
            $json = json_encode([
                '_truncated' => true,
                '_note' => 'Data terlalu besar; ringkasan sahaja.',
                'keys' => is_array($value) ? array_keys($value) : gettype($value),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return $json === false ? null : $json;
    } catch (Throwable $e) {
        return null;
    }
}

/**
 * Strip secrets / uploads from request payloads used in logs.
 *
 * @param array<string,mixed> $data
 * @return array<string,mixed>
 */
function smks3_activity_sanitize_payload(array $data): array
{
    $deny = [
        'password', 'password_confirmation', 'csrf_token', '_csrf',
        'token', 'current_password', 'new_password',
    ];
    $out = [];
    foreach ($data as $k => $v) {
        $key = (string) $k;
        $lk = strtolower($key);
        if (in_array($lk, $deny, true) || str_contains($lk, 'password')) {
            $out[$key] = '[redacted]';
            continue;
        }
        if (is_array($v)) {
            $out[$key] = smks3_activity_sanitize_payload($v);
            continue;
        }
        if (is_string($v) && strlen($v) > 8000) {
            $out[$key] = smks3_activity_truncate($v, 8000);
            continue;
        }
        $out[$key] = $v;
    }
    return $out;
}

/** Human label for action key. */
function smks3_activity_action_label(string $action): string
{
    $map = [
        'auth.login' => 'Log masuk',
        'auth.logout' => 'Log keluar',
        'auth.logout_idle' => 'Log keluar (tamat masa)',
        'auth.logout_deactivated' => 'Log keluar (akaun dinyahaktif)',
        'content.create' => 'Tambah kandungan',
        'content.update' => 'Kemaskini kandungan',
        'content.delete' => 'Padam kandungan',
        'rbac.unit_create' => 'Cipta unit',
        'rbac.unit_update' => 'Kemaskini unit',
        'rbac.unit_delete' => 'Padam unit',
        'rbac.admin_create' => 'Daftar admin',
        'rbac.admin_update' => 'Kemaskini admin',
        'rbac.admin_delete' => 'Padam admin',
        'rbac.admin_set_active' => 'Status admin',
        'rbac.admin_permissions' => 'Kebenaran admin',
        'rbac.site_setting' => 'Tetapan laman',
        'rbac.media_trash_purge' => 'Padam kekal fail sampah',
        'rbac.media_trash_purge_bulk' => 'Padam kekal pukal fail sampah',
    ];
    return $map[$action] ?? $action;
}

/** Admin username affected by rbac.admin_* log entries. */
function smks3_activity_log_target_username(array $row): string
{
    $action = (string) ($row['action'] ?? '');
    if ($action === '' || !str_starts_with($action, 'rbac.admin_')) {
        return '';
    }
    $metaJson = (string) ($row['meta_json'] ?? '');
    if ($metaJson !== '' && $metaJson !== 'null') {
        $meta = json_decode($metaJson, true);
        if (is_array($meta)) {
            $fromMeta = trim((string) ($meta['username'] ?? ''));
            if ($fromMeta !== '') {
                return $fromMeta;
            }
        }
    }
    $summary = (string) ($row['summary'] ?? '');
    if ($summary !== '' && preg_match('/:\s*(.+)$/u', $summary, $m)) {
        return trim((string) ($m[1] ?? ''));
    }
    return '';
}

function smks3_activity_content_op(string $block): string
{
    if (str_ends_with($block, '_add') || str_ends_with($block, '_item_add')) {
        return 'content.create';
    }
    if (str_ends_with($block, '_delete') || str_ends_with($block, '_item_delete')) {
        return 'content.delete';
    }
    return 'content.update';
}

/**
 * Normalize filename-only / JSON gallery values to public relative paths for logs.
 *
 * @return list<string>
 */
function smks3_activity_media_paths(mixed $raw, string $dir): array
{
    $files = function_exists('smks3_news_parse_images') ? smks3_news_parse_images($raw) : [];
    $dir = trim(str_replace('\\', '/', $dir), '/');
    $out = [];
    foreach ($files as $f) {
        $f = str_replace('\\', '/', trim((string) $f));
        if ($f === '') {
            continue;
        }
        if (preg_match('#^(https?:)?//#i', $f)
            || str_starts_with($f, 'uploads/')
            || str_starts_with($f, 'images/')
            || str_starts_with($f, 'files/')) {
            $out[] = $f;
            continue;
        }
        // basename-only (or nested junk) → prefix with module dir
        $base = basename($f);
        if ($base === '' || str_contains($base, '..')) {
            continue;
        }
        $out[] = ($dir !== '' ? $dir . '/' : '') . $base;
    }
    return array_values(array_unique($out));
}

/**
 * Prefix a single stored path/filename.
 */
function smks3_activity_media_path(mixed $raw, string $dir): string
{
    $paths = smks3_activity_media_paths($raw, $dir);
    return $paths[0] ?? '';
}

/**
 * @param list<array<string,mixed>> $rows
 * @return list<array<string,mixed>>
 */
function smks3_activity_prefix_row_field(array $rows, string $field, string $dir): array
{
    foreach ($rows as &$row) {
        if (!is_array($row) || !array_key_exists($field, $row)) {
            continue;
        }
        $paths = smks3_activity_media_paths($row[$field] ?? null, $dir);
        $row[$field] = count($paths) > 1 ? $paths : ($paths[0] ?? '');
    }
    unset($row);
    return $rows;
}

/**
 * Normalize media columns on a single DB row for logging.
 *
 * @param array<string,mixed> $row
 * @return array<string,mixed>
 */
function smks3_activity_normalize_entity_row(string $entity, array $row): array
{
    switch ($entity) {
        case 'news':
            $row['image'] = smks3_activity_media_paths($row['image'] ?? null, 'uploads');
            $row['pdf_file'] = smks3_activity_media_paths($row['pdf_file'] ?? null, 'uploads/pdf');
            unset($row['image_count'], $row['pdf_count']);
            break;
        case 'guru':
        case 'akp':
            $row['image'] = smks3_activity_media_path($row['image'] ?? null, 'uploads');
            break;
        case 'pengetua':
            $row['photo'] = smks3_activity_media_path($row['photo'] ?? null, 'uploads/pengetua');
            break;
        case 'pengurusan':
            $row['gambar'] = smks3_activity_media_path($row['gambar'] ?? null, 'uploads/pengurusan');
            break;
        case 'lencana':
            $img = trim((string) ($row['image'] ?? ''));
            $row['image'] = $img !== '' ? smks3_activity_media_path($img, 'images') : '';
            break;
        default:
            break;
    }
    return $row;
}

/**
 * Capture current stored state for a CMS block (before or after write).
 *
 * @param array<string,mixed> $data
 * @return array<string,mixed>|null
 */
function smks3_activity_snapshot_block(string $block, array $data, PDO $pdo): ?array
{
    try {
        $id = (int) ($data['id'] ?? 0);
        $pageKey = trim((string) ($data['page_key'] ?? $data['key'] ?? ''));

        // Settings / school info
        if ($block === 'school_info') {
            $s = getSettings();
            return [
                'school_name' => (string) ($s['school_name'] ?? ''),
                'address' => (string) ($s['address'] ?? ''),
                'phone' => (string) ($s['phone'] ?? ''),
                'email' => (string) ($s['email'] ?? ''),
            ];
        }

        // Quick links list
        if ($block === 'quick_link' || $block === 'quick_link_add' || $block === 'quick_link_delete') {
            $links = smks3_get_quick_links();
            $index = (int) ($data['index'] ?? -1);
            return [
                'index' => $index,
                'item' => $index >= 0 && isset($links[$index]) ? $links[$index] : null,
                'all' => $links,
            ];
        }

        if ($block === 'slideshow_gallery') {
            return ['slides' => smks3_get_slideshow()];
        }

        if ($block === 'fpk_item' || $block === 'fpk_add' || $block === 'fpk_delete' || $block === 'fpk_falsafah') {
            if ($block === 'fpk_falsafah') {
                return smks3_get_fpk_falsafah();
            }
            if ($id > 0) {
                $stmt = $pdo->prepare('SELECT * FROM fpk_misi_visi WHERE id = ? LIMIT 1');
                $stmt->execute([$id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                return $row ?: null;
            }
            return null;
        }

        // Footer / layout
        if (in_array($block, ['footer_about', 'footer_contact', 'footer_social', 'footer_copyright'], true)) {
            $layout = smks3_get_layout_content();
            return ['footer' => $layout['footer'] ?? $layout];
        }

        // Row entities by prefix
        $rowMap = [
            'news' => 'news',
            'pengetua' => 'pengetua',
            'pengurusan' => 'pengurusan',
            'sejarah' => 'sejarah_sekolah',
            'guru' => 'guru',
            'akp' => 'akp',
            'lencana_item' => 'lencana_item',
            'profil_item' => 'profil_item',
            'kurikulum_card' => 'kurikulum_card',
        ];
        foreach ($rowMap as $prefix => $table) {
            if ($block === $prefix . '_item' || $block === $prefix . '_add' || $block === $prefix . '_delete'
                || $block === $prefix . '_item_add' || $block === $prefix . '_item_delete') {
                if ($id > 0) {
                    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE id = ? LIMIT 1");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$row) {
                        return null;
                    }
                    return smks3_activity_normalize_entity_row($prefix === 'lencana_item' ? 'lencana_item' : $prefix, $row);
                }
                return null;
            }
        }

        // PDF galleries — filename-only columns
        $pdfGalleries = [
            'kalendar_pdf_gallery' => [
                'table' => 'academic_calendar',
                'col' => 'file_pdf',
                'dir' => 'uploads/kalendar',
            ],
            'cuti_pdf_gallery' => [
                'table' => 'cuti_perayaan',
                'col' => 'file_pdf',
                'dir' => 'uploads/cuti_perayaan',
            ],
            'pilihan_pdf_gallery' => [
                'table' => 'pilihan_mata_pelajaran',
                'col' => 'file_pdf',
                'dir' => 'uploads/pilihan_mata_pelajaran',
            ],
        ];
        if (isset($pdfGalleries[$block])) {
            $cfg = $pdfGalleries[$block];
            $rows = $pdo->query(
                "SELECT id, {$cfg['col']} AS file, title FROM {$cfg['table']} ORDER BY id"
            )->fetchAll(PDO::FETCH_ASSOC) ?: [];
            foreach ($rows as &$r) {
                $r['file'] = smks3_activity_media_path($r['file'] ?? null, $cfg['dir']);
            }
            unset($r);
            return ['files' => $rows];
        }

        if ($block === 'pibg_meta' || $block === 'pibg_pdf' || $block === 'pibg_pdf_gallery') {
            $pibg = smks3_get_pibg_content();
            $pdfs = [];
            foreach (($pibg['pdfs'] ?? []) as $p) {
                $src = function_exists('smks3_pibg_pdf_src') ? smks3_pibg_pdf_src((string) $p) : (string) $p;
                if ($src !== '') {
                    $pdfs[] = $src;
                }
            }
            $pibg['pdfs'] = $pdfs;
            return $pibg;
        }

        if ($block === 'enrolmen_gallery') {
            $rows = $pdo->query(
                'SELECT id, title, image, sort_order FROM enrolmen_murid ORDER BY sort_order ASC, id ASC'
            )->fetchAll(PDO::FETCH_ASSOC) ?: [];
            return [
                'rows' => smks3_activity_prefix_row_field($rows, 'image', 'uploads/enrolmen'),
            ];
        }

        if ($block === 'enrolmen_summary' || $block === 'enrolmen_blok' || $block === 'enrolmen_floor') {
            $content = smks3_get_enrolmen_content();
            if (!empty($content['feb']['image'])) {
                $content['feb']['image'] = smks3_activity_media_path($content['feb']['image'], 'uploads/enrolmen');
            }
            return $content;
        }

        if ($block === 'bil_kelas_gallery' || $block === 'bil_kelas_add') {
            $ting = trim((string) ($data['tingkatan'] ?? ''));
            $sql = 'SELECT * FROM bilangan_kelas';
            $params = [];
            if ($ting !== '') {
                $sql .= ' WHERE tingkatan = ?';
                $params[] = $ting;
            }
            $sql .= ' ORDER BY sort_order, item_sort, id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            return [
                'tingkatan' => $ting,
                'rows' => smks3_activity_prefix_row_field($rows, 'image', 'uploads/bil_kelas'),
            ];
        }

        if ($block === 'peraturan_gallery' || $block === 'pemimpin_gallery') {
            $table = $block === 'peraturan_gallery' ? 'peraturan_sekolah' : 'pemimpin_murid';
            $dir = $block === 'peraturan_gallery' ? 'uploads/peraturan' : 'uploads/pemimpin_murid';
            $rows = $pdo->query("SELECT * FROM {$table} ORDER BY id")->fetchAll(PDO::FETCH_ASSOC) ?: [];
            return [
                'rows' => smks3_activity_prefix_row_field($rows, 'image', $dir),
            ];
        }

        if ($block === 'pelan_image') {
            $row = $pdo->query('SELECT * FROM pelan_sekolah LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: null;
            if (!$row) {
                return null;
            }
            $paths = smks3_activity_media_paths($row['image'] ?? null, 'images/pelan-sekolah');
            return [
                'image' => $paths,
            ];
        }

        if (in_array($block, ['pra_sekolah', 'pra_sekolah_carta', 'pra_sekolah_galeri'], true)) {
            $row = $pdo->query('SELECT * FROM pra_sekolah LIMIT 1')->fetch(PDO::FETCH_ASSOC) ?: [];
            return [
                'gambar_carta' => smks3_activity_media_paths($row['gambar_carta'] ?? null, 'uploads/pra_sekolah'),
                'gambar_galeri' => smks3_activity_media_paths($row['gambar_galeri'] ?? null, 'uploads/pra_sekolah'),
            ];
        }

        if (in_array($block, ['lencana_main', 'lencana_moto', 'lencana_lagu'], true)) {
            $row = $pdo->query('SELECT * FROM lencana_lagu_sekolah WHERE id = 1')->fetch(PDO::FETCH_ASSOC) ?: null;
            if (!$row) {
                return null;
            }
            return smks3_activity_normalize_entity_row('lencana', $row);
        }

        if ($block === 'editable_table' || $block === 'editable_html' || $block === 'kalendar_title'
            || $block === 'kalendar_table' || $block === 'kalendar_page') {
            $key = $pageKey !== '' ? $pageKey : (string) ($data['page_key'] ?? '');
            if ($block === 'kalendar_title' || $block === 'kalendar_table' || $block === 'kalendar_page') {
                $key = 'kalendar_akademik';
            }
            if ($key === '') {
                return null;
            }
            if ($block === 'editable_html') {
                return ['key' => $key, 'html' => smks3_get_html_content($key, '')];
            }
            $stmt = $pdo->prepare('SELECT page_key, title, content FROM pages WHERE page_key = ? LIMIT 1');
            $stmt->execute([$key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: ['page_key' => $key, 'title' => '', 'content' => ''];
        }

        if ($block === 'cuti_kumpulan') {
            return smks3_get_cuti_kumpulan();
        }

        if ($block === 'kurikulum_meta' || $block === 'kurikulum_section') {
            $pk = $pageKey !== '' ? $pageKey : 'kurikulum';
            return smks3_get_kurikulum_meta($pk);
        }

        if (str_starts_with($block, 'ubk_')) {
            $ubk = smks3_get_ubk_content();
            foreach (['carta_image', 'pamplet_images', 'pamplet1_image', 'pamplet2_image'] as $field) {
                if (!array_key_exists($field, $ubk)) {
                    continue;
                }
                $paths = smks3_activity_media_paths($ubk[$field] ?? null, 'uploads/ubk');
                // Keep full paths already under uploads/ubk
                $fixed = [];
                foreach ((array) ($ubk[$field] ?? []) as $p) {
                    $p = str_replace('\\', '/', trim((string) $p));
                    if ($p === '') {
                        continue;
                    }
                    if (str_starts_with($p, 'uploads/ubk/') || str_starts_with($p, 'uploads/') || str_starts_with($p, 'images/')) {
                        $fixed[] = $p;
                    } else {
                        $fixed[] = 'uploads/ubk/' . basename($p);
                    }
                }
                if ($fixed === [] && $paths !== []) {
                    $fixed = $paths;
                }
                $ubk[$field] = $fixed;
            }
            return $ubk;
        }

        // Home site_content keys
        $homeKeys = array_keys(smks3_default_home_content());
        if (in_array($block, $homeKeys, true)) {
            $home = smks3_get_home_content();
            return ['key' => $block, 'value' => (string) ($home[$block] ?? '')];
        }

        // Fallback: request-shaped hint only
        return null;
    } catch (Throwable $e) {
        return ['_snapshot_error' => $e->getMessage()];
    }
}

/**
 * @param array<string,mixed> $filters
 * @return array{items:list<array<string,mixed>>,total:int,page:int,per_page:int,pages:int}
 */
function smks3_activity_log_list(PDO $pdo, array $filters = []): array
{
    smks3_ensure_activity_log_schema($pdo);

    $page = max(1, (int) ($filters['page'] ?? 1));
    $perPage = (int) ($filters['per_page'] ?? 50);
    if ($perPage < 5) {
        $perPage = 5;
    }
    if ($perPage > 100) {
        $perPage = 100;
    }

    $where = ['1=1'];
    $params = [];

    $actorId = (int) ($filters['actor_id'] ?? 0);
    if ($actorId > 0) {
        $where[] = 'actor_user_id = ?';
        $params[] = $actorId;
    }

    $relatedUserId = (int) ($filters['related_user_id'] ?? 0);
    if ($relatedUserId > 0) {
        $where[] = '(actor_user_id = ? OR (entity_type = ? AND entity_id = ?))';
        $params[] = $relatedUserId;
        $params[] = 'user';
        $params[] = (string) $relatedUserId;
    }

    $actorQ = trim((string) ($filters['actor'] ?? ''));
    if ($actorQ !== '') {
        $where[] = 'actor_username LIKE ?';
        $params[] = '%' . $actorQ . '%';
    }

    $action = trim((string) ($filters['action'] ?? ''));
    if ($action !== '') {
        if ($action === 'auth') {
            $where[] = "action LIKE 'auth.%'";
        } elseif ($action === 'content') {
            $where[] = "action LIKE 'content.%'";
        } elseif ($action === 'rbac') {
            $where[] = "action LIKE 'rbac.%'";
        } else {
            $where[] = 'action = ?';
            $params[] = $action;
        }
    }

    $q = trim((string) ($filters['q'] ?? ''));
    if ($q !== '') {
        $where[] = '(summary LIKE ? OR entity_type LIKE ? OR entity_id LIKE ? OR action LIKE ? OR actor_username LIKE ?)';
        $like = '%' . $q . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }

    $from = trim((string) ($filters['from'] ?? ''));
    if ($from !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $from)) {
        $where[] = 'occurred_at >= ?';
        $params[] = $from . ' 00:00:00';
    }
    $to = trim((string) ($filters['to'] ?? ''));
    if ($to !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $to)) {
        $where[] = 'occurred_at <= ?';
        $params[] = $to . ' 23:59:59';
    }

    $pageKey = trim((string) ($filters['page_key'] ?? ''));
    if ($pageKey !== '') {
        $catalog = function_exists('smks3_rbac_permission_catalog') ? smks3_rbac_permission_catalog() : [];
        $canonical = function_exists('smks3_rbac_canonical_permission')
            ? smks3_rbac_canonical_permission($pageKey)
            : $pageKey;
        if ($canonical === 'index') {
            $canonical = 'home';
        }
        if ($canonical !== '' && (isset($catalog[$canonical]) || $canonical === 'home')) {
            $matchKeys = [$canonical];
            if (function_exists('smks3_rbac_permission_aliases')) {
                foreach (smks3_rbac_permission_aliases() as $alias => $target) {
                    if ($target === $canonical) {
                        $matchKeys[] = $alias;
                    }
                }
            }
            $matchKeys = array_values(array_unique($matchKeys));

            $ors = [];
            foreach ($matchKeys as $mk) {
                $like = '%"page_key":"' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $mk) . '"%';
                $ors[] = 'meta_json LIKE ?';
                $params[] = $like;
                $ors[] = 'route = ?';
                $params[] = $mk;
            }

            $blockInfo = function_exists('smks3_rbac_blocks_for_permission')
                ? smks3_rbac_blocks_for_permission($canonical)
                : ['blocks' => [], 'prefixes' => []];
            $blocks = $blockInfo['blocks'] ?? [];
            if ($blocks !== []) {
                $placeholders = implode(',', array_fill(0, count($blocks), '?'));
                $ors[] = "entity_type IN ({$placeholders})";
                foreach ($blocks as $b) {
                    $params[] = $b;
                }
            }
            foreach ($blockInfo['prefixes'] ?? [] as $prefix) {
                $ors[] = 'entity_type LIKE ?';
                $params[] = $prefix . '%';
            }

            if ($ors !== []) {
                $where[] = '(' . implode(' OR ', $ors) . ')';
            }
        }
    }

    $sqlWhere = implode(' AND ', $where);
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM activity_log WHERE {$sqlWhere}");
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();
    $pages = max(1, (int) ceil($total / $perPage));
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $perPage;

    $stmt = $pdo->prepare(
        "SELECT id, occurred_at, actor_user_id, actor_username, actor_role, action,
                entity_type, entity_id, summary, route, ip, meta_json,
                (before_json IS NOT NULL AND before_json <> '' AND before_json <> 'null') AS has_before,
                (after_json IS NOT NULL AND after_json <> '' AND after_json <> 'null') AS has_after
         FROM activity_log
         WHERE {$sqlWhere}
         ORDER BY occurred_at DESC, id DESC
         LIMIT {$perPage} OFFSET {$offset}"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $items = [];
    foreach ($rows as $row) {
        $actionKey = (string) ($row['action'] ?? '');
        $targetUsername = smks3_activity_log_target_username($row);
        $items[] = [
            'id' => (int) ($row['id'] ?? 0),
            'occurred_at' => (string) ($row['occurred_at'] ?? ''),
            'actor_user_id' => isset($row['actor_user_id']) ? (int) $row['actor_user_id'] : null,
            'actor_username' => (string) ($row['actor_username'] ?? ''),
            'actor_role' => (string) ($row['actor_role'] ?? ''),
            'action' => $actionKey,
            'action_label' => smks3_activity_action_label($actionKey),
            'entity_type' => (string) ($row['entity_type'] ?? ''),
            'entity_id' => (string) ($row['entity_id'] ?? ''),
            'summary' => (string) ($row['summary'] ?? ''),
            'target_username' => $targetUsername,
            'route' => (string) ($row['route'] ?? ''),
            'ip' => smks3_format_client_ip((string) ($row['ip'] ?? '')),
            'has_before' => !empty($row['has_before']),
            'has_after' => !empty($row['has_after']),
        ];
    }

    return [
        'items' => $items,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'pages' => $pages,
    ];
}

/** Delete all activity log rows. Returns number of deleted rows (best-effort). */
function smks3_activity_log_clear(PDO $pdo): int
{
    smks3_ensure_activity_log_schema($pdo);
    $count = (int) $pdo->query('SELECT COUNT(*) FROM activity_log')->fetchColumn();
    $pdo->exec('DELETE FROM activity_log');
    try {
        $pdo->exec('ALTER TABLE activity_log AUTO_INCREMENT = 1');
    } catch (Throwable $e) {
        // ignore
    }
    return $count;
}

/**
 * @return array<string,mixed>|null
 */
function smks3_activity_log_get(PDO $pdo, int $id): ?array
{
    if ($id < 1) {
        return null;
    }
    smks3_ensure_activity_log_schema($pdo);
    $stmt = $pdo->prepare('SELECT * FROM activity_log WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return null;
    }

    $decode = static function (?string $json) {
        if ($json === null || $json === '' || $json === 'null') {
            return null;
        }
        $decoded = json_decode($json, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $json;
    };

    $actionKey = (string) ($row['action'] ?? '');
    return [
        'id' => (int) ($row['id'] ?? 0),
        'occurred_at' => (string) ($row['occurred_at'] ?? ''),
        'actor_user_id' => isset($row['actor_user_id']) ? (int) $row['actor_user_id'] : null,
        'actor_username' => (string) ($row['actor_username'] ?? ''),
        'actor_role' => (string) ($row['actor_role'] ?? ''),
        'action' => $actionKey,
        'action_label' => smks3_activity_action_label($actionKey),
        'entity_type' => (string) ($row['entity_type'] ?? ''),
        'entity_id' => (string) ($row['entity_id'] ?? ''),
        'summary' => (string) ($row['summary'] ?? ''),
        'target_username' => smks3_activity_log_target_username($row),
        'route' => (string) ($row['route'] ?? ''),
        'ip' => smks3_format_client_ip((string) ($row['ip'] ?? '')),
        'user_agent' => (string) ($row['user_agent'] ?? ''),
        'before' => $decode($row['before_json'] ?? null),
        'after' => $decode($row['after_json'] ?? null),
        'meta' => $decode($row['meta_json'] ?? null),
    ];
}
