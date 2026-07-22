<?php

declare(strict_types=1);

/**
 * Superadmin download of soft-deleted media from the recycle bin.
 */

require_once __DIR__ . '/../app/bootstrap.php';

smks3_ensure_session();

if (!smks3_is_superadmin()) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Akses ditolak.';
    exit;
}

$id = (int) ($_GET['id'] ?? 0);
if ($id < 1) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'ID tidak sah.';
    exit;
}

try {
    $pdo = getConnection();
    $row = smks3_media_trash_get($pdo, $id);
    if (!$row) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Fail tidak dijumpai.';
        exit;
    }
    $path = smks3_media_trash_file_path($row);
    if ($path === null) {
        http_response_code(404);
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Fail tiada pada storan.';
        exit;
    }

    $name = (string) ($row['file_name'] ?? 'fail');
    $name = str_replace(['"', "\r", "\n"], '', $name);
    if ($name === '') {
        $name = 'fail';
    }
    $mime = trim((string) ($row['mime_type'] ?? ''));
    if ($mime === '') {
        $mime = 'application/octet-stream';
    }
    $size = filesize($path);

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . (string) $size);
    header('Content-Disposition: attachment; filename="' . $name . '"');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: private, no-store');
    readfile($path);
    exit;
} catch (Throwable $e) {
    error_log('SMKS3 media-trash-download: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Ralat sistem.';
    exit;
}
