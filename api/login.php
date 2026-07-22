<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../app/bootstrap.php';

smks3_ensure_session();

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

$rateError = smks3_login_rate_limit_check();
if ($rateError !== null) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'error' => $rateError]);
    exit;
}

$username = smks3_normalize_username((string) ($data['username'] ?? ''));
$password = trim((string) ($data['password'] ?? ''));

if ($username === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Sila isi nama pengguna dan kata laluan.']);
    exit;
}

try {
    $pdo = getConnection();
    smks3_ensure_users_is_active_column($pdo);
    $stmt = $pdo->prepare('SELECT * FROM users WHERE ' . smks3_sql_username_equals('username') . ' LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, (string) ($user['password'] ?? ''))) {
        smks3_login_rate_limit_hit();
        http_response_code(401);
        echo json_encode(['ok' => false, 'error' => 'Nama pengguna atau kata laluan tidak sah.']);
        exit;
    }

    if (!smks3_user_row_is_active($user)) {
        smks3_login_rate_limit_hit();
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Akaun admin ini tidak aktif. Sila hubungi pihak pentadbiran.']);
        exit;
    }

    $role = (string) ($user['role'] ?? 'admin');
    if (!in_array($role, ['admin', 'superadmin'], true)) {
        smks3_login_rate_limit_hit();
        http_response_code(403);
        echo json_encode(['ok' => false, 'error' => 'Akaun tidak dibenarkan log masuk.']);
        exit;
    }

    session_regenerate_id(true);
    smks3_login_rate_limit_clear();

    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $role;
    $_SESSION['user_id'] = isset($user['id']) ? (int) $user['id'] : null;
    $_SESSION['edit_preview'] = !empty($user['edit_preview']) ? 1 : 0;
    $_SESSION['unit_id'] = isset($user['unit_id']) && $user['unit_id'] !== null && $user['unit_id'] !== ''
        ? (int) $user['unit_id']
        : null;
    $_SESSION['last_activity'] = time();
    unset($_SESSION['rbac_permissions'], $_SESSION['unit_name']);
    // Keep / rotate CSRF after login
    $_SESSION['_csrf'] = bin2hex(random_bytes(32));

    // Older DBs may not have the column yet — load via helper.
    if (!array_key_exists('edit_preview', $user)) {
        unset($_SESSION['edit_preview']);
        smks3_get_edit_preview();
    }
    smks3_ensure_rbac_schema();
    smks3_rbac_refresh_session_permissions();

    smks3_activity_log(
        'auth.login',
        null,
        [
            'user_id' => (int) ($user['id'] ?? 0),
            'username' => (string) ($user['username'] ?? ''),
            'role' => $role,
            'unit_id' => $_SESSION['unit_id'] ?? null,
        ],
        'user',
        (string) ((int) ($user['id'] ?? 0)),
        'Log masuk berjaya.',
        null,
        [
            'user_id' => (int) ($user['id'] ?? 0),
            'username' => (string) ($user['username'] ?? ''),
            'role' => $role,
        ]
    );

    echo json_encode([
        'ok' => true,
        'message' => 'Log masuk berjaya.',
        'role' => $_SESSION['role'],
        'edit_preview' => !empty($_SESSION['edit_preview']),
        'csrf_token' => smks3_csrf_token(),
    ]);
} catch (Throwable $e) {
    error_log('SMKS3 login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Ralat sistem. Sila cuba lagi.']);
}
