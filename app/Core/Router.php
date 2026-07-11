<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<string, array{0:class-string,1:string}> */
    private array $getRoutes = [];

    /** @var array<string, array{0:class-string,1:string}> */
    private array $postRoutes = [];

    public function get(string $path, array $action): void
    {
        $this->getRoutes[$this->normalize($path)] = $action;
    }

    public function post(string $path, array $action): void
    {
        $this->postRoutes[$this->normalize($path)] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($uri);
        $routes = strtoupper($method) === 'POST' ? $this->postRoutes : $this->getRoutes;

        if (!isset($routes[$path])) {
            // POST fallback to GET route for pages that accept both
            if (strtoupper($method) === 'POST' && isset($this->getRoutes[$path])) {
                $routes = $this->getRoutes;
            } else {
                http_response_code(404);
                require BASE_PATH . '/errors/404.php';
                return;
            }
        }

        [$class, $action] = $routes[$path];
        $controller = new $class();
        $controller->$action();
    }

    private function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?? '/';
        $path = rawurldecode($path);

        // Strip base folder (/smks3)
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($scriptDir !== '/' && $scriptDir !== '' && str_starts_with($path, $scriptDir)) {
            $path = substr($path, strlen($scriptDir)) ?: '/';
        }

        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        // Drop accidental .php
        if (str_ends_with($path, '.php')) {
            $path = substr($path, 0, -4) ?: '/';
        }

        if ($path === '/index') {
            $path = '/';
        }

        return $path;
    }
}
