<?php
/**
 * Helper functions for School Website
 */

function getSettings() {
    return [
        'school_name' => 'Sekolah Menengah Kebangsaaan Seremban 3',
        'tagline' => '',
        'address' => 'Jalan Seremban Tiga 3 25, Seremban 3, 70300 Seremban, Negeri Sembilan',
        'phone' => '011-65732533',
        'email' => 'nea4117@moe.edu.my',
        'about_summary' => 'Sekolah Menengah Kebangsaan Seremban 3 ialah sekolah menengah yang komited menyediakan pendidikan vokasional berkualiti.',
    ];
}

/** News items: newest `published_at` first. */
function smks3_sort_news_by_published_desc(array $items): array
{
    usort($items, static function (array $a, array $b): int {
        $ta = strtotime($a['published_at'] ?? '0');
        $tb = strtotime($b['published_at'] ?? '0');
        return $tb <=> $ta;
    });
    return $items;
}

/** Plain text → <p> blocks (double newlines = new paragraph; single newlines inside a block get <br>). */
function smks3_plain_text_to_paragraphs(string $text): string
{
    $text = trim($text);
    if ($text === '') {
        return '';
    }
    $parts = preg_split('/\r\n\r\n|\n\n|\r\r/', $text, -1, PREG_SPLIT_NO_EMPTY);
    if (count($parts) === 1 && strpbrk($parts[0], "\r\n") !== false) {
        $lines = preg_split('/\r\n|\n|\r/', $parts[0], -1, PREG_SPLIT_NO_EMPTY);
        $html = '';
        foreach ($lines as $line) {
            $t = trim($line);
            if ($t === '') {
                continue;
            }
            $html .= '<p>' . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . '</p>';
        }
        return $html;
    }
    $html = '';
    foreach ($parts as $part) {
        $t = trim($part);
        if ($t === '') {
            continue;
        }
        $inner = nl2br(htmlspecialchars($t, ENT_QUOTES, 'UTF-8'));
        $html .= '<p>' . $inner . '</p>';
    }
    return $html;
}

/**
 * News article body: trusted HTML if it contains tags; else plain text as paragraphs.
 * Falls back to excerpt when content is empty.
 */
function smks3_news_body_html(?string $content, ?string $fallbackExcerpt = ''): string
{
    $content = trim((string) $content);
    $fallback = trim((string) $fallbackExcerpt);
    if ($content !== '') {
        if (preg_match('/<[a-z][a-z0-9]*\b/i', $content)) {
            return $content;
        }
        return smks3_plain_text_to_paragraphs($content);
    }
    if ($fallback !== '') {
        return smks3_plain_text_to_paragraphs($fallback);
    }
    return '';
}

/** Published news rows, newest first. Empty if DB unavailable or `news` missing. */
function smks3_fetch_published_news(?int $limit = null): array
{
    require_once __DIR__ . '/../config/database.php';
    try {
        $pdo = getConnection();
        $sql = 'SELECT id, title, slug, excerpt, content, published_at FROM news WHERE published_at IS NOT NULL ORDER BY published_at DESC';
        if ($limit !== null && $limit > 0) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        return [];
    }
}

/** Single news row by id, or null. */
function smks3_fetch_news_by_id(int $id): ?array
{
    require_once __DIR__ . '/../config/database.php';
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare('SELECT id, title, slug, excerpt, content, published_at FROM news WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/** Single published news row by URL slug, or null. */
function smks3_fetch_news_by_slug(string $slug): ?array
{
    $slug = trim($slug);
    if ($slug === '' || !preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
        return null;
    }
    require_once __DIR__ . '/../config/database.php';
    try {
        $pdo = getConnection();
        $stmt = $pdo->prepare(
            'SELECT id, title, slug, excerpt, content, published_at FROM news WHERE slug = :slug AND published_at IS NOT NULL LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    } catch (Throwable $e) {
        return null;
    }
}

/**
 * Paginated published news from DB. Returns null on connection/query failure.
 * Keys: items, total, total_pages, page, per_page
 */
function smks3_fetch_published_news_paginated(int $page, int $perPage = 3): ?array
{
    require_once __DIR__ . '/../config/database.php';
    $perPage = max(1, min(50, $perPage));
    $page = max(1, $page);
    try {
        $pdo = getConnection();
        $total = (int) $pdo->query('SELECT COUNT(*) FROM news WHERE published_at IS NOT NULL')->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);
        $offset = ($page - 1) * $perPage;
        $stmt = $pdo->prepare(
            'SELECT id, title, slug, excerpt, content, published_at FROM news WHERE published_at IS NOT NULL ORDER BY published_at DESC LIMIT :lim OFFSET :off'
        );
        $stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'items' => $items,
            'total' => $total,
            'total_pages' => $totalPages,
            'page' => $page,
            'per_page' => $perPage,
        ];
    } catch (Throwable $e) {
        return null;
    }
}

/** Link to one article: prefers `?slug=` so the address bar shows a readable slug, not a numeric id. */
function smks3_news_article_url(array $n): string
{
    $slug = isset($n['slug']) ? trim((string) $n['slug']) : '';
    if ($slug !== '' && preg_match('/^[a-zA-Z0-9_-]+$/', $slug)) {
        return 'news.php?' . http_build_query(['slug' => $slug]);
    }
    if (!empty($n['id'])) {
        return 'news.php?' . http_build_query(['id' => (int) $n['id']]);
    }
    return 'news.php';
}
