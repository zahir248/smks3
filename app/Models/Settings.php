<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

/**
 * Thin data-access helpers for settings (wraps existing functions).
 */
final class Settings
{
    public static function all(): array
    {
        return getSettings();
    }

    public static function save(array $data): void
    {
        smks3_save_settings($data);
    }
}
