<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = [], bool $withLayout = true): void
    {
        View::render($view, $data, $withLayout);
    }

    protected function redirect(string $path): void
    {
        if (!str_starts_with($path, 'http')) {
            $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
            if ($path === '/' || $path === '') {
                $path = ($base === '' ? '' : $base) . '/';
            } else {
                $path = ($base === '' ? '' : $base) . '/' . ltrim($path, '/');
            }
        }
        header('Location: ' . $path);
        exit;
    }
}
