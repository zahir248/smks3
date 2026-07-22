<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/bootstrap.php';

smks3_ensure_session();

if (!smks3_is_superadmin()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Akses ditolak. Superadmin sahaja.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Kaedah tidak dibenarkan.']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: '', true);
if (!is_array($data)) {
    $data = $_POST;
}

smks3_require_csrf($data);

$action = trim((string) ($data['action'] ?? ''));

try {
    $pdo = getConnection();
    smks3_ensure_rbac_schema($pdo);
    smks3_ensure_activity_log_schema($pdo);

    if ($action === 'activity_log_list') {
        $result = smks3_activity_log_list($pdo, [
            'page' => (int) ($data['page'] ?? 1),
            'per_page' => (int) ($data['per_page'] ?? 50),
            'actor_id' => (int) ($data['actor_id'] ?? 0),
            'related_user_id' => (int) ($data['related_user_id'] ?? 0),
            'actor' => (string) ($data['actor'] ?? ''),
            'action' => (string) ($data['action_filter'] ?? $data['filter'] ?? ''),
            'page_key' => (string) ($data['page_key'] ?? ''),
            'q' => (string) ($data['q'] ?? ''),
            'from' => (string) ($data['from'] ?? ''),
            'to' => (string) ($data['to'] ?? ''),
        ]);
        echo json_encode(['ok' => true] + $result);
        exit;
    }

    if ($action === 'activity_log_get') {
        $id = (int) ($data['id'] ?? 0);
        $row = smks3_activity_log_get($pdo, $id);
        if (!$row) {
            throw new InvalidArgumentException('Log tidak dijumpai.');
        }
        echo json_encode(['ok' => true, 'log' => $row]);
        exit;
    }

    if ($action === 'activity_log_clear') {
        $deleted = smks3_activity_log_clear($pdo);
        echo json_encode([
            'ok' => true,
            'message' => $deleted > 0
                ? ('Log dikosongkan (' . $deleted . ' rekod dipadam).')
                : 'Tiada rekod log untuk dikosongkan.',
            'deleted' => $deleted,
        ]);
        exit;
    }

    if ($action === 'unit_create') {
        $name = trim((string) ($data['name'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        if ($name === '') {
            throw new InvalidArgumentException('Nama unit diperlukan.');
        }
        $slug = smks3_rbac_make_unit_slug($name, $pdo);
        $stmt = $pdo->prepare('INSERT INTO units (name, slug, description) VALUES (?, ?, ?)');
        $stmt->execute([$name, $slug, $description !== '' ? $description : null]);
        $newId = (int) $pdo->lastInsertId();
        $unitPayload = [
            'id' => $newId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description !== '' ? $description : null,
            'admin_count' => 0,
        ];
        smks3_activity_log('rbac.unit_create', null, $unitPayload, 'unit', (string) $newId, 'Unit ditambah: ' . $name);
        echo json_encode([
            'ok' => true,
            'message' => 'Unit ditambah.',
            'unit' => $unitPayload,
        ]);
        exit;
    }

    if ($action === 'unit_update') {
        $id = (int) ($data['id'] ?? 0);
        $name = trim((string) ($data['name'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));
        if ($id < 1 || $name === '') {
            throw new InvalidArgumentException('Data unit tidak sah.');
        }
        $beforeStmt = $pdo->prepare('SELECT id, name, slug, description FROM units WHERE id = ? LIMIT 1');
        $beforeStmt->execute([$id]);
        $before = $beforeStmt->fetch(PDO::FETCH_ASSOC) ?: null;
        $slug = smks3_rbac_make_unit_slug($name, $pdo, $id);
        $stmt = $pdo->prepare('UPDATE units SET name = ?, slug = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $slug, $description !== '' ? $description : null, $id]);
        $after = [
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description !== '' ? $description : null,
        ];
        smks3_activity_log('rbac.unit_update', $before, $after, 'unit', (string) $id, 'Unit dikemaskini: ' . $name);
        echo json_encode(['ok' => true, 'message' => 'Unit dikemaskini.']);
        exit;
    }

    if ($action === 'unit_delete') {
        $id = (int) ($data['id'] ?? 0);
        if ($id < 1) {
            throw new InvalidArgumentException('ID unit tidak sah.');
        }
        $beforeStmt = $pdo->prepare('SELECT id, name, slug, description FROM units WHERE id = ? LIMIT 1');
        $beforeStmt->execute([$id]);
        $before = $beforeStmt->fetch(PDO::FETCH_ASSOC) ?: null;
        $pdo->prepare('UPDATE users SET unit_id = NULL WHERE unit_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM units WHERE id = ?')->execute([$id]);
        smks3_activity_log('rbac.unit_delete', $before, null, 'unit', (string) $id, 'Unit dipadam' . ($before ? ': ' . ($before['name'] ?? '') : ''));
        echo json_encode(['ok' => true, 'message' => 'Unit dipadam.', 'id' => $id]);
        exit;
    }

    if ($action === 'admin_get_permissions') {
        $id = (int) ($data['id'] ?? 0);
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        $check = $pdo->prepare("SELECT id, username, role FROM users WHERE id = ? LIMIT 1");
        $check->execute([$id]);
        $adminRow = $check->fetch(PDO::FETCH_ASSOC);
        if (!$adminRow || ($adminRow['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Admin tidak dijumpai.');
        }
        $keys = smks3_rbac_admin_permissions($pdo, $id);
        echo json_encode([
            'ok' => true,
            'id' => $id,
            'username' => (string) ($adminRow['username'] ?? ''),
            'permissions' => $keys,
        ]);
        exit;
    }

    if ($action === 'admin_permissions') {
        $id = (int) ($data['id'] ?? 0);
        $keys = $data['permissions'] ?? [];
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        if (!is_array($keys)) {
            $keys = [];
        }
        $check = $pdo->prepare("SELECT id, role, username FROM users WHERE id = ? LIMIT 1");
        $check->execute([$id]);
        $adminRow = $check->fetch(PDO::FETCH_ASSOC);
        if (!$adminRow || ($adminRow['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Admin tidak dijumpai.');
        }
        $beforePerms = smks3_rbac_admin_permissions($pdo, $id);
        smks3_rbac_set_admin_permissions($pdo, $id, $keys);
        $cleanKeys = array_values(array_filter(array_map(static fn($k) => trim((string) $k), $keys), static fn($k) => $k !== ''));
        smks3_activity_log(
            'rbac.admin_permissions',
            ['permissions' => $beforePerms],
            ['permissions' => $cleanKeys],
            'user',
            (string) $id,
            'Kebenaran admin dikemaskini: ' . (string) ($adminRow['username'] ?? ''),
            ['username' => (string) ($adminRow['username'] ?? '')]
        );
        echo json_encode([
            'ok' => true,
            'message' => 'Kebenaran admin disimpan.',
            'id' => $id,
            'permission_count' => count($cleanKeys),
        ]);
        exit;
    }

    if ($action === 'admin_create') {
        $username = smks3_normalize_username((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $unitId = (int) ($data['unit_id'] ?? 0);
        if ($username === '' || strlen($password) < 6) {
            throw new InvalidArgumentException('Nama pengguna dan kata laluan (min 6 aksara) diperlukan.');
        }
        if (!smks3_is_valid_username($username)) {
            throw new InvalidArgumentException(smks3_username_validation_error());
        }
        if ($unitId < 1) {
            throw new InvalidArgumentException('Sila pilih unit untuk admin.');
        }
        $check = $pdo->prepare('SELECT id, name FROM units WHERE id = ?');
        $check->execute([$unitId]);
        $unitRow = $check->fetch(PDO::FETCH_ASSOC);
        if (!$unitRow) {
            throw new InvalidArgumentException('Unit tidak dijumpai.');
        }
        $exists = $pdo->prepare('SELECT id FROM users WHERE ' . smks3_sql_username_equals_ci('username') . ' LIMIT 1');
        $exists->execute([$username]);
        if ($exists->fetchColumn()) {
            throw new InvalidArgumentException('Nama pengguna sudah wujud.');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        smks3_ensure_users_is_active_column($pdo);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role, unit_id, is_active) VALUES (?, ?, ?, ?, 1)');
        $stmt->execute([$username, $hash, 'admin', $unitId]);
        $newId = (int) $pdo->lastInsertId();
        $adminPayload = [
            'id' => $newId,
            'username' => $username,
            'role' => 'admin',
            'unit_id' => $unitId,
            'unit_name' => (string) ($unitRow['name'] ?? ''),
            'is_active' => 1,
            'permission_count' => 0,
        ];
        smks3_activity_log('rbac.admin_create', null, $adminPayload, 'user', (string) $newId, 'Admin didaftarkan: ' . $username);
        echo json_encode([
            'ok' => true,
            'message' => 'Admin didaftarkan.',
            'admin' => $adminPayload,
        ]);
        exit;
    }

    if ($action === 'admin_set_active') {
        $id = (int) ($data['id'] ?? 0);
        $active = !empty($data['is_active']) ? 1 : 0;
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        smks3_ensure_users_is_active_column($pdo);
        $user = $pdo->prepare('SELECT id, role, username, is_active FROM users WHERE id = ? LIMIT 1');
        $user->execute([$id]);
        $row = $user->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($row['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Admin tidak dijumpai.');
        }
        if ($active === 0 && ($row['username'] ?? '') === ($_SESSION['username'] ?? '')) {
            throw new InvalidArgumentException('Tidak boleh nyahaktifkan akaun sendiri.');
        }
        $beforeActive = (int) ($row['is_active'] ?? 1);
        $stmt = $pdo->prepare('UPDATE users SET is_active = ? WHERE id = ?');
        $stmt->execute([$active, $id]);
        smks3_activity_log(
            'rbac.admin_set_active',
            ['id' => $id, 'username' => (string) ($row['username'] ?? ''), 'is_active' => $beforeActive],
            ['id' => $id, 'username' => (string) ($row['username'] ?? ''), 'is_active' => $active],
            'user',
            (string) $id,
            $active ? 'Admin diaktifkan: ' . ($row['username'] ?? '') : 'Admin dinyahaktifkan: ' . ($row['username'] ?? '')
        );
        echo json_encode([
            'ok' => true,
            'message' => $active ? 'Admin diaktifkan.' : 'Admin dinyahaktifkan.',
            'admin' => [
                'id' => $id,
                'is_active' => $active,
            ],
        ]);
        exit;
    }

    if ($action === 'admin_update') {
        $id = (int) ($data['id'] ?? 0);
        $unitId = (int) ($data['unit_id'] ?? 0);
        $password = (string) ($data['password'] ?? '');
        $username = smks3_normalize_username((string) ($data['username'] ?? ''));
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        $user = $pdo->prepare('SELECT id, role, username, unit_id FROM users WHERE id = ? LIMIT 1');
        $user->execute([$id]);
        $row = $user->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($row['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Admin tidak dijumpai.');
        }
        if ($username === '') {
            $username = (string) ($row['username'] ?? '');
        }
        if ($username === '') {
            throw new InvalidArgumentException('Nama pengguna diperlukan.');
        }
        if (!smks3_is_valid_username($username)) {
            throw new InvalidArgumentException(smks3_username_validation_error());
        }
        if ($unitId < 1) {
            throw new InvalidArgumentException('Sila pilih unit.');
        }
        $check = $pdo->prepare('SELECT id, name FROM units WHERE id = ?');
        $check->execute([$unitId]);
        $unitRow = $check->fetch(PDO::FETCH_ASSOC);
        if (!$unitRow) {
            throw new InvalidArgumentException('Unit tidak dijumpai.');
        }
        $exists = $pdo->prepare('SELECT id FROM users WHERE ' . smks3_sql_username_equals_ci('username') . ' AND id <> ? LIMIT 1');
        $exists->execute([$username, $id]);
        if ($exists->fetchColumn()) {
            throw new InvalidArgumentException('Nama pengguna sudah digunakan.');
        }
        $oldUsername = (string) ($row['username'] ?? '');
        $before = [
            'id' => $id,
            'username' => $oldUsername,
            'unit_id' => isset($row['unit_id']) ? (int) $row['unit_id'] : null,
            'password_changed' => false,
        ];
        $passwordChanged = $password !== '';
        if ($passwordChanged) {
            if (strlen($password) < 6) {
                throw new InvalidArgumentException('Kata laluan baharu minimum 6 aksara.');
            }
            $stmt = $pdo->prepare('UPDATE users SET username = ?, unit_id = ?, password = ?, role = ? WHERE id = ?');
            $stmt->execute([$username, $unitId, password_hash($password, PASSWORD_DEFAULT), 'admin', $id]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET username = ?, unit_id = ?, role = ? WHERE id = ?');
            $stmt->execute([$username, $unitId, 'admin', $id]);
        }
        if ($oldUsername !== '' && $oldUsername === ($_SESSION['username'] ?? '') && $username !== $oldUsername) {
            $_SESSION['username'] = $username;
        }
        $after = [
            'id' => $id,
            'username' => $username,
            'unit_id' => $unitId,
            'unit_name' => (string) ($unitRow['name'] ?? ''),
            'password_changed' => $passwordChanged,
        ];
        smks3_activity_log('rbac.admin_update', $before, $after, 'user', (string) $id, 'Admin dikemaskini: ' . $username);
        echo json_encode([
            'ok' => true,
            'message' => 'Admin dikemaskini.',
            'admin' => [
                'id' => $id,
                'username' => $username,
                'unit_id' => $unitId,
                'unit_name' => (string) ($unitRow['name'] ?? ''),
            ],
        ]);
        exit;
    }

    if ($action === 'admin_delete') {
        $id = (int) ($data['id'] ?? 0);
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        $user = $pdo->prepare("SELECT id, role, username FROM users WHERE id = ? LIMIT 1");
        $user->execute([$id]);
        $row = $user->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($row['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Tidak boleh padam akaun ini.');
        }
        if (($row['username'] ?? '') === ($_SESSION['username'] ?? '')) {
            throw new InvalidArgumentException('Tidak boleh padam akaun sendiri.');
        }
        $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        smks3_activity_log(
            'rbac.admin_delete',
            ['id' => $id, 'username' => (string) ($row['username'] ?? ''), 'role' => (string) ($row['role'] ?? '')],
            null,
            'user',
            (string) $id,
            'Admin dipadam: ' . (string) ($row['username'] ?? '')
        );
        echo json_encode(['ok' => true, 'message' => 'Admin dipadam.', 'id' => $id]);
        exit;
    }

    if ($action === 'site_setting_public_external_docs') {
        $enabled = !empty($data['enabled']) && (string) $data['enabled'] !== '0';
        $before = ['public_external_docs' => smks3_public_external_docs_enabled()];
        if (!smks3_set_public_external_docs($enabled)) {
            throw new RuntimeException('Gagal simpan tetapan.');
        }
        smks3_activity_log(
            'rbac.site_setting',
            $before,
            ['public_external_docs' => $enabled],
            'site_setting',
            'public_external_docs',
            $enabled ? 'Akses awam Google Docs diaktifkan.' : 'Akses awam Google Docs dimatikan.'
        );
        echo json_encode([
            'ok' => true,
            'message' => $enabled
                ? 'Akses awam ke Google Sheets / Drive / Docs diaktifkan.'
                : 'Akses awam dimatikan. Hanya admin/superadmin boleh membuka pautan Sheets / Drive / Docs.',
            'enabled' => $enabled,
        ]);
        exit;
    }

    if ($action === 'media_trash_list') {
        smks3_ensure_media_trash_schema($pdo);
        $result = smks3_media_trash_list($pdo, [
            'page' => (int) ($data['page'] ?? 1),
            'per_page' => (int) ($data['per_page'] ?? 20),
            'q' => (string) ($data['q'] ?? ''),
            'kind' => (string) ($data['kind'] ?? ''),
        ]);
        echo json_encode(['ok' => true] + $result);
        exit;
    }

    if ($action === 'media_trash_purge') {
        smks3_ensure_media_trash_schema($pdo);
        $id = (int) ($data['id'] ?? 0);
        $row = smks3_media_trash_get($pdo, $id);
        if (!$row) {
            throw new InvalidArgumentException('Fail sampah tidak dijumpai.');
        }
        $before = smks3_media_trash_row_public($row);
        if (!smks3_media_trash_purge($pdo, $id)) {
            throw new RuntimeException('Gagal padam kekal fail.');
        }
        smks3_activity_log(
            'rbac.media_trash_purge',
            $before,
            null,
            'media_trash',
            (string) $id,
            'Fail dipadam kekal dari tong sampah: ' . (string) ($before['file_name'] ?? '')
        );
        echo json_encode([
            'ok' => true,
            'message' => 'Fail dipadam kekal.',
            'id' => $id,
        ]);
        exit;
    }

    if ($action === 'media_trash_purge_bulk') {
        smks3_ensure_media_trash_schema($pdo);
        $idsRaw = $data['ids'] ?? [];
        if (!is_array($idsRaw)) {
            throw new InvalidArgumentException('Senarai ID tidak sah.');
        }
        $ids = [];
        foreach ($idsRaw as $rawId) {
            $id = (int) $rawId;
            if ($id > 0) {
                $ids[$id] = $id;
            }
        }
        $ids = array_values($ids);
        if ($ids === []) {
            throw new InvalidArgumentException('Sila pilih sekurang-kurangnya satu fail.');
        }
        if (count($ids) > 100) {
            throw new InvalidArgumentException('Maksimum 100 fail setiap tindakan pukal.');
        }
        $deleted = 0;
        $names = [];
        foreach ($ids as $id) {
            $row = smks3_media_trash_get($pdo, $id);
            if (!$row) {
                continue;
            }
            $names[] = (string) ($row['file_name'] ?? ('#' . $id));
            if (smks3_media_trash_purge($pdo, $id)) {
                $deleted++;
            }
        }
        smks3_activity_log(
            'rbac.media_trash_purge_bulk',
            ['ids' => $ids, 'names' => $names],
            ['deleted' => $deleted],
            'media_trash',
            null,
            'Padam kekal pukal dari tong sampah (' . $deleted . ' fail).'
        );
        echo json_encode([
            'ok' => true,
            'message' => $deleted > 0
                ? ($deleted . ' fail dipadam kekal.')
                : 'Tiada fail dipadam.',
            'deleted' => $deleted,
        ]);
        exit;
    }

    throw new InvalidArgumentException('Tindakan tidak dikenali.');
} catch (InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Ralat sistem. Sila cuba lagi.']);
}
