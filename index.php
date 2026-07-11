<?php

declare(strict_types=1);

/**
 * Front controller — all portal routes enter here.
 */
require __DIR__ . '/app/bootstrap.php';

use App\Core\Router;

$router = new Router();
require __DIR__ . '/routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
