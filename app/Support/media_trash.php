<?php

declare(strict_types=1);

/**
 * Soft-deleted media recycle bin for superadmin recovery.
 */

function smks3_media_trash_dir(): string
{
    $dir = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/storage/media_trash';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $deny = $dir . '/.htaccess';
    if (!is_file($deny)) {
        @file_put_contents($deny, "Require all denied\nDeny from all\n");
    }
    return $dir;
}

function smks3_ensure_media_trash_schema(?PDO $pdo = null): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;
    try {
        $pdo = $pdo ?? getConnection();
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS media_trash (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                original_path VARCHAR(500) NOT NULL,
                trash_name VARCHAR(255) NOT NULL,
                file_name VARCHAR(255) NOT NULL,
                file_ext VARCHAR(32) NULL,
                mime_type VARCHAR(120) NULL,
                file_size INT UNSIGNED NULL,
                kind VARCHAR(32) NOT NULL DEFAULT \'other\',
                deleted_by_user_id INT UNSIGNED NULL,
                deleted_by_username VARCHAR(100) NULL,
                deleted_at DATETIME NOT NULL,
                note VARCHAR(255) NULL,
                INDEX idx_media_trash_deleted (deleted_at),
                INDEX idx_media_trash_kind (kind),
                INDEX idx_media_trash_name (file_name)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
    } catch (Throwable $e) {
        error_log('SMKS3 media_trash schema: ' . $e->getMessage());
    }
}

function smks3_media_trash_kind_from_name(string $name): string
{
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp', 'bmp', 'svg'], true)) {
        return 'image';
    }
    if ($ext === 'pdf') {
        return 'pdf';
    }
    if (in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv'], true)) {
        return 'document';
    }
    if (in_array($ext, ['zip', 'rar', '7z'], true)) {
        return 'archive';
    }
    return 'other';
}

/**
 * Soft-delete a project-relative file into the recycle bin.
 * Returns true if moved (or already absent after attempt).
 */
function smks3_media_trash_soft_delete(?string $relativePath, ?string $note = null): bool
{
    $relativePath = str_replace('\\', '/', trim((string) $relativePath));
    if ($relativePath === '' || str_contains($relativePath, '..')) {
        return false;
    }
    // Never trash core brand assets
    $base = basename($relativePath);
    if (in_array($base, ['hero-logo.png', 'favicon-smks3.ico', 'favicon.ico'], true)) {
        return false;
    }

    $full = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2)) . '/' . ltrim($relativePath, '/');
    if (!is_file($full)) {
        return false;
    }

    try {
        $pdo = getConnection();
        smks3_ensure_media_trash_schema($pdo);

        $trashDir = smks3_media_trash_dir();
        $ext = strtolower(pathinfo($base, PATHINFO_EXTENSION));
        $trashName = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . ($ext !== '' ? ('.' . $ext) : '');
        $dest = $trashDir . '/' . $trashName;
        if (!@rename($full, $dest)) {
            // Cross-device fallback
            if (!@copy($full, $dest)) {
                return false;
            }
            @unlink($full);
        }

        $size = @filesize($dest);
        $mime = null;
        if (function_exists('mime_content_type')) {
            $detected = @mime_content_type($dest);
            $mime = is_string($detected) ? $detected : null;
        }

        $userId = function_exists('smks3_current_user_id') ? smks3_current_user_id() : null;
        $username = (string) ($_SESSION['username'] ?? '');

        $stmt = $pdo->prepare(
            'INSERT INTO media_trash
                (original_path, trash_name, file_name, file_ext, mime_type, file_size, kind,
                 deleted_by_user_id, deleted_by_username, deleted_at, note)
             VALUES (?,?,?,?,?,?,?,?,?,NOW(),?)'
        );
        $stmt->execute([
            $relativePath,
            $trashName,
            $base,
            $ext !== '' ? $ext : null,
            $mime,
            $size !== false ? (int) $size : null,
            smks3_media_trash_kind_from_name($base),
            $userId !== null && (int) $userId > 0 ? (int) $userId : null,
            $username !== '' ? $username : null,
            $note !== null && $note !== ''
                ? (function_exists('mb_substr') ? mb_substr($note, 0, 255) : substr($note, 0, 255))
                : null,
        ]);
        return true;
    } catch (Throwable $e) {
        error_log('SMKS3 media_trash soft_delete: ' . $e->getMessage());
        return false;
    }
}

/**
 * @param array{page?:int,per_page?:int,q?:string,kind?:string} $filters
 * @return array{items:list<array<string,mixed>>,total:int,page:int,per_page:int,pages:int}
 */
