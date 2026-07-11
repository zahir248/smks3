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
        echo json_encode([
            'ok' => true,
            'message' => 'Unit ditambah.',
            'unit' => [
                'id' => $newId,
                'name' => $name,
                'slug' => $slug,
                'description' => $description !== '' ? $description : null,
                'admin_count' => 0,
                'permission_count' => 0,
            ],
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
        $slug = smks3_rbac_make_unit_slug($name, $pdo, $id);
        $stmt = $pdo->prepare('UPDATE units SET name = ?, slug = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $slug, $description !== '' ? $description : null, $id]);
        echo json_encode(['ok' => true, 'message' => 'Unit dikemaskini.']);
        exit;
    }

    if ($action === 'unit_delete') {
        $id = (int) ($data['id'] ?? 0);
        if ($id < 1) {
            throw new InvalidArgumentException('ID unit tidak sah.');
        }
        $pdo->prepare('UPDATE users SET unit_id = NULL WHERE unit_id = ?')->execute([$id]);
        $pdo->prepare('DELETE FROM units WHERE id = ?')->execute([$id]);
        echo json_encode(['ok' => true, 'message' => 'Unit dipadam.', 'id' => $id]);
        exit;
    }

    if ($action === 'unit_get_permissions') {
        $id = (int) ($data['id'] ?? 0);
        if ($id < 1) {
            throw new InvalidArgumentException('ID unit tidak sah.');
        }
        $check = $pdo->prepare('SELECT id, name FROM units WHERE id = ?');
        $check->execute([$id]);
        $unitRow = $check->fetch(PDO::FETCH_ASSOC);
        if (!$unitRow) {
            throw new InvalidArgumentException('Unit tidak dijumpai.');
        }
        $keys = smks3_rbac_unit_permissions($pdo, $id);
        echo json_encode([
            'ok' => true,
            'id' => $id,
            'name' => (string) ($unitRow['name'] ?? ''),
            'permissions' => $keys,
        ]);
        exit;
    }

    if ($action === 'unit_permissions') {
        $id = (int) ($data['id'] ?? 0);
        $keys = $data['permissions'] ?? [];
        if ($id < 1) {
            throw new InvalidArgumentException('ID unit tidak sah.');
        }
        if (!is_array($keys)) {
            $keys = [];
        }
        smks3_rbac_set_unit_permissions($pdo, $id, $keys);
        echo json_encode([
            'ok' => true,
            'message' => 'Kebenaran unit disimpan.',
            'id' => $id,
            'permission_count' => count(array_filter($keys, static fn($k) => trim((string) $k) !== '')),
        ]);
        exit;
    }

    if ($action === 'admin_create') {
        $username = trim((string) ($data['username'] ?? ''));
        $password = (string) ($data['password'] ?? '');
        $unitId = (int) ($data['unit_id'] ?? 0);
        if ($username === '' || strlen($password) < 6) {
            throw new InvalidArgumentException('Nama pengguna dan kata laluan (min 6 aksara) diperlukan.');
        }
        if (!preg_match('/^[A-Za-z0-9._-]{3,100}$/', $username)) {
            throw new InvalidArgumentException('Nama pengguna mesti 3–100 aksara (huruf, nombor, . _ -).');
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
        $exists = $pdo->prepare('SELECT id FROM users WHERE username = ? LIMIT 1');
        $exists->execute([$username]);
        if ($exists->fetchColumn()) {
            throw new InvalidArgumentException('Nama pengguna sudah wujud.');
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        smks3_ensure_users_is_active_column($pdo);
        $stmt = $pdo->prepare('INSERT INTO users (username, password, role, unit_id, is_active) VALUES (?, ?, ?, ?, 1)');
        $stmt->execute([$username, $hash, 'admin', $unitId]);
        $newId = (int) $pdo->lastInsertId();
        echo json_encode([
            'ok' => true,
            'message' => 'Admin didaftarkan.',
            'admin' => [
                'id' => $newId,
                'username' => $username,
                'role' => 'admin',
                'unit_id' => $unitId,
                'unit_name' => (string) ($unitRow['name'] ?? ''),
                'is_active' => 1,
            ],
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
        $user = $pdo->prepare('SELECT id, role, username FROM users WHERE id = ? LIMIT 1');
        $user->execute([$id]);
        $row = $user->fetch(PDO::FETCH_ASSOC);
        if (!$row || ($row['role'] ?? '') === 'superadmin') {
            throw new InvalidArgumentException('Admin tidak dijumpai.');
        }
        if ($active === 0 && ($row['username'] ?? '') === ($_SESSION['username'] ?? '')) {
            throw new InvalidArgumentException('Tidak boleh nyahaktifkan akaun sendiri.');
        }
        $stmt = $pdo->prepare('UPDATE users SET is_active = ? WHERE id = ?');
        $stmt->execute([$active, $id]);
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
        $username = trim((string) ($data['username'] ?? ''));
        if ($id < 1) {
            throw new InvalidArgumentException('ID admin tidak sah.');
        }
        $user = $pdo->prepare('SELECT id, role, username FROM users WHERE id = ? LIMIT 1');
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
        if (!preg_match('/^[A-Za-z0-9._-]{3,100}$/', $username)) {
            throw new InvalidArgumentException('Nama pengguna mesti 3–100 aksara (huruf, nombor, . _ -).');
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
        $exists = $pdo->prepare('SELECT id FROM users WHERE username = ? AND id <> ? LIMIT 1');
        $exists->execute([$username, $id]);
        if ($exists->fetchColumn()) {
            throw new InvalidArgumentException('Nama pengguna sudah digunakan.');
        }
        $oldUsername = (string) ($row['username'] ?? '');
        if ($password !== '') {
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
        echo json_encode(['ok' => true, 'message' => 'Admin dipadam.', 'id' => $id]);
        exit;
    }

    if ($action === 'site_setting_public_external_docs') {
        $enabled = !empty($data['enabled']) && (string) $data['enabled'] !== '0';
        if (!smks3_set_public_external_docs($enabled)) {
            throw new RuntimeException('Gagal simpan tetapan.');
        }
        echo json_encode([
            'ok' => true,
            'message' => $enabled
                ? 'Akses awam ke Google Sheets / Drive / Docs diaktifkan.'
                : 'Akses awam dimatikan. Hanya admin/superadmin boleh membuka pautan Sheets / Drive / Docs.',
            'enabled' => $enabled,
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
