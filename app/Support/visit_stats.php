<?php

declare(strict_types=1);

const SMKS3_VISIT_COOKIE = 'smks3_visit_day';

function smks3_should_count_visit(): bool
{
    if (PHP_SAPI === 'cli') {
        return false;
    }
    $uri = $_SERVER['SCRIPT_NAME'] ?? '';
    if ($uri !== '' && (
        stripos($uri, '/admin/') !== false
        || stripos($uri, '\\admin\\') !== false
        || stripos($uri, '/errors/') !== false
        || stripos($uri, '\\errors\\') !== false
    )) {
        return false;
    }
    if (smks3_visit_likely_bot()) {
        return false;
    }
    return true;
}

/** Skip common crawlers / previews so stats stay closer to real people. */
function smks3_visit_likely_bot(): bool
{
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    if ($ua === '') {
        return true;
    }
    $needles = [
        'bot', 'crawl', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit',
        'lighthouse', 'headless', 'prerender',
    ];
    foreach ($needles as $n) {
        if (strpos($ua, $n) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * Record at most one visit per browser per calendar day (first page load only).
 * Must run before HTML output (e.g. from header.php) so the cookie can be set.
 */
function smks3_record_visit(): void
{
    if (!smks3_should_count_visit()) {
        return;
    }

    $today = date('Y-m-d');
    if (isset($_COOKIE[SMKS3_VISIT_COOKIE]) && $_COOKIE[SMKS3_VISIT_COOKIE] === $today) {
        return;
    }

    require_once BASE_PATH . '/config/database.php';
    try {
        $pdo = getConnection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS site_visit_daily (
                visit_date DATE NOT NULL PRIMARY KEY,
                visit_count INT UNSIGNED NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
        $stmt = $pdo->prepare(
            'INSERT INTO site_visit_daily (visit_date, visit_count) VALUES (CURDATE(), 1)
             ON DUPLICATE KEY UPDATE visit_count = visit_count + 1'
        );
        $stmt->execute();

        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        setcookie(SMKS3_VISIT_COOKIE, $today, [
            'expires' => time() + 60 * 60 * 24 * 400,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE[SMKS3_VISIT_COOKIE] = $today;
    } catch (Throwable $e) {
        // ignore if DB unavailable
    }
}

/**
 * @return array{total:int,today:int,yesterday:int,week:int,month:int}
 */
function smks3_get_visit_stats(): array
{
    static $cached = null;
    if (is_array($cached)) {
        return $cached;
    }

    $out = ['total' => 0, 'today' => 0, 'yesterday' => 0, 'week' => 0, 'month' => 0];
    require_once BASE_PATH . '/config/database.php';
    try {
        $pdo = getConnection();
        $row = $pdo->query(
            'SELECT
                COALESCE(SUM(visit_count), 0) AS total,
                COALESCE(SUM(CASE WHEN visit_date = CURDATE() THEN visit_count ELSE 0 END), 0) AS today,
                COALESCE(SUM(CASE WHEN visit_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN visit_count ELSE 0 END), 0) AS yesterday,
                COALESCE(SUM(CASE
                    WHEN visit_date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                     AND visit_date <= CURDATE() THEN visit_count ELSE 0 END), 0) AS week,
                COALESCE(SUM(CASE
                    WHEN YEAR(visit_date) = YEAR(CURDATE())
                     AND MONTH(visit_date) = MONTH(CURDATE()) THEN visit_count ELSE 0 END), 0) AS month
             FROM site_visit_daily'
        )->fetch(PDO::FETCH_ASSOC);
        if (is_array($row)) {
            $out = [
                'total' => (int) ($row['total'] ?? 0),
                'today' => (int) ($row['today'] ?? 0),
                'yesterday' => (int) ($row['yesterday'] ?? 0),
                'week' => (int) ($row['week'] ?? 0),
                'month' => (int) ($row['month'] ?? 0),
            ];
        }
    } catch (Throwable $e) {
        // keep zeros
    }

    $cached = $out;
    return $out;
}
