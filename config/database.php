<?php

declare(strict_types=1);

smks3_load_dotenv(dirname(__DIR__) . '/.env');

define('DB_HOST', smks3_env('DB_HOST', 'localhost') ?? 'localhost');
define('DB_NAME', smks3_env('DB_NAME', 'iesbcomm_smks3') ?? 'iesbcomm_smks3');
define('DB_USER', smks3_env('DB_USER', 'root') ?? 'root');
define('DB_PASS', smks3_env('DB_PASS', '') ?? '');

function getConnection(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log('SMKS3 DB connection failed: ' . $e->getMessage());
        http_response_code(503);
        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=utf-8');
        }
        exit('Perkhidmatan pangkalan data tidak tersedia. Sila cuba lagi kemudian.');
    }
}
