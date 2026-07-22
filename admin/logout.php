<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$idle = isset($_GET['idle']);
$actorUserId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
$actorUsername = (string) ($_SESSION['username'] ?? '');
$actorRole = (string) ($_SESSION['role'] ?? '');

if ($actorUsername !== '') {
    require_once __DIR__ . '/../app/Support/security.php';
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../app/Support/activity_log.php';
    smks3_activity_log(
        $idle ? 'auth.logout_idle' : 'auth.logout',
        null,
        null,
        'session',
        null,
        $idle ? 'Log keluar kerana tidak aktif.' : 'Log keluar.',
        ['reason' => $idle ? 'idle' : 'manual', 'via' => 'admin/logout.php'],
        [
            'user_id' => $actorUserId,
            'username' => $actorUsername,
            'role' => $actorRole,
        ]
    );
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', !empty($params['secure']), !empty($params['httponly']));
}
session_destroy();

header('Location: ../' . ($idle ? '?login=1&idle=1' : ''));
exit;
