<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONFIG_PATH', BASE_PATH . '/config');
define('VIEW_PATH', APP_PATH . '/Views');

require_once APP_PATH . '/Support/security.php';
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/Support/helpers.php';
require_once APP_PATH . '/Support/rbac.php';
require_once APP_PATH . '/Support/kurikulum.php';
require_once APP_PATH . '/Support/hal-ehwal.php';
require_once APP_PATH . '/Support/pibg.php';
require_once APP_PATH . '/Support/layout-chrome.php';
require_once APP_PATH . '/Support/breadcrumbs.php';
require_once APP_PATH . '/Support/visit_stats.php';
require_once APP_PATH . '/Support/upload_helper.php';
require_once APP_PATH . '/Support/pdf_viewer.php';
require_once APP_PATH . '/Support/view.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = APP_PATH . '/' . $relative . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

smks3_ensure_session();
