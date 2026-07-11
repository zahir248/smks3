<?php

declare(strict_types=1);

/**
 * Security helpers: env loading, CSRF, login rate limit, upload MIME checks.
 */

function smks3_load_dotenv(?string $path = null): void
{
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;
    $path = $path ?? (defined('BASE_PATH') ? BASE_PATH . '/.env' : dirname(__DIR__, 2) . '/.env');
    if (!is_file($path) || !is_readable($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if ($name === '') {
            continue;
        }
        if (
            (str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))
        ) {
            $value = substr($value, 1, -1);
        }
        if (getenv($name) === false) {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
        }
    }
}

function smks3_env(string $key, ?string $default = null): ?string
{
    smks3_load_dotenv();
    $val = $_ENV[$key] ?? getenv($key);
    if ($val === false || $val === null || $val === '') {
        return $default;
    }
    return (string) $val;
}

function smks3_csrf_token(): string
{
    smks3_ensure_session();
    if (empty($_SESSION['_csrf']) || !is_string($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function smks3_csrf_validate(?string $token): bool
{
    smks3_ensure_session();
    $expected = $_SESSION['_csrf'] ?? '';
    if (!is_string($expected) || $expected === '' || $token === null || $token === '') {
        return false;
    }
    return hash_equals($expected, $token);
}

/** Read CSRF from header or request payload. */
function smks3_request_csrf_token(?array $data = null): ?string
{
    $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (is_string($header) && $header !== '') {
        return $header;
    }
    if (is_array($data) && isset($data['csrf_token'])) {
        return (string) $data['csrf_token'];
    }
    if (isset($_POST['csrf_token'])) {
        return (string) $_POST['csrf_token'];
    }
    return null;
}

function smks3_require_csrf(?array $data = null): void
{
    if (!smks3_csrf_validate(smks3_request_csrf_token($data))) {
        http_response_code(403);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'Token keselamatan tidak sah. Muat semula halaman.']);
        exit;
    }
}

function smks3_client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return is_string($ip) && $ip !== '' ? $ip : '0.0.0.0';
}

function smks3_login_rate_limit_path(string $ip): string
{
    $dir = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/storage/rate_limit';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir . '/login_' . hash('sha256', $ip) . '.json';
}

/**
 * @return array{count:int,first:int}
 */
function smks3_login_rate_limit_read(string $ip): array
{
    $file = smks3_login_rate_limit_path($ip);
    if (!is_file($file)) {
        return ['count' => 0, 'first' => time()];
    }
    $raw = @file_get_contents($file);
    $data = is_string($raw) ? json_decode($raw, true) : null;
    if (!is_array($data)) {
        return ['count' => 0, 'first' => time()];
    }
    return [
        'count' => (int) ($data['count'] ?? 0),
        'first' => (int) ($data['first'] ?? time()),
    ];
}

function smks3_login_rate_limit_window(): int
{
    return 15 * 60;
}

function smks3_login_rate_limit_max(): int
{
    return 5;
}

/** @return string|null Error message if blocked */
function smks3_login_rate_limit_check(?string $ip = null): ?string
{
    $ip = $ip ?? smks3_client_ip();
    $state = smks3_login_rate_limit_read($ip);
    $window = smks3_login_rate_limit_window();
    if ($state['count'] >= smks3_login_rate_limit_max() && (time() - $state['first']) < $window) {
        $wait = max(1, (int) ceil(($window - (time() - $state['first'])) / 60));
        return 'Terlalu banyak percubaan log masuk. Sila cuba lagi dalam ' . $wait . ' minit.';
    }
    if ((time() - $state['first']) >= $window) {
        @unlink(smks3_login_rate_limit_path($ip));
    }
    return null;
}

function smks3_login_rate_limit_hit(?string $ip = null): void
{
    $ip = $ip ?? smks3_client_ip();
    $window = smks3_login_rate_limit_window();
    $state = smks3_login_rate_limit_read($ip);
    if ((time() - $state['first']) >= $window) {
        $state = ['count' => 0, 'first' => time()];
    }
    $state['count']++;
    if ($state['count'] === 1) {
        $state['first'] = time();
    }
    @file_put_contents(smks3_login_rate_limit_path($ip), json_encode($state), LOCK_EX);
}

function smks3_login_rate_limit_clear(?string $ip = null): void
{
    $ip = $ip ?? smks3_client_ip();
    $file = smks3_login_rate_limit_path($ip);
    if (is_file($file)) {
        @unlink($file);
    }
}

/** Safe site_content / table keys only. */
function smks3_is_safe_content_key(string $key): bool
{
    return (bool) preg_match('/^[a-z][a-z0-9_]{0,119}$/', $key);
}

/**
 * Map extension → allowed MIME types for uploads.
 *
 * @param list<string> $allowedExt
 * @return array<string, list<string>>
 */
function smks3_upload_mime_map(array $allowedExt): array
{
    $all = [
        'jpg' => ['image/jpeg'],
        'jpeg' => ['image/jpeg'],
        'png' => ['image/png'],
        'webp' => ['image/webp'],
        'gif' => ['image/gif'],
        'pdf' => ['application/pdf'],
        'doc' => ['application/msword'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        'xls' => ['application/vnd.ms-excel'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        'ppt' => ['application/vnd.ms-powerpoint'],
        'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
        'mp4' => ['video/mp4'],
        'webm' => ['video/webm'],
    ];
    $out = [];
    foreach ($allowedExt as $ext) {
        $ext = strtolower($ext);
        if (isset($all[$ext])) {
            $out[$ext] = $all[$ext];
        }
    }
    return $out;
}

function smks3_assert_upload_mime(string $tmpPath, string $ext, array $allowedExt): void
{
    $map = smks3_upload_mime_map($allowedExt);
    $ext = strtolower($ext);
    if (!isset($map[$ext])) {
        throw new RuntimeException('Format fail tidak dibenarkan.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmpPath) ?: '';
    if (!in_array($mime, $map[$ext], true)) {
        throw new RuntimeException('Jenis fail tidak sepadan dengan sambungan.');
    }
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
        $info = @getimagesize($tmpPath);
        if ($info === false) {
            throw new RuntimeException('Fail imej tidak sah.');
        }
    }
}
