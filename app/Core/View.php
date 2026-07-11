<?php

declare(strict_types=1);

namespace App\Core;

final class View
{
    /**
     * @param array<string, mixed> $data
     */
    public static function render(string $view, array $data = [], bool $withLayout = true): void
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        smks3_view_render($viewFile, $data, $withLayout);
    }
}