function smks3_media_trash_list(PDO $pdo, array $filters = []): array
{
    smks3_ensure_media_trash_schema($pdo);

    $page = max(1, (int) ($filters['page'] ?? 1));
    $perPage = (int) ($filters['per_page'] ?? 20);
    if ($perPage < 5) {
        $perPage = 5;
    }
    if ($perPage > 100) {
        $perPage = 100;
    }

    $where = ['1=1'];
    $params = [];

    $kind = trim((string) ($filters['kind'] ?? ''));
    if ($kind !== '' && in_array($kind, ['image', 'pdf', 'document', 'archive', 'other'], true)) {
        $where[] = 'kind = ?';
        $params[] = $kind;
    }

    $q = trim((string) ($filters['q'] ?? ''));
    if ($q !== '') {
        $where[] = '(file_name LIKE ? OR original_path LIKE ? OR deleted_by_username LIKE ? OR note LIKE ?)';
        $like = '%' . $q . '%';
        array_push($params, $like, $like, $like, $like);
    }

    $sqlWhere = implode(' AND ', $where);
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM media_trash WHERE {$sqlWhere}");
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();
    $pages = max(1, (int) ceil($total / $perPage));
    if ($page > $pages) {
        $page = $pages;
    }
    $offset = ($page - 1) * $perPage;

    $stmt = $pdo->prepare(
        "SELECT * FROM media_trash
         WHERE {$sqlWhere}
         ORDER BY deleted_at DESC, id DESC
         LIMIT {$perPage} OFFSET {$offset}"
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $items = [];
    foreach ($rows as $row) {
        $items[] = smks3_media_trash_row_public($row);
    }

    return [
        'items' => $items,
        'total' => $total,
        'page' => $page,
        'per_page' => $perPage,
        'pages' => $pages,
    ];
}

/**
 * @param array<string,mixed> $row
 * @return array<string,mixed>
 */
function smks3_media_trash_row_public(array $row): array
{
    $id = (int) ($row['id'] ?? 0);
    return [
        'id' => $id,
        'original_path' => (string) ($row['original_path'] ?? ''),
        'file_name' => (string) ($row['file_name'] ?? ''),
        'file_ext' => (string) ($row['file_ext'] ?? ''),
        'mime_type' => (string) ($row['mime_type'] ?? ''),
        'file_size' => isset($row['file_size']) ? (int) $row['file_size'] : null,
        'file_size_label' => smks3_media_trash_format_size(isset($row['file_size']) ? (int) $row['file_size'] : null),
        'kind' => (string) ($row['kind'] ?? 'other'),
        'deleted_by_user_id' => isset($row['deleted_by_user_id']) ? (int) $row['deleted_by_user_id'] : null,
        'deleted_by_username' => (string) ($row['deleted_by_username'] ?? ''),
        'deleted_at' => (string) ($row['deleted_at'] ?? ''),
        'note' => (string) ($row['note'] ?? ''),
        'download_url' => 'api/media-trash-download.php?id=' . $id,
        'exists' => smks3_media_trash_file_path($row) !== null,
    ];
}

function smks3_media_trash_format_size(?int $bytes): string
{
    if ($bytes === null || $bytes < 0) {
        return '—';
    }
    if ($bytes < 1024) {
        return $bytes . ' B';
    }
    if ($bytes < 1048576) {
        return round($bytes / 1024, 1) . ' KB';
    }
    return round($bytes / 1048576, 2) . ' MB';
}

/**
 * @param array<string,mixed> $row
 */
function smks3_media_trash_file_path(array $row): ?string
{
    $name = basename((string) ($row['trash_name'] ?? ''));
    if ($name === '' || str_contains($name, '..')) {
        return null;
    }
    $full = smks3_media_trash_dir() . '/' . $name;
    return is_file($full) ? $full : null;
}

/**
 * @return array<string,mixed>|null
 */
function smks3_media_trash_get(PDO $pdo, int $id): ?array
{
    if ($id < 1) {
        return null;
    }
    smks3_ensure_media_trash_schema($pdo);
    $stmt = $pdo->prepare('SELECT * FROM media_trash WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

/**
 * Permanently remove a trash entry and its file.
 */
function smks3_media_trash_purge(PDO $pdo, int $id): bool
{
    $row = smks3_media_trash_get($pdo, $id);
    if (!$row) {
        return false;
    }
    $path = smks3_media_trash_file_path($row);
    if ($path !== null) {
        @unlink($path);
    }
    $stmt = $pdo->prepare('DELETE FROM media_trash WHERE id = ?');
    $stmt->execute([$id]);
    return true;
}
