<?php

declare(strict_types=1);

namespace App\Models;

/**
 * News data access (wraps existing helpers).
 */
final class News
{
    public static function latestByYear(\PDO $pdo): array
    {
        $list = getLatestNewsByYear($pdo);
        return is_array($list) ? $list : [];
    }

    public static function findBySlug(string $slug): ?array
    {
        return smks3_fetch_news_by_slug($slug);
    }

    public static function findById(int $id): ?array
    {
        return smks3_fetch_news_by_id($id);
    }

    public static function paginated(int $page, int $perPage, ?int $year = null): array
    {
        return smks3_fetch_published_news_paginated($page, $perPage, $year);
    }
}
